<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use App\Models\Laboratorium;
use App\Models\Praktikum;
use App\Models\User;
use Illuminate\Database\Seeder;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        $praktikums = Praktikum::all();
        $dosens = User::where('role', 'Dosen')->get();
        $laboratoriums = Laboratorium::all();

        if ($praktikums->isEmpty() || $dosens->isEmpty() || $laboratoriums->isEmpty()) {
            return;
        }

        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $timeSlots = [
            ['08:00', '09:30'],
            ['09:30', '11:00'],
            ['11:00', '12:30'],
            ['13:00', '14:30'],
            ['14:30', '16:00'],
            ['16:00', '17:30'],
            ['17:30', '19:00'],
        ];

        foreach ($praktikums as $praktikum) {
            for ($i = 0; $i < 3; $i++) {
                Jadwal::create([
                    'id_praktikum' => $praktikum->id,
                    'id_dosen' => $dosens[$i % $dosens->count()]->id,
                    'id_laboratorium' => $laboratoriums[$i % $laboratoriums->count()]->id,
                    'hari' => $days[$i % count($days)],
                    'jam_mulai' => $timeSlots[$i % count($timeSlots)][0],
                    'jam_selesai' => $timeSlots[$i % count($timeSlots)][1],
                    'jumlah_max_peserta' => 20,
                    'status' => 'Dibuka',
                ]);
            }
        }
    }
}