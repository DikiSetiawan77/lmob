<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use App\Exports\AttendanceRecapExport;
use Maatwebsite\Excel\Facades\Excel;

class RecapController extends Controller
{
    /**
     * Menampilkan halaman rekapitulasi absensi.
     */
    public function index(Request $request)
    {
        // 1. Ambil bulan dan tahun dari request, atau gunakan bulan ini sebagai default
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        // --- SOLUSI: BUAT NAMA BULAN DI SINI, BUKAN DI VIEW ---
        // Ini akan dikirim ke view untuk ditampilkan di judul dan dropdown.
        $monthName = Carbon::createFromDate($year, $month, 1)->isoFormat('MMMM');

        // 2. Ambil semua user beserta relasi yang relevan untuk bulan dan tahun yang dipilih
        $users = User::where('role', 'user')->with([
            'attendances' => function ($query) use ($month, $year) {
                $query->whereMonth('date', $month)->whereYear('date', $year);
            }, 
            'dinasLuars' => function ($query) use ($month, $year) {
                $query->whereMonth('start_date', $month)->whereYear('start_date', $year)->where('status', 'approved');
            }, 
            'izins' => function ($query) use ($month, $year) {
                $query->whereMonth('date', $month)->whereYear('date', $year)->where('status', 'approved');
            }, 
            'leaveRequests' => function ($query) use ($month, $year) {
                $query->whereMonth('start_date', $month)->whereYear('start_date', $year)->where('status', 'approved');
            }
        ])->get();

        // 3. Proses data mentah menjadi format rekap yang siap ditampilkan
        $recapData = $users->map(function ($user) {
            $totalHadir = $user->attendances->where('type', 'MASUK')->count();
            $totalTerlambatMenit = $user->attendances->sum('late_minutes');
            $totalDinasLuar = $user->dinasLuars->count();
            $totalIzin = $user->izins->count();
            $totalCutiSakit = $user->leaveRequests->sum(function ($leave) {
                return Carbon::parse($leave->start_date)->diffInDays(Carbon::parse($leave->end_date)) + 1;
            });

            return [
                'name' => $user->name,
                'nip' => $user->nip,
                'total_hadir' => $totalHadir,
                'total_terlambat_menit' => $totalTerlambatMenit,
                'total_dinas_luar' => $totalDinasLuar,
                'total_izin' => $totalIzin,
                'total_cuti_sakit' => $totalCutiSakit,
            ];
        });

        // 4. Kirim semua data yang dibutuhkan ke view
        return view('admin.recap.index', compact('recapData', 'month', 'year', 'monthName'));
    }

    /**
     * Menangani request untuk export data rekapitulasi ke Excel.
     */
     public function exportExcel(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $monthName = Carbon::createFromDate(null, $month, 1)->isoFormat('MMMM');

        $fileName = "rekap_absensi_{$monthName}_{$year}.xlsx";

        return Excel::download(new AttendanceRecapExport((int)$month, (int)$year), $fileName);
    }
}