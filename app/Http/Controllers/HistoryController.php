<?php
// app/Http/Controllers/HistoryController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Izin;
use App\Models\DinasLuar;
use App\Models\Attendance;
use App\Models\LeaveRequest; 

class HistoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Menggunakan Model langsung untuk memaksa query baru ke database
        $riwayatIzin = Izin::where('user_id', $user->id)
                           ->orderBy('date', 'desc')
                           ->paginate(10, ['*'], 'izin_page');

        $riwayatDinasLuar = DinasLuar::where('user_id', $user->id)
                                    ->orderBy('start_date', 'desc')
                                    ->paginate(10, ['*'], 'dl_page');

        $riwayatTerlambat = Attendance::where('user_id', $user->id)
                                      ->where('late_minutes', '!=', 0)
                                      ->whereNotNull('late_minutes')
                                      ->orderBy('date', 'desc')
                                      ->paginate(10, ['*'], 'terlambat_page');

         $riwayatCutiSakit = LeaveRequest::where('user_id', $user->id)
                             ->orderBy('start_date', 'desc')
                             ->paginate(10, ['*'], 'leave_page');

        return view('riwayat.index', compact('riwayatIzin', 'riwayatDinasLuar', 'riwayatTerlambat', 'riwayatCutiSakit'));
    }
}