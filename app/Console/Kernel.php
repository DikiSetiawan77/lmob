<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Laravel secara otomatis akan menemukan command Anda
        // di direktori app/Console/Commands, jadi Anda tidak perlu
        // mendaftarkannya secara manual di sini, kecuali jika Anda menempatkannya
        // di lokasi yang tidak standar.
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

        /**
         * --------------------------------------------------------------------------
         * Tugas Penjadwalan Aplikasi Absensi
         * --------------------------------------------------------------------------
         *
         * Di sinilah kita mendaftarkan semua tugas otomatis (cron jobs)
         * yang berhubungan dengan aplikasi absensi.
         */

        // TUGAS 1: Menandai Siswa yang Tidak Hadir sebagai "ALPA"
        // Perintah ini akan menjalankan command 'attendance:mark-alpa'
        // yang telah kita buat sebelumnya.
        //
        // ->dailyAt('01:00'): Menjalankan tugas setiap hari pada pukul 01:00 pagi.
        // ->timezone('Asia/Jakarta'): Memastikan waktu eksekusi sesuai dengan zona waktu Indonesia.
        //
        // Catatan: Waktu 01:00 dipilih untuk memastikan semua aktivitas di hari sebelumnya
        // sudah benar-benar selesai. Anda bisa mengubahnya, misal '23:59'.
        $schedule->command('attendance:mark-alpa')
                 ->dailyAt('01:00')
                 ->timezone('Asia/Jakarta')
                 ->withoutOverlapping(); // Mencegah tugas berjalan ganda jika eksekusi sebelumnya belum selesai.

        
        /*
         * CONTOH TUGAS LAIN YANG BISA DITAMBAHKAN NANTI:
         *
         * // Mengirim email pengingat kepada yang belum absen masuk pada jam 9 pagi.
         * // $schedule->command('reminder:check-in')->weekdays()->at('09:00');
         *
         * // Menghapus data absensi yang sudah lebih dari 5 tahun.
         * // $schedule->command('attendance:cleanup')->yearly();
         *
         * // Men-generate laporan bulanan dan mengirimkannya ke email admin.
         * // $schedule->command('report:generate-monthly')->monthlyOn(1, '02:00'); // Setiap tanggal 1 jam 2 pagi.
         */
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        // Memuat file command dari routes/console.php
        $this->load(__DIR__.'/Commands');

        // Memuat file command dari routes/console.php
        require base_path('routes/console.php');
    }
}