<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Izin;
use App\Models\Holiday;
use App\Models\LeaveRequest;
use App\Models\DinasLuar; // <-- 1. IMPORT MODEL DINAS LUAR
use Carbon\Carbon;
use App\Helpers\AppHelper;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard untuk user (siswa).
     */
    public function index()
    {
        // Arahkan admin ke dashboard admin
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Siapkan variabel dasar
        $user = Auth::user();
        $todayCarbon = Carbon::now('UTC')->addHours(7);
        $today = $todayCarbon->format('Y-m-d');
        
        // Ambil data absensi & izin jam untuk hari ini
        $attendance = Attendance::where('user_id', $user->id)
                                ->where('date', $today)
                                ->first();

        $izinToday = Izin::where('user_id', $user->id)
                         ->where('date', $today)
                         ->where('status', 'approved')
                         ->first();

        // Ambil data rekap untuk bulan ini
        $rekapBulanIni = Attendance::where('user_id', $user->id)
                                ->whereMonth('date', $todayCarbon->month)
                                ->whereYear('date', $todayCarbon->year)
                                ->get();
        
        $totalHadir = $rekapBulanIni->where('type', 'MASUK')->count();
        $totalTerlambat = $rekapBulanIni->sum('late_minutes');

        // Cek status pengecualian (Libur atau Cuti/Sakit)
        $isTodayHoliday = AppHelper::isHoliday($todayCarbon);
        $isOnLeave = AppHelper::isOnApprovedLeave($user->id, $todayCarbon);
        
        $holidayName = '';
        if ($isTodayHoliday) {
            $holiday = Holiday::where('date', $today)->first();
            $holidayName = $holiday ? $holiday->name : 'Hari Libur Nasional';
        }

        // --- 2. TAMBAHKAN LOGIKA UNTUK DINAS LUAR DI SINI ---
        $activeDinasLuar = DinasLuar::where('user_id', $user->id)
            ->where('status', 'approved')
            ->where('start_date', '<=', $todayCarbon)
            ->where('end_date', '>=', $todayCarbon)
            ->first();

        // Tentukan apakah geofence harus di-skip untuk check-in
        $skipGeofenceCheckIn = $activeDinasLuar && in_array($activeDinasLuar->tipe, ['full', 'dinasluar_masukkerja']);
        // --- AKHIR LOGIKA DINAS LUAR ---

        // Ambil pengaturan jam kerja
        $jamMasuk = AppHelper::getSetting('jam_masuk');
        $jamPulang = AppHelper::getSetting('jam_pulang');
        
        // --- 3. TAMBAHKAN VARIABEL BARU KE compact() ---
        return view('dashboard', compact(
            'attendance',
            'izinToday',
            'isTodayHoliday',
            'holidayName',
            'isOnLeave',
            'skipGeofenceCheckIn', // <-- Variabel baru
            'jamMasuk',
            'jamPulang',
            'today',
            'totalHadir',
            'totalTerlambat'
        ));
    }
}