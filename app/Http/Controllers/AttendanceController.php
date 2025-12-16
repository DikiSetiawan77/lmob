<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Izin;
use App\Models\DinasLuar;
use Carbon\Carbon;
use App\Helpers\AppHelper;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Menangani proses absensi masuk (check-in).
     */
    public function checkIn(Request $request)
    {
        $request->validate(['lat' => 'required|numeric', 'long' => 'required|numeric']);

        $user_id = Auth::id();
        $waktu_absen = Carbon::now('UTC')->addHours(7);
        $today = $waktu_absen->format('Y-m-d');

        $activeDL = DinasLuar::where('user_id', $user_id)
                            ->where('start_date', '<=', $waktu_absen)
                            ->where('end_date', '>=', $waktu_absen)
                            ->first();
        
        // Aturan: Geofence TIDAK BERLAKU saat check-in jika tipe DL adalah 'full' atau 'dinasluar_masukkerja'
        $skipGeofence = $activeDL && in_array($activeDL->tipe, ['full', 'dinasluar_masukkerja']);

        if (!$skipGeofence) {
            $lat_kantor = AppHelper::getSetting('kantor_lat');
            $long_kantor = AppHelper::getSetting('kantor_long');
            $radius = AppHelper::getSetting('kantor_radius');
            $distance = AppHelper::haversineDistance($request->lat, $request->long, $lat_kantor, $long_kantor);

            if ($distance > $radius) {
                return response()->json(['success' => false, 'message' => 'Anda berada di luar radius kantor yang diizinkan.']);
            }
        }
        
        $jam_masuk_standar = Carbon::parse($today . ' ' . AppHelper::getSetting('jam_masuk'));
        $late_minutes = 0;
        $is_late = false;

        if ($waktu_absen->isAfter($jam_masuk_standar)) {
            $is_late = true;
        }

        if ($is_late) {
            $izinToday = Izin::where('user_id', $user_id)
            ->where('date', $today)
            ->where('izin_type', 'terlambat')
            ->where('status', 'approved')
            ->first();
            if ($izinToday) {
                $batas_waktu_izin = Carbon::parse($izinToday->date . ' ' . $izinToday->allowed_time);
                if ($waktu_absen->isBeforeOrEqualTo($batas_waktu_izin)) {
                    $is_late = false;
                }
            }
        }

        if ($is_late) {
            $late_minutes = $jam_masuk_standar->diffInMinutes($waktu_absen, false);
        }

        Attendance::create([
            'user_id' => $user_id,
            'date' => $today,
            'type' => 'MASUK',
            'check_in_time' => $waktu_absen,
            'lat' => $request->lat,
            'long' => $request->long,
            'late_minutes' => $late_minutes,
            'note' => $activeDL ? 'Dinas Luar: ' . $activeDL->lokasi_nama : null,
        ]);
        
        return response()->json(['success' => true, 'message' => 'Absen Masuk berhasil dicatat.']);
    }

    /**
     * Menangani proses absensi pulang (check-out).
     */
    public function checkOut(Request $request)
    {
        $request->validate(['lat' => 'required|numeric', 'long' => 'required|numeric']);

        $user_id = Auth::id();
        $waktu_sekarang = Carbon::now('UTC')->addHours(7);
        $today = $waktu_sekarang->format('Y-m-d');

        $attendance = Attendance::where('user_id', $user_id)
                                ->where('date', $today)
                                ->where('type', 'MASUK')
                                ->first();

        if (!$attendance) {
            return response()->json(['success' => false, 'message' => 'Anda belum melakukan absen masuk hari ini.']);
        }

        if ($attendance->check_out_time) {
            return response()->json(['success' => false, 'message' => 'Anda sudah melakukan absen pulang hari ini.']);
        }
        
        // --- LOGIKA GEO-FENCE BARU UNTUK CHECK-OUT ---
        $activeDL = DinasLuar::where('user_id', $user_id)
                            ->where('start_date', '<=', $waktu_sekarang)
                            ->where('end_date', '>=', $waktu_sekarang)
                            ->where('status', 'approved')
                            ->first();
        
        // Aturan: Geofence TIDAK BERLAKU saat checkout jika tipe DL adalah 'full' atau 'masuk_kerja_dinasluar'
        $skipGeofence = $activeDL && in_array($activeDL->tipe, ['full', 'masuk_kerja_dinasluar']);

        if (!$skipGeofence) {
            // Jika tidak ada pengecualian, jalankan validasi jarak
            $lat_kantor = AppHelper::getSetting('kantor_lat');
            $long_kantor = AppHelper::getSetting('kantor_long');
            $radius = AppHelper::getSetting('kantor_radius');
            $distance = AppHelper::haversineDistance($request->lat, $request->long, $lat_kantor, $long_kantor);

            if ($distance > $radius) {
                return response()->json(['success' => false, 'message' => 'Anda harus berada di dalam radius kantor untuk absen pulang.']);
            }
        }
        // --- AKHIR LOGIKA GEO-FENCE BARU ---

        $attendance->update([
            'check_out_time' => $waktu_sekarang
        ]);

        return response()->json(['success' => true, 'message' => 'Absen Pulang berhasil dicatat.']);
    }
}