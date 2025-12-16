<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama untuk menghindari duplikasi jika seeder dijalankan lagi
        Setting::truncate();

        // Data Pengaturan Kantor
        Setting::create(['key' => 'kantor_lat', 'value' => '-6.8494624']); // Lokasi BKPSDM Subang
        Setting::create(['key' => 'kantor_long', 'value' => '107.5468755']);
        Setting::create(['key' => 'kantor_radius', 'value' => '10000']); // Radius dalam meter

        // Data Pengaturan Waktu
        Setting::create(['key' => 'jam_masuk', 'value' => '07:30:00']);
        Setting::create(['key' => 'jam_pulang', 'value' => '15:00:00']);
    }
}