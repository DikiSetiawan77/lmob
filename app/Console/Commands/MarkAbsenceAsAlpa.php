<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Attendance;
use App\Models\DinasLuar;
use Carbon\Carbon;
use App\Helpers\AppHelper;

class MarkAbsenceAsAlpa extends Command
{
    /**
     * Nama dan signature dari console command.
     * {date?} : membuat parameter tanggal opsional
     */
    protected $signature = 'attendance:mark-alpa {date?}';

    /**
     * Deskripsi console command.
     */
    protected $description = 'Tandai siswa yang tidak hadir (bukan DL/Izin) sebagai ALPA untuk tanggal tertentu (default: kemarin)';

    /**
     * Jalankan console command.
     * HANYA ADA SATU METHOD HANDLE() DI SINI.
     */
    public function handle()
    {
        // Langkah 1: Tentukan tanggal. Jika tidak ada parameter, gunakan tanggal kemarin.
        $date = $this->argument('date') ? Carbon::parse($this->argument('date')) : Carbon::yesterday();
        $this->info("Memproses absensi untuk tanggal: " . $date->format('Y-m-d'));

        // Langkah 2: Cek apakah tanggal tersebut adalah hari libur. Jika ya, hentikan proses.
        if (isHoliday($date)) {
            $this->info("Tanggal adalah hari libur, proses dihentikan.");
            return 0; // Menghentikan eksekusi command dengan sukses
        }

        // Langkah 3: Ambil semua siswa yang seharusnya masuk pada hari itu.
        $usersToCheck = User::where('role', 'user')->get();
        
        $alpaCount = 0;

        // Langkah 4: Loop melalui setiap siswa untuk diperiksa.
        foreach ($usersToCheck as $user) {
            // Cek apakah sudah ada record absensi untuk tanggal tersebut.
            $hasAttendance = Attendance::where('user_id', $user->id)->where('date', $date->format('Y-m-d'))->exists();
            $isOnLeave = isOnApprovedLeave($user->id, $date);
            
            // Cek apakah ada dinas luar yang aktif pada tanggal tersebut.
            $hasDinasLuar = DinasLuar::where('user_id', $user->id)
                                ->whereDate('start_date', '<=', $date)
                                ->whereDate('end_date', '>=', $date)
                                ->exists();
            
            // (Anda bisa menambahkan pengecekan Izin di sini jika diperlukan)
            
            // Jika tidak ada record absensi DAN tidak sedang dinas luar, maka tandai sebagai ALPA.
             if (!$hasAttendance && !$hasDinasLuar && !$isOnLeave) {
                Attendance::create([
                    'user_id' => $user->id,
                    'date' => $date->format('Y-m-d'),
                    'type' => 'ALPA',
                    'note' => 'Dibuat otomatis oleh sistem.',
                    'source' => 'admin',
                ]);
                $this->line(" - Pengguna {$user->name} ditandai ALPA.");
                $alpaCount++;
            }
        }

        $this->info("Selesai. Total {$alpaCount} siswa ditandai sebagai ALPA.");
        return 0; // Mengindikasikan command berjalan dengan sukses
    }
}