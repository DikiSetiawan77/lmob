<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin BKPSDM',
            'email' => 'Sadmin@bkpsdm.go.id',
            'password' => Hash::make('password'), // Ganti dengan password aman
            'role' => 'admin',
            'nip' => '0000000000'
        ]);
    }
}
