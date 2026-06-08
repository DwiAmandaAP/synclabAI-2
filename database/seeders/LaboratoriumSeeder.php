<?php

namespace Database\Seeders;

use App\Models\Laboratorium;
use App\Models\User;
use Illuminate\Database\Seeder;

class LaboratoriumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin users untuk kepala lab (bukan dosen)
        $admins = User::where('role', 'Admin')->get();
        
        if ($admins->isEmpty()) {
            return;
        }

        $laboratoriums = [
            [
                'kode_laboratorium' => 'LAB001',
                'nama_laboratorium' => 'Lab Komputer A - Pemrograman',
                'lokasi' => 'Gedung A Lt. 1',
                'kapasitas' => 30,
                'kepala_lab' => $admins[0]->id,
                'status' => 'Tersedia',
            ],
            [
                'kode_laboratorium' => 'LAB002',
                'nama_laboratorium' => 'Lab Komputer B - Dasar',
                'lokasi' => 'Gedung A Lt. 2',
                'kapasitas' => 25,
                'kepala_lab' => $admins[1]->id,
                'status' => 'Tersedia',
            ],
            [
                'kode_laboratorium' => 'LAB003',
                'nama_laboratorium' => 'Lab Jaringan - Network',
                'lokasi' => 'Gedung B Lt. 1',
                'kapasitas' => 20,
                'kepala_lab' => $admins[2]->id,
                'status' => 'Tersedia',
            ],
        ];

        foreach ($laboratoriums as $lab) {
            Laboratorium::create($lab);
        }
    }
}
