<?php

namespace App\Http\Controllers;

use App\Models\Praktikum;
use App\Models\Nilai;
use App\Models\Presensi;
use App\Models\Modul;
use App\Models\Pertemuan;
use App\Models\PengumpulanLaporan;
use App\Models\Pretest;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\StudentAnswer;
use App\Models\Flashcard;
use App\Models\FlashcardProgress;

class MahasiswaController extends Controller
{
    public function getMyPretest()
    {
        $user = Auth::user();

        $moduls = Modul::with(['pertemuan.praktikum'])
            ->whereHas('pertemuan.praktikum.jadwals.pendaftarans', function ($query) use ($user) {
                $query->where('id_user', $user->id);
            })
            ->get();

        $pretestData = $moduls->map(function ($modul) use ($user) {
            $pertemuan  = $modul->pertemuan;
            $praktikum  = $pertemuan?->praktikum;

            $statusAbsen = Presensi::where('id_pertemuan', $modul->id_pertemuan)
                ->where('id_user', $user->id)
                ->exists();

            $statusPretest = Nilai::where('id_pertemuan', $modul->id_pertemuan)
                ->where('id_user', $user->id)
                ->whereNotNull('nilai_pretest')
                ->exists();

            $statusLaporan = PengumpulanLaporan::where('id_pertemuan', $modul->id_pertemuan)
                ->where('id_user', $user->id)
                ->exists();

            return [
                'id'            => $modul->id,
                'matkul'        => $praktikum?->nama_praktikum ?? 'Praktikum',
                'modul'         => $modul->judul_modul ?? 'Modul',
                'kode'          => ($praktikum?->kode_praktikum . '-M' . $pertemuan?->pertemuan_ke) ?? 'M1',
                'statusAbsen'   => $statusAbsen,
                'statusPretest' => $statusPretest,
                'statusLaporan' => $statusLaporan,
                'fileLaporan'   => null,
            ];
        })->values();

        $matkulList = $moduls
            ->pluck('pertemuan.praktikum.nama_praktikum')
            ->unique()->filter()->values();

        return view('Mahasiswa.pretest', compact('pretestData', 'matkulList'));
    }

    // ─── Endpoint: Presensi / Absen ──────────────────────────────────────────

