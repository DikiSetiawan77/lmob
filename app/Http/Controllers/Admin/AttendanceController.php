<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function forceStore(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'check_in_time' => 'required|date',
            'note' => 'required|string|min:5',
        ]);

        $checkInTime = Carbon::parse($request->check_in_time);

        Attendance::create([
            'user_id' => $request->user_id,
            'date' => $checkInTime->format('Y-m-d'),
            'type' => 'MASUK',
            'check_in_time' => $checkInTime,
            'note' => $request->note,
            'source' => 'admin',
            'created_by_admin_id' => auth()->id(), // Mencatat siapa admin yang menginput
        ]);

        return redirect()->route('admin.dashboard')->with('status', 'Absensi manual berhasil disimpan.');
    }
}