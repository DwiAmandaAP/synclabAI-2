<?php

namespace Database\Seeders;

use App\Models\Presensi;
use App\Models\Pertemuan;
use App\Models\User;
use Illuminate\Database\Seeder;

class PresensiSeeder extends Seeder
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

        $presensis = [];

        $kehadiran_options = ['Hadir', 'Izin', 'Sakit', 'Alpha'];
        $status_options = ['Dikonfirmasi', 'Pending', 'Ditolak'];

        // Untuk setiap praktikan dan pertemuan, buat presensi
        foreach ($praktikans as $praktikan) {
            foreach ($pertemuans as $pertemuan) {
                // Hindari duplicate
                $exists = Presensi::where('id_pertemuan', $pertemuan->id)
                    ->where('id_user', $praktikan->id)
                    ->exists();

                if (!$exists) {
                    // Probabilitas kehadiran: 70% hadir, 15% izin, 10% sakit, 5% alpha
                    $random = rand(0, 100);
                    if ($random <= 70) {
                        $kehadiran = 'Hadir';
                    } elseif ($random <= 85) {
                        $kehadiran = 'Izin';
                    } elseif ($random <= 95) {
                        $kehadiran = 'Sakit';
                    } else {
                        $kehadiran = 'Alpha';
                    }

                    // Status confir lebih tinggi untuk hadir, pending/ditolak untuk lainnya
                    if ($kehadiran === 'Hadir') {
                        $status = rand(0, 100) <= 90 ? 'Dikonfirmasi' : 'Pending';
                    } else {
                        $status = rand(0, 100) <= 70 ? 'Dikonfirmasi' : 'Pending';
                    }

                    $presensis[] = [
                        'id_pertemuan' => $pertemuan->id,
                        'id_user' => $praktikan->id,
                        'kehadiran' => $kehadiran,
                        'status' => $status,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // Update or create each presensi record to ensure idempotency
        if (!empty($presensis)) {
            foreach ($presensis as $presensi) {
                Presensi::updateOrCreate(
                    [
                        'id_pertemuan' => $presensi['id_pertemuan'],
                        'id_user' => $presensi['id_user'],
                    ],
                    [
                        'kehadiran' => $presensi['kehadiran'],
                        'status' => $presensi['status'],
                    ]
                );
            }
        }
    }
}