    public function absenPretest(Request $request, $modulId)
    {
        $user  = Auth::user();
        $modul = Modul::findOrFail($modulId);

        $sudahAbsen = Presensi::where('id_pertemuan', $modul->id_pertemuan)
            ->where('id_user', $user->id)
            ->exists();

        if ($sudahAbsen) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan presensi untuk pertemuan ini.'
            ], 409);
        }

        try {
            Presensi::create([
                'id_pertemuan' => $modul->id_pertemuan,
                'id_user'      => $user->id,
                'kehadiran'    => 'Hadir',
                'status'       => 'Pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Presensi berhasil dicatat!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan presensi.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // ─── Endpoint: Mulai Pretest ──────────────────────────────────────────────

    public function startPretest(Request $request, $modulId)
    {
        $user  = Auth::user();
        $modul = Modul::findOrFail($modulId);

        $sudahAbsen = Presensi::where('id_pertemuan', $modul->id_pertemuan)
            ->where('id_user', $user->id)
            ->exists();

        if (!$sudahAbsen) {
            return response()->json([
                'success' => false,
                'message' => 'Harap lakukan presensi terlebih dahulu sebelum memulai pretest.'
            ], 422);
        }

        $sudahPretest = Nilai::where('id_pertemuan', $modul->id_pertemuan)
            ->where('id_user', $user->id)
            ->whereNotNull('nilai_pretest')
            ->exists();

        if ($sudahPretest) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah mengerjakan pretest ini.'
            ], 409);
        }

        try {
            Nilai::firstOrCreate(
                [
                    'id_pertemuan' => $modul->id_pertemuan,
                    'id_user'      => $user->id,
                ],
                [
                    'nilai_pretest' => 0,
                    'status'        => 'Pending',
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Pretest dimulai! Selamat mengerjakan.',
                'redirect' => route('pretest.questions', ['pertemuanId' => $modul->id_pertemuan])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memulai pretest.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // ─── Endpoint: Upload Laporan ─────────────────────────────────────────────

    public function uploadLaporan(Request $request, $modulId)
    {
        $user  = Auth::user();
        $modul = Modul::findOrFail($modulId);

        $request->validate([
            'file'       => 'required|file|mimes:pdf,doc,docx,zip|max:10240',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $sudahUpload = PengumpulanLaporan::where('id_pertemuan', $modul->id_pertemuan)
            ->where('id_user', $user->id)
            ->exists();

        if ($sudahUpload) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan untuk pertemuan ini sudah pernah diupload.'
            ], 409);
        }

        try {
            $path = $request->file('file')->store(
                'laporan/' . $user->id . '/' . $modul->id_pertemuan,
                'public'
            );

            PengumpulanLaporan::create([
                'id_pertemuan' => $modul->id_pertemuan,
                'id_user'      => $user->id,
                'file_path'    => $path,
                'nama_file'    => $request->file('file')->getClientOriginalName(),
                'keterangan'   => $request->input('keterangan'),
                'status'       => 'Pending',
            ]);

            return response()->json([
                'success'   => true,
                'message'   => 'Laporan berhasil diupload!',
                'file_name' => $request->file('file')->getClientOriginalName(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload laporan.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function getMyHistory()
    {
        $user = Auth::user();

        $nilais = Nilai::where('id_user', $user->id)
            ->with('pertemuan.praktikum')
            ->get();

        // Get user's presensi history
        $presensis = Presensi::where('id_user', $user->id)
            ->with('pertemuan.praktikum')
            ->get();

        return view('Mahasiswa.riwayat', compact('nilais', 'presensis'));
    }

    /**
     * Tampilkan daftar modul untuk mahasiswa (dengan praktikum yang didaftar)
     */
    public function getMyModul()
    {
        $user = Auth::user();

        // Ambil modul dari praktikum yang sudah didaftar user
        $moduls = Modul::with(['pertemuan.praktikum'])
            ->whereHas('pertemuan.praktikum.jadwals.pendaftarans', function ($query) use ($user) {
                $query->where('id_user', $user->id);
            })
            ->get();

        $matkulList = $moduls
            ->pluck('pertemuan.praktikum.nama_praktikum')
            ->unique()->filter()->values();

        return view('Mahasiswa.modul', compact('moduls', 'matkulList'));
    }

    /**
     * Tampilkan detail modul untuk satu pertemuan
     */
    public function showModul($id_pertemuan)
    {
        $user = Auth::user();
        $pertemuan = Pertemuan::with(['modul', 'praktikum'])->findOrFail($id_pertemuan);
        $modul = $pertemuan->modul;

        if (!$modul) {
            return redirect()->back()->with('error', 'Modul tidak ditemukan untuk pertemuan ini.');
        }

        // Ambil flashcard untuk modul ini (untuk tombol generate)
        $flashcards = Flashcard::where('id_modul', $modul->id)
            ->with(['progress' => function ($query) use ($user) {
                $query->where('id_user', $user->id);
            }])
            ->get();

        return view('Mahasiswa.modul-detail', compact('pertemuan', 'modul', 'flashcards'));
    }

    /**
     * Tampilkan daftar flashcard untuk mahasiswa
     */
    public function getMyFlashcard()
    {
        $user = Auth::user();

        // Ambil flashcard dari modul yang ada di praktikum yang didaftar
        $flashcards = Flashcard::with(['modul.pertemuan.praktikum', 'progress' => function ($query) use ($user) {
            $query->where('id_user', $user->id);
        }])
            ->whereHas('modul.pertemuan.praktikum.jadwals.pendaftarans', function ($query) use ($user) {
                $query->where('id_user', $user->id);
            })
            ->get();

        // Kelompokkan berdasarkan pertemuan
        $groupedFlashcards = $flashcards->groupBy(fn($card) => $card->modul?->id_pertemuan);

        return view('Mahasiswa.flashcard', compact('groupedFlashcards', 'flashcards'));
    }

    /**
     * Tampilkan flashcard untuk satu pertemuan dengan spaced repetition
     */
    public function showFlashcardByPertemuan($id_pertemuan)
    {
        $user = Auth::user();
        $pertemuan = Pertemuan::with(['modul'])->findOrFail($id_pertemuan);
        $modul = $pertemuan->modul;

        if (!$modul) {
            return redirect()->back()->with('error', 'Modul tidak ditemukan untuk pertemuan ini.');
        }

        // Ambil flashcard dengan filter spaced repetition
        $allFlashcards = Flashcard::where('id_modul', $modul->id)
            ->with(['progress' => function ($query) use ($user) {
                $query->where('id_user', $user->id);
            }])
            ->get();

        // Filter untuk flashcard yang perlu di-review (next_review_date <= hari ini)
        $flashcardsToReview = $allFlashcards->filter(function ($card) use ($user) {
            $progress = $card->progress->first();
            
            // Jika belum ada progress, tampilkan (first time)
            if (!$progress) {
                return true;
            }

            // Jika sudah ada progress, check apakah sudah waktunya direview
            return $progress->next_review_date <= now()->toDateString();
        })->values();

        $flashcardsJson = $flashcardsToReview->map(fn($card) => [
        'id'    => $card->id,
        'front' => $card->front,
        'back'  => $card->back,
    ])->values();

        return view('Mahasiswa.flashcard-detail', compact('pertemuan', 'modul', 'flashcardsToReview', 'allFlashcards', 'flashcardsJson'));
    }

    function getMahasiswa(Request $request)
    {
        $user = Auth::user();

        // Get jadwal IDs where user is Asisten
        $jadwalIds = DB::table('pendaftaran_praktikum')
            ->where('id_user', $user->id)
            ->where('role', 'Asisten')
            ->pluck('id_jadwal')
            ->toArray();

        // Get praktikum IDs from those jadwals
        $praktikumIds = DB::table('jadwals')
            ->whereIn('id', $jadwalIds)
            ->pluck('id_praktikum')
            ->unique()
            ->toArray();

        // Get all praktikum names for filter
        $praktikums = Praktikum::whereIn('id', $praktikumIds)->get();

        // Get all students (Praktikan) registered in these praktikums
        $query = User::where('role', 'Praktikan')
            ->whereHas('pendaftaranPraktikums', function ($q) use ($praktikumIds, $jadwalIds) {
                $q->whereIn('id_jadwal', $jadwalIds)
                    ->where('role', 'Praktikan');
            })
            ->with(['pendaftaranPraktikums' => function ($q) use ($jadwalIds) {
                $q->whereIn('id_jadwal', $jadwalIds)
                    ->with(['jadwal.praktikum']);
            }]);

        // Apply filters
        if ($request->has('matkul') && $request->matkul) {
            $query->whereHas('pendaftaranPraktikums.jadwal.praktikum', function ($q) use ($request) {
                $q->where('nama_praktikum', $request->matkul);
            });
        }

        if ($request->has('praktikum') && $request->praktikum) {
            $query->whereHas('pendaftaranPraktikums.jadwal.praktikum', function ($q) use ($request) {
                $q->where('nama_praktikum', $request->praktikum);
            });
        }

        if ($request->has('kelas') && $request->kelas) {
            // Filter by class/angkatan
            $query->whereHas('pendaftaranPraktikums.jadwal.praktikum', function ($q) use ($request) {
                $q->where('angkatan', $request->kelas);
            });
        }

        if ($request->has('pertemuan') && $request->pertemuan) {
            $query->whereHas('pendaftaranPraktikums.jadwal.pertemuan', function ($q) use ($request) {
                $q->where('pertemuan_ke', $request->pertemuan);
            });
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nomor_induk', 'like', "%{$search}%");
            });
        }

        $mahasiswas = $query->orderBy('nama', 'asc')->paginate(15);

        // Get unique values for filters
        $praktikumNames = $praktikums->pluck('nama_praktikum')->unique()->values();
        $kelasList = $praktikums->pluck('angkatan')->unique()->sort()->values();
        $pertemuanList = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14];

        // For each student, get their praktikum and pertemuan info
        foreach ($mahasiswas as $mahasiswa) {
            $firstRegistration = $mahasiswa->pendaftaranPraktikums->first();
            if ($firstRegistration && $firstRegistration->jadwal) {
                $mahasiswa->praktikum_name = $firstRegistration->jadwal->praktikum->nama_praktikum ?? 'N/A';
                $mahasiswa->angkatan = $firstRegistration->jadwal->praktikum->angkatan ?? 'N/A';

                // Get latest pertemuan
                $latestPertemuan = $firstRegistration->jadwal->pertemuan->sortByDesc('pertemuan_ke')->first();
                $mahasiswa->pertemuan_ke = $latestPertemuan->pertemuan_ke ?? '-';
                $mahasiswa->tanggal_praktikum = $latestPertemuan->created_at ? $latestPertemuan->created_at->format('d F Y') : '-';
            } else {
                $mahasiswa->praktikum_name = 'N/A';
                $mahasiswa->angkatan = 'N/A';
                $mahasiswa->pertemuan_ke = '-';
                $mahasiswa->tanggal_praktikum = '-';
            }
        }

        return view('asisten/mahasiswa_asisten', compact('mahasiswas', 'praktikumNames', 'kelasList', 'pertemuanList'));
    }

    function dashboard()
    {
        $user = Auth::user();

        // Get user's nilai with pertemuan (per pertemuan)
        $nilais = Nilai::where('id_user', $user->id)
            ->with('pertemuan.jadwal.praktikum')
            ->get();

        // Get user's presensi with pertemuan
        $presensis = Presensi::where('id_user', $user->id)
            ->with('pertemuan.jadwal.praktikum')
            ->get();

        // Get user's registered praktikums
        $praktikumCount = Praktikum::whereHas('jadwals.pertemuan.nilais', function ($query) use ($user) {
            $query->where('id_user', $user->id);
        })->count();

        // Calculate average nilai
        $avgNilai = $nilais->avg('nilai_akhir') ?? 0;

        // Calculate attendance percentage
        $hadirCount = $presensis->where('kehadiran', 'Hadir')->count();
        $totalPresensi = $presensis->count();
        $attendanceRate = $totalPresensi > 0 ? round(($hadirCount / $totalPresensi) * 100) : 0;

        // Get upcoming reminders (pertemuan yang akan datang)
        $reminders = [];
        foreach ($nilais as $nilai) {
            if ($nilai->pertemuan && $nilai->pertemuan->jadwal) {
                $reminders[] = [
                    'praktikum' => $nilai->pertemuan->jadwal->praktikum->nama_praktikum ?? 'Praktikum',
                    'pertemuan' => $nilai->pertemuan->nama_pertemuan,
                    'modul' => $nilai->pertemuan->modul->judul_modul ?? '-',
                    'nilai' => $nilai->nilai_akhir,
                    'status' => $nilai->status,
                ];
            }
        }

        // Get all nilai per pertemuan for display
        $nilaiPerPertemuan = $nilais->map(function ($nilai) {
            return [
                'praktikum' => $nilai->pertemuan?->jadwal?->praktikum?->nama_praktikum ?? '-',
                'pertemuan' => $nilai->pertemuan?->nama_pertemuan ?? '-',
                'modul' => $nilai->pertemuan?->modul?->judul_modul ?? '-',
                'nilai_pretest' => $nilai->nilai_pretest,
                'nilai_laporan' => $nilai->nilai_laporan,
                'nilai_total' => $nilai->nilai_total,
                'nilai_akhir' => $nilai->nilai_akhir,
                'status' => $nilai->status,
            ];
        });

        return view('Mahasiswa.dashboard', compact(
            'user',
            'praktikumCount',
            'nilais',
            'presensis',
            'avgNilai',
            'attendanceRate',
            'reminders',
            'nilaiPerPertemuan'
        ));

        // Reminder 2: Pertemuan dengan pretest besok
        $pretestBesok = Pertemuan::whereHas('modul')
            ->whereHas('jadwal', function ($q) use ($hariBesok) {
                $q->where('hari', $hariBesok);
            })
            ->whereHas('jadwal.pendaftarans', function ($q) use ($user) {
                $q->where('id_user', $user->id);
            })
            ->with('praktikum', 'jadwal')
            ->get();

        foreach ($pretestBesok as $pertemuan) {
            $jadwal = $pertemuan->jadwal;
            $reminders[] = [
                'praktikum' => $pertemuan->praktikum->nama_praktikum ?? '-',
                'pertemuan' => $pertemuan->nama_pertemuan ?? '-',
                'modul'     => ($jadwal?->jam_mulai ?? '-') . ' WIB',
                'nilai'     => '-',
                'status'    => 'Pretest Besok',
            ];
        }

        // ── Nilai per pertemuan ───────────────────────────────────
        $nilaiPerPertemuan = $nilais->map(function ($nilai) {
            return [
                'praktikum'     => $nilai->pertemuan?->praktikum?->nama_praktikum ?? '-',
                'pertemuan'     => $nilai->pertemuan?->nama_pertemuan ?? '-',
                'modul'         => $nilai->pertemuan?->modul?->judul_modul ?? '-',
                'nilai_pretest' => $nilai->nilai_pretest,
                'nilai_laporan' => $nilai->nilai_laporan,
                'nilai_total'   => $nilai->nilai_total,
                'nilai_akhir'   => $nilai->nilai_akhir,
                'status'        => $nilai->status,
            ];
        });

        return view('Mahasiswa.dashboard', compact(
            'user',
            'praktikumCount',
            'nilais',
            'presensis',
            'avgNilai',
            'attendanceRate',
            'reminders',
            'nilaiPerPertemuan',
            'jadwalHariIni'
        ));
    }

    // ─── Endpoint: Tampilkan Soal Pretest ─────────────────────────────────────

    public function showPretestQuestions(Request $request, $pertemuanId)
    {
        $user = Auth::user();

        // Cek apakah user sudah presensi
        $sudahAbsen = Presensi::where('id_pertemuan', $pertemuanId)
            ->where('id_user', $user->id)
            ->exists();

        if (!$sudahAbsen) {
            return redirect()->back()->with('error', 'Harap lakukan presensi terlebih dahulu.');
        }

        // Cek apakah sudah pernah mengerjakan pretest ini
        $sudahDikerjakan = Nilai::where('id_pertemuan', $pertemuanId)
            ->where('id_user', $user->id)
            ->whereNotNull('nilai_pretest')
            ->exists();

        if ($sudahDikerjakan) {
            return redirect()->back()->with('warning', 'Anda sudah mengerjakan pretest ini sebelumnya.');
        }

        // Ambil pretest dari pertemuan ini
        $pretest = Pretest::where('id_pertemuan', $pertemuanId)
            ->with('questions')
            ->first();

        if (!$pretest) {
            return redirect()->back()->with('error', 'Pretest untuk pertemuan ini belum tersedia.');
        }

        // Ambil data pertemuan dan modul
        $pertemuan = Pertemuan::with('praktikum', 'modul')->findOrFail($pertemuanId);
        $modul = $pertemuan->modul;

        return view('Mahasiswa.takePretestStudent', [
            'pretest' => $pretest,
            'pertemuan' => $pertemuan,
            'modul' => $modul,
            'totalQuestions' => $pretest->questions->count()
        ]);
    }

    // ─── Endpoint: Submit Jawaban Pretest ─────────────────────────────────────

    public function submitPretestAnswers(Request $request, $pertemuanId)
    {
        $user = Auth::user();

        // Validasi jawaban
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|in:A,B,C,D',
        ]);

        // Ambil pretest dari pertemuan ini
        $pretest = Pretest::where('id_pertemuan', $pertemuanId)
            ->with('questions')
            ->first();

        if (!$pretest) {
            return response()->json([
                'success' => false,
                'message' => 'Pretest tidak ditemukan.'
            ], 404);
        }

        // Hitung skor dan simpan jawaban siswa
        $answers = $request->input('answers');
        $correctCount = 0;
        $totalQuestions = $pretest->questions->count();

        foreach ($pretest->questions as $question) {
            $userAnswer = $answers[$question->id] ?? null;
            $isCorrect = $userAnswer && strtoupper($userAnswer) === strtoupper($question->correct_option);
            
            if ($isCorrect) {
                $correctCount++;
            }

            // Simpan jawaban siswa untuk tracking
            StudentAnswer::updateOrCreate(
                [
                    'id_user' => $user->id,
                    'id_pretest' => $pretest->id,
                    'id_question' => $question->id,
                ],
                [
                    'user_answer' => strtoupper($userAnswer),
                    'is_correct' => $isCorrect,
                ]
            );
        }

        // Hitung nilai (skalanya 100)
        $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100) : 0;

        // Simpan atau update nilai pretest
        try {
            $nilai = Nilai::firstOrCreate(
                [
                    'id_pertemuan' => $pertemuanId,
                    'id_user' => $user->id,
                ],
                [
                    'nilai_pretest' => $score,
                    'status' => 'Pending',
                ]
            );

            // Jika sudah ada, update nilainya (jangan timpa jika sudah ada nilai)
            if ($nilai->nilai_pretest === 0 || $nilai->nilai_pretest === null) {
                $nilai->update(['nilai_pretest' => $score]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Pretest berhasil disubmit!',
                'score' => $score,
                'correct' => $correctCount,
                'total' => $totalQuestions,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan jawaban pretest.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
