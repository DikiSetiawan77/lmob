<?php

namespace App\Helpers;

use App\Models\Setting;
use App\Models\Holiday;
use App\Models\LeaveRequest;
use Carbon\Carbon;

class AppHelper
{
    /**
     * Mengambil nilai dari tabel settings berdasarkan key.
     */
    public static function getSetting($key)
    {
        return cache()->rememberForever('setting_' . $key, function () use ($key) {
            $setting = Setting::where('key', $key)->first();
            return $setting ? $setting->value : null;
        });
    }

    /**
     * Menghitung jarak antara dua titik koordinat.
     */
    public static function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000;
        $latFrom = deg2rad($lat1); $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2); $lonTo = deg2rad($lon2);
        $latDelta = $latTo - $latFrom; $lonDelta = $lonTo - $lonFrom;
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }

    /**
     * Cek apakah tanggal tertentu adalah hari libur.
     */
    public static function isHoliday(Carbon $date): bool
    {
        $holidays = cache()->remember('holidays_' . $date->year, 60 * 24, function () use ($date) {
            return Holiday::whereYear('date', $date->year)->pluck('date')->map(fn ($d) => (string) $d);
        });
        return $holidays->contains($date->format('Y-m-d'));
    }

    /**
     * Cek apakah user sedang dalam masa cuti/sakit yang disetujui.
     */
    public static function isOnApprovedLeave(int $userId, Carbon $date): bool
    {
        return LeaveRequest::where('user_id', $userId)
            ->where('status', 'approved')
            ->where('start_date', '<=', $date->format('Y-m-d'))
            ->where('end_date', '>=', $date->format('Y-m-d'))
            ->exists();
    }
}