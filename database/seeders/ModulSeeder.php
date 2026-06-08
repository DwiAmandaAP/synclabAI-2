<?php

namespace Database\Seeders;

use App\Models\Modul;
use App\Models\Pertemuan;
use Illuminate\Database\Seeder;

class ModulSeeder extends Seeder
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

        // Helper untuk mencari pertemuan
        $pertemuans = Pertemuan::with('praktikum')->get(); // Eager load relasi

        $getPertemuan = function($kodePraktikum, $pertemuanKe) use ($pertemuans) {
            return $pertemuans->first(function($item) use ($kodePraktikum, $pertemuanKe) {
                // Akses kode_praktikum melalui relasi model Praktikum
                return $item->praktikum->kode_praktikum === $kodePraktikum && 
                    $item->pertemuan_ke === $pertemuanKe;
            });
        };

        $moduls = [
            // === Pemrograman Dasar (PD2401) ===
            // Pertemuan 1
            [
                'id_pertemuan' => $getPertemuan('PD2401', 1)?->id,
                'judul_modul' => 'Modul 1.1: Algoritma & Flowchart',
                'filepath' => 'modul/pd2401_pertemuan1_modul1.pdf',
                'deskripsi' => 'Pengenalan algoritma, flowchart, dan pseudocode. Pengertian dasar pemrograman dan langkah-langkah penyelesaian masalah.',
            ],
            [
                'id_pertemuan' => $getPertemuan('PD2401', 1)?->id,
                'judul_modul' => 'Modul 1.2: Instalasi Compiler C++',
                'filepath' => 'modul/pd2401_pertemuan1_modul2.pdf',
                'deskripsi' => 'Panduan instalasi compiler C++ (Dev-C++, CodeBlocks, atau Visual Studio Code).',
            ],
            // Pertemuan 2
            [
                'id_pertemuan' => $getPertemuan('PD2401', 2)?->id,
                'judul_modul' => 'Modul 2.1: Variabel & Tipe Data',
                'filepath' => 'modul/pd2401_pertemuan2_modul1.pdf',
                'deskripsi' => 'Pengertian variabel, deklarasi variabel, tipe data (int, float, char, boolean), dan operasi dasar.',
            ],
            [
                'id_pertemuan' => $getPertemuan('PD2401', 2)?->id,
                'judul_modul' => 'Modul 2.2: Percabangan If-Else & Switch',
                'filepath' => 'modul/pd2401_pertemuan2_modul2.pdf',
                'deskripsi' => 'Struktur kontrol If-Else, nested If, dan Switch-Case untuk logika percabangan.',
            ],
            [
                'id_pertemuan' => $getPertemuan('PD2401', 2)?->id,
                'judul_modul' => 'Modul 2.3: Perulangan For, While, Do-While',
                'filepath' => 'modul/pd2401_pertemuan2_modul3.pdf',
                'deskripsi' => 'Loop for, while, dan do-while untuk pengulangan kode dan iterasi.',
            ],
            // Pertemuan 3
            [
                'id_pertemuan' => $getPertemuan('PD2401', 3)?->id,
                'judul_modul' => 'Modul 3.1: Fungsi & Deklarasi',
                'filepath' => 'modul/pd2401_pertemuan3_modul1.pdf',
                'deskripsi' => 'Definisi fungsi, parameter, return value, dan scope variabel.',
            ],
            [
                'id_pertemuan' => $getPertemuan('PD2401', 3)?->id,
                'judul_modul' => 'Modul 3.2: Rekursi & Modulitas',
                'filepath' => 'modul/pd2401_pertemuan3_modul2.pdf',
                'deskripsi' => 'Konsep rekursi, call stack, dan pembuatan program yang modular.',
            ],
            // Pertemuan 4
            [
                'id_pertemuan' => $getPertemuan('PD2401', 4)?->id,
                'judul_modul' => 'Modul 4.1: Array 1D & 2D',
                'filepath' => 'modul/pd2401_pertemuan4_modul1.pdf',
                'deskripsi' => 'Konsep array single dimensi dan multi dimensi, deklarasi, dan akses elemen.',
            ],
            [
                'id_pertemuan' => $getPertemuan('PD2401', 4)?->id,
                'judul_modul' => 'Modul 4.2: String & Manipulasi Karakter',
                'filepath' => 'modul/pd2401_pertemuan4_modul2.pdf',
                'deskripsi' => 'Tipe string, fungsi string built-in, dan manipulasi karakter dalam C++.',
            ],

            // === Struktur Data (SD2402) ===
            // Pertemuan 1
            [
                'id_pertemuan' => $getPertemuan('SD2402', 1)?->id,
                'judul_modul' => 'Modul 1.1: Konsep Dasar Struktur Data',
                'filepath' => 'modul/sd2402_pertemuan1_modul1.pdf',
                'deskripsi' => 'Pengenalan struktur data dan pentingnya dalam pemrograman.',
            ],
            [
                'id_pertemuan' => $getPertemuan('SD2402', 1)?->id,
                'judul_modul' => 'Modul 1.2: Array & Linked List',
                'filepath' => 'modul/sd2402_pertemuan1_modul2.pdf',
                'deskripsi' => 'Implementasi single linked list dengan operasi insert, delete, dan traverse.',
            ],
            // Pertemuan 2
            [
                'id_pertemuan' => $getPertemuan('SD2402', 2)?->id,
                'judul_modul' => 'Modul 2.1: Stack (LIFO)',
                'filepath' => 'modul/sd2402_pertemuan2_modul1.pdf',
                'deskripsi' => 'Konsep LIFO, operasi push dan pop, implementasi stack.',
            ],
            [
                'id_pertemuan' => $getPertemuan('SD2402', 2)?->id,
                'judul_modul' => 'Modul 2.2: Queue (FIFO)',
                'filepath' => 'modul/sd2402_pertemuan2_modul2.pdf',
                'deskripsi' => 'Konsep FIFO, operasi enqueue dan dequeue, implementasi queue.',
            ],
            // Pertemuan 3
            [
                'id_pertemuan' => $getPertemuan('SD2402', 3)?->id,
                'judul_modul' => 'Modul 3.1: Tree & Binary Tree',
                'filepath' => 'modul/sd2402_pertemuan3_modul1.pdf',
                'deskripsi' => 'Struktur tree, binary tree, dan tree traversal (in-order, pre-order, post-order).',
            ],
            [
                'id_pertemuan' => $getPertemuan('SD2402', 3)?->id,
                'judul_modul' => 'Modul 3.2: Binary Search Tree',
                'filepath' => 'modul/sd2402_pertemuan3_modul2.pdf',
                'deskripsi' => 'Implementasi BST, operasi insert dan search dengan kompleksitas O(log n).',
            ],
            // Pertemuan 4
            [
                'id_pertemuan' => $getPertemuan('SD2402', 4)?->id,
                'judul_modul' => 'Modul 4.1: Graph & Representasi',
                'filepath' => 'modul/sd2402_pertemuan4_modul1.pdf',
                'deskripsi' => 'Konsep graph, adjacency matrix, dan adjacency list.',
            ],
            [
                'id_pertemuan' => $getPertemuan('SD2402', 4)?->id,
                'judul_modul' => 'Modul 4.2: BFS & DFS',
                'filepath' => 'modul/sd2402_pertemuan4_modul2.pdf',
                'deskripsi' => 'Algoritma Breadth-First Search dan Depth-First Search untuk traversal graph.',
            ],

            // === Basis Data (BD2403) ===
            // Pertemuan 1
            [
                'id_pertemuan' => $getPertemuan('BD2403', 1)?->id,
                'judul_modul' => 'Modul 1.1: Pengenalan Basis Data',
                'filepath' => 'modul/bd2403_pertemuan1_modul1.pdf',
                'deskripsi' => 'Konsep basis data, DBMS, tabel, dan relasi dalam database.',
            ],
            [
                'id_pertemuan' => $getPertemuan('BD2403', 1)?->id,
                'judul_modul' => 'Modul 1.2: SQL SELECT Dasar',
                'filepath' => 'modul/bd2403_pertemuan1_modul2.pdf',
                'deskripsi' => 'Syntax SELECT, WHERE, ORDER BY, GROUP BY, dan LIMIT dalam SQL.',
            ],
            // Pertemuan 2
            [
                'id_pertemuan' => $getPertemuan('BD2403', 2)?->id,
                'judul_modul' => 'Modul 2.1: SQL JOIN',
                'filepath' => 'modul/bd2403_pertemuan2_modul1.pdf',
                'deskripsi' => 'INNER JOIN, LEFT JOIN, RIGHT JOIN, dan FULL OUTER JOIN untuk multiple table.',
            ],
            [
                'id_pertemuan' => $getPertemuan('BD2403', 2)?->id,
                'judul_modul' => 'Modul 2.2: Subquery',
                'filepath' => 'modul/bd2403_pertemuan2_modul2.pdf',
                'deskripsi' => 'Subquery dalam SELECT, WHERE, dan FROM clause.',
            ],
            // Pertemuan 3
            [
                'id_pertemuan' => $getPertemuan('BD2403', 3)?->id,
                'judul_modul' => 'Modul 3.1: DML (INSERT, UPDATE, DELETE)',
                'filepath' => 'modul/bd2403_pertemuan3_modul1.pdf',
                'deskripsi' => 'Data Manipulation Language: INSERT, UPDATE, DELETE dengan berbagai kondisi.',
            ],
            [
                'id_pertemuan' => $getPertemuan('BD2403', 3)?->id,
                'judul_modul' => 'Modul 3.2: DDL (CREATE, ALTER, DROP)',
                'filepath' => 'modul/bd2403_pertemuan3_modul2.pdf',
                'deskripsi' => 'Data Definition Language: CREATE TABLE, ALTER TABLE, DROP TABLE.',
            ],
            // Pertemuan 4
            [
                'id_pertemuan' => $getPertemuan('BD2403', 4)?->id,
                'judul_modul' => 'Modul 4.1: Index & Query Optimization',
                'filepath' => 'modul/bd2403_pertemuan4_modul1.pdf',
                'deskripsi' => 'Penggunaan index untuk mempercepat query dan optimasi performa database.',
            ],

            // === Jaringan Komputer (JARKOM2404) ===
            // Pertemuan 1
            [
                'id_pertemuan' => $getPertemuan('JARKOM2404', 1)?->id,
                'judul_modul' => 'Modul 1.1: Pengenalan Cisco Packet Tracer',
                'filepath' => 'modul/jarkom2404_pertemuan1_modul1.pdf',
                'deskripsi' => 'Interface Cisco Packet Tracer dan cara membuat topologi jaringan.',
            ],
            [
                'id_pertemuan' => $getPertemuan('JARKOM2404', 1)?->id,
                'judul_modul' => 'Modul 1.2: Topologi Jaringan Dasar',
                'filepath' => 'modul/jarkom2404_pertemuan1_modul2.pdf',
                'deskripsi' => 'Jenis topologi (star, ring, bus) dan konfigurasi dasar perangkat.',
            ],
            // Pertemuan 2
            [
                'id_pertemuan' => $getPertemuan('JARKOM2404', 2)?->id,
                'judul_modul' => 'Modul 2.1: IP Address & Subnetting',
                'filepath' => 'modul/jarkom2404_pertemuan2_modul1.pdf',
                'deskripsi' => 'IPv4 address, subnet mask, dan teknik subnetting untuk alokasi IP.',
            ],
            [
                'id_pertemuan' => $getPertemuan('JARKOM2404', 2)?->id,
                'judul_modul' => 'Modul 2.2: Gateway & Routing',
                'filepath' => 'modul/jarkom2404_pertemuan2_modul2.pdf',
                'deskripsi' => 'Konfigurasi default gateway dan static routing pada router.',
            ],
            // Pertemuan 3
            [
                'id_pertemuan' => $getPertemuan('JARKOM2404', 3)?->id,
                'judul_modul' => 'Modul 3.1: Routing Dinamis dengan OSPF',
                'filepath' => 'modul/jarkom2404_pertemuan3_modul1.pdf',
                'deskripsi' => 'Protokol OSPF untuk routing dinamis antar router.',
            ],
            // Pertemuan 4
            [
                'id_pertemuan' => $getPertemuan('JARKOM2404', 4)?->id,
                'judul_modul' => 'Modul 4.1: Access Control List (ACL)',
                'filepath' => 'modul/jarkom2404_pertemuan4_modul1.pdf',
                'deskripsi' => 'Konfigurasi ACL untuk kontrol akses dan keamanan jaringan.',
            ],
        ];

        foreach ($moduls as $modul) {
            if ($modul['id_pertemuan']) {
                Modul::create($modul);
            }
        }
    }
}
