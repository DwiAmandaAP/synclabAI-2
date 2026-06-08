<?php

namespace Database\Seeders;

use App\Models\Laporan;
use App\Models\Pertemuan;
use Illuminate\Database\Seeder;

class LaporanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pertemuans = Pertemuan::all();

        if ($pertemuans->isEmpty()) {
            return;
        }

        $pertemuans = Pertemuan::with('praktikum')->get(); // Eager load relasi

        $getPertemuan = function($kodePraktikum, $pertemuanKe) use ($pertemuans) {
            return $pertemuans->first(function($item) use ($kodePraktikum, $pertemuanKe) {
                // Akses kode_praktikum melalui relasi model Praktikum
                return $item->praktikum->kode_praktikum === $kodePraktikum && 
                    $item->pertemuan_ke === $pertemuanKe;
            });
        };

        $laporans = [
            // Pemrograman Dasar
            [
                'id_pertemuan' => $getPertemuan('PD2401', 1)?->id,
                'judul_laporan' => 'Laporan Praktikum Pertemuan 1: Instalasi & Sintaks Dasar',
                'open_date' => date('Y-m-d', strtotime('+1 day')),
                'close_date' => date('Y-m-d', strtotime('+8 days')),
                'open_time' => '08:00:00',
                'close_time' => '17:00:00',
                'deskripsi' => 'Laporan hasil praktikum tentang instalasi compiler C++ dan pembuatan program dasar dengan sintaks.',
            ],
            [
                'id_pertemuan' => $getPertemuan('PD2401', 2)?->id,
                'judul_laporan' => 'Laporan Praktikum Pertemuan 2: Percabangan & Perulangan',
                'open_date' => date('Y-m-d', strtotime('+8 days')),
                'close_date' => date('Y-m-d', strtotime('+15 days')),
                'open_time' => '08:00:00',
                'close_time' => '17:00:00',
                'deskripsi' => 'Laporan hasil praktikum tentang implementasi if-else, switch-case, dan berbagai jenis loop.',
            ],
            [
                'id_pertemuan' => $getPertemuan('PD2401', 3)?->id,
                'judul_laporan' => 'Laporan Praktikum Pertemuan 3: Fungsi & Modularitas',
                'open_date' => date('Y-m-d', strtotime('+15 days')),
                'close_date' => date('Y-m-d', strtotime('+22 days')),
                'open_time' => '08:00:00',
                'close_time' => '17:00:00',
                'deskripsi' => 'Laporan hasil praktikum tentang pembuatan dan penggunaan fungsi untuk program modular.',
            ],

            // Struktur Data
            [
                'id_pertemuan' => $getPertemuan('SD2402', 1)?->id,
                'judul_laporan' => 'Laporan Praktikum Pertemuan 1: Array & Linked List',
                'open_date' => date('Y-m-d', strtotime('+1 day')),
                'close_date' => date('Y-m-d', strtotime('+8 days')),
                'open_time' => '08:00:00',
                'close_time' => '17:00:00',
                'deskripsi' => 'Laporan hasil praktikum tentang implementasi single linked list dengan berbagai operasi.',
            ],
            [
                'id_pertemuan' => $getPertemuan('SD2402', 2)?->id,
                'judul_laporan' => 'Laporan Praktikum Pertemuan 2: Stack & Queue',
                'open_date' => date('Y-m-d', strtotime('+8 days')),
                'close_date' => date('Y-m-d', strtotime('+15 days')),
                'open_time' => '08:00:00',
                'close_time' => '17:00:00',
                'deskripsi' => 'Laporan hasil praktikum tentang implementasi stack (LIFO) dan queue (FIFO).',
            ],
            [
                'id_pertemuan' => $getPertemuan('SD2402', 3)?->id,
                'judul_laporan' => 'Laporan Praktikum Pertemuan 3: Tree & Binary Search Tree',
                'open_date' => date('Y-m-d', strtotime('+15 days')),
                'close_date' => date('Y-m-d', strtotime('+22 days')),
                'open_time' => '08:00:00',
                'close_time' => '17:00:00',
                'deskripsi' => 'Laporan hasil praktikum tentang implementasi binary search tree dan tree traversal.',
            ],

            // Basis Data
            [
                'id_pertemuan' => $getPertemuan('BD2403', 1)?->id,
                'judul_laporan' => 'Laporan Praktikum Pertemuan 1: SQL Basic & Query',
                'open_date' => date('Y-m-d', strtotime('+1 day')),
                'close_date' => date('Y-m-d', strtotime('+8 days')),
                'open_time' => '08:00:00',
                'close_time' => '17:00:00',
                'deskripsi' => 'Laporan hasil praktikum tentang penggunaan SQL SELECT, WHERE, ORDER BY, dan GROUP BY.',
            ],
            [
                'id_pertemuan' => $getPertemuan('BD2403', 2)?->id,
                'judul_laporan' => 'Laporan Praktikum Pertemuan 2: Join & Subquery',
                'open_date' => date('Y-m-d', strtotime('+8 days')),
                'close_date' => date('Y-m-d', strtotime('+15 days')),
                'open_time' => '08:00:00',
                'close_time' => '17:00:00',
                'deskripsi' => 'Laporan hasil praktikum tentang penggunaan berbagai jenis JOIN dan subquery.',
            ],
            [
                'id_pertemuan' => $getPertemuan('BD2403', 3)?->id,
                'judul_laporan' => 'Laporan Praktikum Pertemuan 3: DML & DDL',
                'open_date' => date('Y-m-d', strtotime('+15 days')),
                'close_date' => date('Y-m-d', strtotime('+22 days')),
                'open_time' => '08:00:00',
                'close_time' => '17:00:00',
                'deskripsi' => 'Laporan hasil praktikum tentang INSERT, UPDATE, DELETE, dan manipulasi struktur tabel.',
            ],

            // Jaringan Komputer
            [
                'id_pertemuan' => $getPertemuan('JARKOM2404', 1)?->id,
                'judul_laporan' => 'Laporan Praktikum Pertemuan 1: Konfigurasi Cisco Packet Tracer',
                'open_date' => date('Y-m-d', strtotime('+1 day')),
                'close_date' => date('Y-m-d', strtotime('+8 days')),
                'open_time' => '08:00:00',
                'close_time' => '17:00:00',
                'deskripsi' => 'Laporan hasil praktikum tentang penggunaan Cisco Packet Tracer dan pembuatan topologi.',
            ],
            [
                'id_pertemuan' => $getPertemuan('JARKOM2404', 2)?->id,
                'judul_laporan' => 'Laporan Praktikum Pertemuan 2: Konfigurasi IP Address',
                'open_date' => date('Y-m-d', strtotime('+8 days')),
                'close_date' => date('Y-m-d', strtotime('+15 days')),
                'open_time' => '08:00:00',
                'close_time' => '17:00:00',
                'deskripsi' => 'Laporan hasil praktikum tentang konfigurasi IP address dan subnet mask.',
            ],
            [
                'id_pertemuan' => $getPertemuan('JARKOM2404', 3)?->id,
                'judul_laporan' => 'Laporan Praktikum Pertemuan 3: Routing & OSPF',
                'open_date' => date('Y-m-d', strtotime('+15 days')),
                'close_date' => date('Y-m-d', strtotime('+22 days')),
                'open_time' => '08:00:00',
                'close_time' => '17:00:00',
                'deskripsi' => 'Laporan hasil praktikum tentang konfigurasi routing dinamis menggunakan OSPF.',
            ],
        ];

        foreach ($laporans as $laporan) {
            if ($laporan['id_pertemuan']) {
                Laporan::updateOrCreate(
                    [
                        'id_pertemuan' => $laporan['id_pertemuan'],
                        'judul_laporan' => $laporan['judul_laporan'],
                    ],
                    [
                        'open_date' => $laporan['open_date'],
                        'close_date' => $laporan['close_date'],
                        'open_time' => $laporan['open_time'],
                        'close_time' => $laporan['close_time'],
                        'deskripsi' => $laporan['deskripsi'],
                    ]
                );
            }
        }
    }
}
