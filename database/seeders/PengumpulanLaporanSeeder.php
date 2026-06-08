<?php

namespace Database\Seeders;

use App\Models\PengumpulanLaporan;
use App\Models\Laporan;
use App\Models\Pertemuan;
use App\Models\User;
use Illuminate\Database\Seeder;

class PengumpulanLaporanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $laporans = Laporan::all();
        // Hanya pertemuan 1-2 karena user sedang di pertemuan 3
        $pertemuans = Pertemuan::where('pertemuan_ke', '<=', 2)->get();
        $praktikans = User::where('role', 'Praktikan')->get();

        if ($laporans->isEmpty() || $praktikans->isEmpty() || $pertemuans->isEmpty()) {
            return;
        }

        $status_options = ['Disubmit', 'Dalam Review', 'Diterima', 'Ditolak', 'Terlambat'];

        // Untuk setiap praktikan dan laporan yang ada di pertemuan, buat pengumpulan
        foreach ($praktikans as $praktikan) {
            foreach ($pertemuans as $pertemuan) {
                // Cari laporan untuk pertemuan ini
                $laporan = $laporans->firstWhere('id_pertemuan', $pertemuan->id);

                if ($laporan) {
                    // Probability: 85% submit, 15% tidak submit
                    if (rand(0, 100) <= 85) {
                        // Probability status
                        $rand = rand(0, 100);
                        if ($rand <= 60) {
                            $status = 'Diterima';
                            $nilai = rand(70, 95);
                        } elseif ($rand <= 75) {
                            $status = 'Dalam Review';
                            $nilai = null;
                        } elseif ($rand <= 85) {
                            $status = 'Ditolak';
                            $nilai = null;
                        } else {
                            $status = 'Terlambat';
                            $nilai = rand(40, 70);
                        }

                        // Update or create the pengumpulan laporan
                        PengumpulanLaporan::updateOrCreate(
                            [
                                'id_pertemuan' => $pertemuan->id,
                                'id_user' => $praktikan->id,
                                'id_laporan' => $laporan->id,
                            ],
                            [
                                'file_path' => 'uploads/laporan/' . $praktikan->nomor_induk . '_' . $laporan->id . '.pdf',
                                'keterangan' => $this->generateKeterangan(),
                                'status' => $status,
                                'nilai' => $nilai,
                                'komentar' => $status === 'Diterima' ? $this->generateKomentar() : null,
                            ]
                        );
                    }
                }
            }
        }
    }

    private function generateKeterangan()
    {
        $keterangan = [
            'Laporan dikerjakan sesuai dengan instruksi dan modul.',
            'Dikumpulkan tepat waktu dengan hasil yang memuaskan.',
            'Laporan sudah lengkap dengan semua hasil percobaan.',
            'Dikerjakan dengan baik dan analisis yang mendalam.',
            'Hasil sesuai dengan yang diharapkan.',
            'Laporan cukup lengkap namun ada beberapa kekurangan.',
            'Dikerjakan dengan usaha maksimal.',
        ];

        return $keterangan[array_rand($keterangan)];
    }

    private function generateKomentar()
    {
        $komentars = [
            'Bagus, laporan sudah sesuai standar.',
            'Analisis cukup mendalam dan jelas.',
            'Kesimpulan sudah tepat dan logis.',
            'Hasil praktikum sesuai dengan teori.',
            'Penulisan laporan sudah rapi dan terstruktur.',
            'Dokumentasi lengkap dan informatif.',
        ];

        return $komentars[array_rand($komentars)];
    }
}
