<?php

namespace Database\Seeders;

use App\Models\Nilai;
use App\Models\Pertemuan;
use App\Models\User;
use Illuminate\Database\Seeder;

class NilaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get pertemuans dan praktikans (hanya pertemuan 1-2 karena user sedang di pertemuan 3)
        $pertemuans = Pertemuan::where('pertemuan_ke', '<=', 2)->get();
        $praktikans = User::where('role', 'Praktikan')->get();

        if ($praktikans->isEmpty() || $pertemuans->isEmpty()) {
            return;
        }

        $nilais = [];

        // Untuk setiap praktikan, buat nilai untuk setiap pertemuan
        foreach ($praktikans as $praktikan) {
            foreach ($pertemuans as $pertemuan) {
                // Hindari duplicate
                $exists = Nilai::where('id_pertemuan', $pertemuan->id)
                    ->where('id_user', $praktikan->id)
                    ->exists();

                if (!$exists) {
                    // Generate nilai yang realistis (65-95)
                    $nilai_pretest = null;
                    $nilai_laporan = rand(65, 95);
                    $nilai_total = intval(($nilai_laporan) / 2);
                    
                    // Nilai akhir dengan sedikit variasi
                    $nilai_akhir = intval($nilai_total * 0.9 + rand(0, 10));

                    $nilais[] = [
                        'id_pertemuan' => $pertemuan->id,
                        'id_user' => $praktikan->id,
                        'nilai_pretest' => $nilai_pretest,
                        'nilai_laporan' => $nilai_laporan,
                        'nilai_total' => $nilai_total,
                        'nilai_akhir' => $nilai_akhir,
                        'komentar' => $this->generateKomentar($nilai_akhir),
                        'status' => rand(0, 1) ? 'Terkonfirmasi' : 'Pending',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // Update or create each nilai record to ensure idempotency
        if (!empty($nilais)) {
            foreach ($nilais as $nilai) {
                Nilai::updateOrCreate(
                    [
                        'id_pertemuan' => $nilai['id_pertemuan'],
                        'id_user' => $nilai['id_user'],
                    ],
                    [
                        'nilai_pretest' => $nilai['nilai_pretest'],
                        'nilai_laporan' => $nilai['nilai_laporan'],
                        'nilai_total' => $nilai['nilai_total'],
                        'nilai_akhir' => $nilai['nilai_akhir'],
                        'komentar' => $nilai['komentar'],
                        'status' => $nilai['status'],
                    ]
                );
            }
        }
    }

    private function generateKomentar($nilai)
    {
        $komentars = [
            'Bagus! Terus semangat.',
            'Perlu peningkatan lagi.',
            'Sangat baik!',
            'Baik, pertahankan.',
            'Hasil yang memuaskan.',
            'Bisa lebih baik lagi.',
            'Excellent performance!',
            'Kerja keras, hasil maksimal.',
            'Ada perbaikan, lanjutkan.',
            'Luar biasa!',
        ];

        return $komentars[array_rand($komentars)];
    }
}