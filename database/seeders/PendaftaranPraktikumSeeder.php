<?php

namespace Database\Seeders;

use App\Models\PendaftaranPraktikum;
use App\Models\Jadwal;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PendaftaranPraktikumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $jadwals = Jadwal::all();
    $praktikans = User::where('role', 'Praktikan')->get();
    $asistens   = User::where('role', 'Asisten')->get();

    if ($jadwals->isEmpty() || $praktikans->isEmpty()) {
        $this->command->warn("Data Jadwal atau Praktikan kosong. Seeder dilewati.");
        return;
    }

    $pendaftarans = [];
    $now = Carbon::now();

    // Group jadwal by praktikum
    $jadwalsByPraktikum = $jadwals->groupBy('id_praktikum');

    foreach ($jadwalsByPraktikum as $idPraktikum => $jadwalGroup) {
        $jadwalList = $jadwalGroup->values();

        // Distribusikan praktikan: setiap praktikan hanya masuk 1 jadwal per praktikum
        foreach ($praktikans as $index => $praktikan) {
            // Round-robin: tentukan jadwal mana yang dapat praktikan ini
            $jadwal = $jadwalList[$index % $jadwalList->count()];

            $pendaftarans[] = [
                'id_jadwal'  => $jadwal->id,
                'id_user'    => $praktikan->id,
                'role'       => 'Praktikan',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Asisten: 1-2 per jadwal, tapi tetap tidak double per praktikum
        $assignedAsisten = collect();
        foreach ($jadwalList as $jadwal) {
            $available = $asistens->diff($assignedAsisten);
            if ($available->isEmpty()) break;

            $pick = $available->random(min(2, $available->count()));
            foreach ($pick as $asisten) {
                $pendaftarans[] = [
                    'id_jadwal'  => $jadwal->id,
                    'id_user'    => $asisten->id,
                    'role'       => 'Asisten',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $assignedAsisten->push($asisten);
            }
        }
    }

    // Update or create each record to ensure idempotency
    foreach ($pendaftarans as $pendaftaran) {
        PendaftaranPraktikum::updateOrCreate(
            [
                'id_jadwal' => $pendaftaran['id_jadwal'],
                'id_user' => $pendaftaran['id_user'],
            ],
            [
                'role' => $pendaftaran['role'],
            ]
        );
    }
}
}