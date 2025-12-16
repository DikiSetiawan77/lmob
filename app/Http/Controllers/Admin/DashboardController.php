<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use App\Models\DinasLuar;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use App\Helpers\AppHelper;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard admin dengan data ringkasan hari ini.
     */
    public function index()
    {
        // 1. Siapkan variabel dasar
        $today = Carbon::today();
        $isTodayHoliday = AppHelper::isHoliday($today);

        // 2. Inisialisasi semua variabel data dengan nilai default
        $belumAbsen = collect();
        $attendancesToday = collect();
        $dinasLuarToday = collect();
        $leaveRequestToday = collect(); // Inisialisasi
        $attendedUserIds = collect();   // Inisialisasi
        
        $totalSudahAbsen = 0;
        $totalTerlambat = 0;
        $totalDinasLuar = 0;
        $totalCutiSakit = 0;

        // 3. Hanya jalankan query absensi jika BUKAN hari libur
        if (!$isTodayHoliday) {
            
            // Query untuk data absensi yang sudah masuk hari ini
            $attendancesToday = Attendance::where('date', $today)->with('user')->get();
            $attendedUserIds = $attendancesToday->pluck('user_id'); // Buat daftar ID dari sini
            
            // Query untuk siswa yang sedang dinas luar (disetujui)
            $dinasLuarToday = DinasLuar::where('status', 'approved')
                                       ->whereDate('start_date', '<=', $today)
                                       ->whereDate('end_date', '>=', $today)
                                       ->with('user')
                                       ->get();

            // Query untuk siswa yang sedang cuti/sakit (disetujui)
            $leaveRequestToday = LeaveRequest::where('status', 'approved')
                                             ->whereDate('start_date', '<=', $today)
                                             ->whereDate('end_date', '>=', $today)
                                             ->with('user')
                                             ->get();

            // Query untuk siswa yang belum hadir (setelah semua data lain didapat)
            $belumAbsen = User::where('role', 'user')
                ->whereDoesntHave('attendances', function($query) use ($today) {
                    $query->where('date', $today);
                })
                ->whereDoesntHave('dinasLuars', function($query) use ($today){
                    $query->where('status', 'approved')->whereDate('start_date', '<=', $today)->whereDate('end_date', '>=', $today);
                })
                ->whereDoesntHave('leaveRequests', function($query) use ($today) {
                    $query->where('status', 'approved')->whereDate('start_date', '<=', $today)->whereDate('end_date', '>=', $today);
                })
                ->get();
            
            // Hitung statistik dari data yang sudah diambil
            $totalSudahAbsen = $attendancesToday->count();
            $totalTerlambat = $attendancesToday->where('late_minutes', '>', 0)->count();
            $totalDinasLuar = $dinasLuarToday->count();
            $totalCutiSakit = $leaveRequestToday->count();
        }

        // 4. Ambil total siswa (dijalankan terlepas dari hari libur)
        $totalPegawai = User::where('role', 'user')->count();

        // 5. Kirim SEMUA variabel ke view dalam SATU kali return
        return view('admin.dashboard', compact(
            'belumAbsen', 
            'attendancesToday', 
            'dinasLuarToday',
            'leaveRequestToday',      // <-- Dikirim ke view
            'attendedUserIds',        // <-- Dikirim ke view
            'totalPegawai',
            'totalSudahAbsen',
            'totalTerlambat',
            'totalDinasLuar',
            'totalCutiSakit',         // <-- Dikirim ke view
            'isTodayHoliday'
        ));
    }
}