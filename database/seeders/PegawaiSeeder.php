<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pegawaiData = [
            ['name' => 'NANDA PRASETYO', 'email' => 'nanduridam@gmail.com', 'nip' => '3344442222', 'bidang_unit' => 'Bidang kinerja' ],
            ['name' => 'CANDRA AGUSTIKA', 'email' => 'smk2.aguss@gmail.com', 'nip' => '3344467822'],
            ['name' => 'DIKI SETWAN', 'email' => 'di0kii@gmail.com', 'nip' => '9874489922'],
            ['name' => 'CINDY RISKA', 'email' => 'riskac65in@gmail.com', 'nip' => '3574456722'],
            ['name' => 'SYALSABILAK', 'email' => 'bill448@gmail.com', 'nip' => '3667854329'],
            ['name' => 'NISSA RIKA', 'email' => 'sarik32@gmail.com', 'nip' => '3799865422'],
            ['name' => 'SITI GENDHISA', 'email' => 'siti61@gmail.com', 'nip' => '1029483658'],
            ['name' => 'YANTI KUMAYANTI', 'email' => 'yk6861@gmail.com', 'nip' => '3117896652'],
            ['name' => 'YENA SUHERMAN', 'email' => 'naherman901@gmail.com', 'nip' => '0389711222'],
            ['name' => 'YOPI SFIRDAUS', 'email' => 'yopiesfsf@gmail.com', 'nip' => '1325786922'],
            ['name' => 'JULIFAR YUDHA', 'email' => 'yudhajulifar@gmail.com', 'nip' => '5109759230'],
        ];

        foreach ($pegawaiData as $data) {
            // updateOrCreate akan mencari user berdasarkan email.
            // Jika ada, akan di-update. Jika tidak ada, akan dibuat.
            // Ini mencegah duplikasi jika seeder dijalankan lagi.
            User::updateOrCreate(
                ['email' => $data['email']], // Kunci untuk mencari
                [
                    'name' => $data['name'],
                    'nip' => $data['nip'],
                    'password' => Hash::make('123456'), // Password default
                    'role' => 'user', // Set role sebagai user
                ]
            );
        }
    }
}