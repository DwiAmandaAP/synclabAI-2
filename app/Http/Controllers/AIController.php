<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Modul;
use App\Models\Pretest;
use App\Models\Question;
use App\Models\Flashcard;
use App\Models\FlashcardProgress;
use App\Models\Pertemuan;
use App\Models\StudentAnswer;

class AiController extends Controller
{
    // Masukkan URL service Python FastAPI kamu
    private $baseUrl = 'http://127.0.0.1:8001';

    /**
     * 1. TRIGGER GENERATE AI (Flashcard & Soal) dari PDF Modul
     * Dipanggil ketika asisten klik generate AI dari manageModul atau generate awal
     */
    public function generateFromModul(int $id_modul)
    {
        $modul = Modul::findOrFail($id_modul);
        
        // Sesuaikan dengan letak penyimpanan PDF di project kamu (misal di folder public storage)
        $filePath = storage_path('app/public/' . $modul->filepath);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File PDF Modul tidak ditemukan di server.');
        }

        $fileContent = file_get_contents($filePath);
        $fileName = basename($filePath);

        // --- PROSES A: Ambil Flashcard dari FastAPI ---
        $responseFlashcard = Http::timeout(120)
                                 ->attach('file', $fileContent, $fileName)
                                 ->post($this->baseUrl . '/generate-flashcards');

        if ($responseFlashcard->successful()) {
            $flashcards = $responseFlashcard->json();
            foreach ($flashcards as $card) {
                Flashcard::create([
                    'id_modul' => $modul->id,
                    'front'    => $card['front'],
                    'back'     => $card['back'],
                ]);
            }
        }

        // --- PROSES B: Ambil Soal Kuis dari FastAPI ---
        $responseQuiz = Http::timeout(120)
                             ->attach('file', $fileContent, $fileName)
                             ->post($this->baseUrl . '/generate-quiz');

        if ($responseQuiz->successful()) {
            $quizzesFromAi = $responseQuiz->json();

            // Buat rangkuman Kuis (Pretest) di Pertemuan ini
            $pretest = Pretest::create([
                'id_pertemuan' => $modul->id_pertemuan, // Mengambil relasi id_pertemuan dari modul
                'judul_kuis'   => 'Pretest Otomatis - ' . $modul->judul_modul,
            ]);

            foreach ($quizzesFromAi as $q) {
                Question::create([
                    'id_pretest'     => $pretest->id,
                    'question_text'  => $q['question_text'],
                    'option_a'       => $q['option_a'],
                    'option_b'       => $q['option_b'],
                    'option_c'       => $q['option_c'],
                    'option_d'       => $q['option_d'],
                    'correct_option' => $q['correct_option'],
                ]);
            }
        }

