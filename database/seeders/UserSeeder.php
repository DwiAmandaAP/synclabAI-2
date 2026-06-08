<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    private int $phoneCounter = 890;

    private function phone(): string
    {
        return '081234567' . str_pad($this->phoneCounter++, 3, '0', STR_PAD_LEFT);
    }

    public function run(): void
    {
        $users = [

            // Admin
            ['Budi Kangoding', 'admin@abc.c', '199001011990', 'Admin'],
            ['Sri Wahyu Hartono', 'admin.lab.a@univ.ac.id', '198801011988', 'Admin'],
            ['Hendra Gunawan', 'admin.lab.b@univ.ac.id', '198902011989', 'Admin'],

            // Dosen
            ['Dr. Alek Skom, M.Kom', 'alek.skom@univ.ac.id', '198501011985', 'Dosen'],
            ['Prof. Budi Santoso, S.Kom, M.Sc', 'budi.santoso@univ.ac.id', '198102011981', 'Dosen'],
            ['Dr. Siti Nurhaliza, M.Kom', 'siti.nurhaliza@univ.ac.id', '198703011110', 'Dosen'],

            // Asisten
            ['Andi Imphnen Arifianto', 'andi.imphnen@univ.ac.id', '2021001001', 'Asisten'],
            ['Eri Sepuh', 'epuh@univ.ac.id', '2021001002', 'Asisten'],
            ['Siti Mardhiah Pratiwi', 'siti.mardhiah@univ.ac.id', '2021001003', 'Asisten'],

            // Praktikan
            ['Eri Sepuh', 'eri.sepuh@student.univ.ac.id', '2022001001', 'Praktikan'],
            ['Rizka Aulia Putri', 'rizka.aulia@student.univ.ac.id', '2022001002', 'Praktikan'],
            ['Doni Setiawan', 'doni.setiawan@student.univ.ac.id', '2022001003', 'Praktikan'],
        ];

        foreach ($users as [$nama, $email, $nomor_induk, $role]) {
            User::create([
                'nama' => $nama,
                'email' => $email,
                'nohp' => $this->phone(),
                'password' => Hash::make('password123'),
                'nomor_induk' => $nomor_induk,
                'role' => $role,
            ]);
        }
    }
}