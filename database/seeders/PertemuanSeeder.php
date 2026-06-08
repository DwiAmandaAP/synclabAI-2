<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use App\Models\Pertemuan;
use Illuminate\Database\Seeder;

class PertemuanSeeder extends Seeder
{
    public function run(): void
    {
        $jadwals = Jadwal::with('praktikum')->get();

        if ($jadwals->isEmpty()) {
            return;
        }

        foreach ($jadwals as $jadwal) {
            for ($ke = 1; $ke <= 3; $ke++) {
                Pertemuan::create([
                    'id_praktikum' => $jadwal->id_praktikum,
                    'id_jadwal' => $jadwal->id,
                    'nama_pertemuan' => "Pertemuan {$ke}: " . $this->getName($jadwal->praktikum->kode_praktikum, $ke),
                    'pertemuan_ke' => $ke,
                    'deskripsi_pertemuan' => $this->getDesc($jadwal->praktikum->kode_praktikum, $ke),
                ]);
            }
        }
    }

    private function getName($kode, $ke)
    {
        $data = $this->getData();

        return $data[$kode][$ke]['nama'] ?? "Pertemuan {$ke}";
    }

    private function getDesc($kode, $ke)
    {
        $data = $this->getData();

        return $data[$kode][$ke]['desc'] ?? "Deskripsi pertemuan {$ke}";
    }

    private function getData(): array
    {
        return [
            'PD2401' => [
                1 => ['nama' => 'Instalasi & Sintaks Dasar', 'desc' => 'Setup environment dan dasar sintaks C++'],
                2 => ['nama' => 'Percabangan & Perulangan', 'desc' => 'If, switch, loop (for, while)'],
                3 => ['nama' => 'Fungsi', 'desc' => 'Pembuatan fungsi dan modularisasi'],
            ],

            'SD2402' => [
                1 => ['nama' => 'Array & Linked List', 'desc' => 'Struktur data linear'],
                2 => ['nama' => 'Stack & Queue', 'desc' => 'Implementasi LIFO dan FIFO'],
                3 => ['nama' => 'Tree', 'desc' => 'Binary tree dan traversal'],
            ],

            'BD2403' => [
                1 => ['nama' => 'Pengenalan DB', 'desc' => 'Konsep dasar basis data'],
                2 => ['nama' => 'ERD', 'desc' => 'Perancangan ERD'],
                3 => ['nama' => 'Relasi', 'desc' => 'Relasi antar tabel'],
            ],
        ];
    }
}