        return redirect()->back()->with('success', 'Fitur AI Flashcard dan Kuis berhasil dibuat!');
    }

    /**
     * 1B. GENERATE SOAL PRETEST DARI MODUL
     * Endpoint terpisah untuk asisten generate soal di managePretest
     * Dipanggil ketika asisten klik tombol "Generate Soal" di managePretest
     */
    public function generateSoal(Request $request, int $id_pertemuan)
    {
        $request->validate([
            'id_modul' => 'required|exists:moduls,id',
        ]);

        $modul = Modul::findOrFail($request->id_modul);
        $pertemuan = Pertemuan::findOrFail($id_pertemuan);

        // Cek apakah sudah ada pretest untuk pertemuan ini
        $existingPretest = Pretest::where('id_pertemuan', $id_pertemuan)->first();
        if ($existingPretest) {
            return redirect()->back()->with('error', 'Pretest untuk pertemuan ini sudah ada. Hapus terlebih dahulu jika ingin generate ulang.');
        }

        // Sesuaikan dengan letak penyimpanan PDF di project kamu
        $filePath = storage_path('app/public/' . $modul->filepath);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File PDF Modul tidak ditemukan di server.');
        }

        try {
            $fileContent = file_get_contents($filePath);
            $fileName = basename($filePath);

            // Ambil Soal dari FastAPI
            $responseQuiz = Http::timeout(120)
                                 ->attach('file', $fileContent, $fileName)
                                 ->post($this->baseUrl . '/generate-quiz');

            if ($responseQuiz->successful()) {
                $quizzesFromAi = $responseQuiz->json();

                // Buat Pretest baru
                $pretest = Pretest::create([
                    'id_pertemuan' => $id_pertemuan,
                    'judul_kuis'   => 'Pretest - ' . $modul->judul_modul,
                ]);

                // Simpan semua soal
                foreach ($quizzesFromAi as $q) {
                    Question::create([
                        'id_pretest'     => $pretest->id,
                        'question_text'  => $q['question_text'],
                        'option_a'       => $q['option_a'],
                        'option_b'       => $q['option_b'],
                        'option_c'       => $q['option_c'],
                        'option_d'       => $q['option_d'],
                        'correct_option' => $q['correct_option'],
                    ]);
                }

                return redirect()->back()->with('success', 'Soal pretest berhasil di-generate! Total: ' . count($quizzesFromAi) . ' soal');
            } else {
                return redirect()->back()->with('error', 'Gagal generate soal dari AI service.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * UNTUK MAHASISWA: GENERATE FLASHCARD DARI MODUL
     * Dipanggil ketika mahasiswa klik tombol "Generate Flashcard" di halaman modul
     */
    public function generateFlashcard(Request $request, int $id_modul)
    {
        $modul = Modul::findOrFail($id_modul);
        
        // Sesuaikan dengan letak penyimpanan PDF di project kamu
        $filePath = storage_path('app/public/' . $modul->filepath);

        if (!file_exists($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'File PDF Modul tidak ditemukan di server.'
            ], 404);
        }

        try {
            $fileContent = file_get_contents($filePath);
            $fileName = basename($filePath);

            // Ambil Flashcard dari FastAPI
            $responseFlashcard = Http::timeout(120)
                                     ->attach('file', $fileContent, $fileName)
                                     ->post($this->baseUrl . '/generate-flashcards');

            if ($responseFlashcard->successful()) {
                $flashcards = $responseFlashcard->json();
                $savedCount = 0;

                foreach ($flashcards as $card) {
                    // Cek apakah flashcard sudah pernah dibuat sebelumnya (avoid duplicate)
                    $exists = Flashcard::where('id_modul', $modul->id)
                        ->where('front', $card['front'])
                        ->exists();

                    if (!$exists) {
                        Flashcard::create([
                            'id_modul' => $modul->id,
                            'front'    => $card['front'],
                            'back'     => $card['back'],
                        ]);
                        $savedCount++;
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Flashcard berhasil di-generate! Total: ' . $savedCount . ' flashcard baru',
                    'count' => $savedCount,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal generate flashcard dari AI service.'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    public function reviewFlashcard(Request $request, int $id_flashcard)
    {
        $request->validate([
            'score' => 'required|integer|between:0,5' // 0 = Lupa total, 5 = Sangat ingat
        ]);

        $userId = Auth::id();
        $score = $request->score;

        // Ambil data progress atau buat baru jika siswa pertama kali melihat kartu ini
        $progress = FlashcardProgress::firstOrCreate(
            ['id_user' => $userId, 'id_flashcard' => $id_flashcard],
            ['interval' => 1, 'ease_factor' => 2.5, 'repetitions' => 0]
        );

        $ef = $progress->ease_factor;
        $rep = $progress->repetitions;
        $interval = $progress->interval;

        // Implementasi Aturan Matematika Algoritma SM-2
        if ($score >= 3) { // Jika siswa menjawab benar/ingat (skor 3, 4, 5)
            if ($rep == 0) {
                $interval = 1;
            } elseif ($rep == 1) {
                $interval = 6;
            } else {
                $interval = round($interval * $ef);
            }
            $rep++;
        } else { // Jika siswa menjawab salah/lupa (skor 0, 1, 2)
            $rep = 0;
            $interval = 1;
        }

        // Koreksi Rumus Ease Factor (Faktor Kemudahan Kartu)
        $ef = $ef + (0.1 - (5 - $score) * (0.08 + (5 - $score) * 0.02));
        if ($ef < 1.3) {
            $ef = 1.3; // Batas minimal ease factor agar tidak stuck berkali-kali
        }

        // Simpan pembaruan jadwal ke database
        $progress->update([
            'interval'         => $interval,
            'ease_factor'      => $ef,
            'repetitions'      => $rep,
            'next_review_date' => now()->addDays($interval)->toDateString(),
        ]);

        return response()->json([
            'message' => 'Progress review kartu berhasil diperbarui!',
            'next_review' => $interval . ' hari lagi'
        ]);
    }

    /**
     * 3. GRADE PRETEST OTOMATIS
     * Menghitung skor pretest berdasarkan jawaban yang dikirim siswa
     * Menyimpan hasil ke tabel nilai (nilai_pretest)
     */
    public function gradePretest(Request $request, int $id_pretest)
    {
        $request->validate([
            'answers' => 'required|array', // Format: ['id_question' => 'user_answer']
        ]);

        $userId = Auth::id();
        $answers = $request->answers;
        $pretest = Pretest::with('questions')->findOrFail($id_pretest);

        $correctCount = 0;
        $totalQuestions = count($pretest->questions);

        // Simpan setiap jawaban siswa dan hitung yang benar
        foreach ($answers as $questionId => $userAnswer) {
            $question = $pretest->questions->find($questionId);
            
            if ($question) {
                $isCorrect = strtoupper($userAnswer) === strtoupper($question->correct_option);
                
                if ($isCorrect) {
                    $correctCount++;
                }

                // Simpan jawaban siswa
                StudentAnswer::updateOrCreate(
                    [
                        'id_user' => $userId,
                        'id_pretest' => $id_pretest,
                        'id_question' => $questionId,
                    ],
                    [
                        'user_answer' => strtoupper($userAnswer),
                        'is_correct' => $isCorrect,
                    ]
                );
            }
        }

        // Hitung skor (skala 0-100)
        $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100) : 0;

        // Simpan ke tabel nilai
        $pertemuan = $pretest->pertemuan;
        $nilai = \App\Models\Nilai::updateOrCreate(
            [
                'id_pertemuan' => $pertemuan->id,
                'id_user' => $userId,
            ],
            [
                'nilai_pretest' => $score,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Pretest berhasil dinilai!',
            'score' => $score,
            'correctCount' => $correctCount,
            'totalQuestions' => $totalQuestions,
            'percentage' => $score . '%',
        ]);
    }
}
