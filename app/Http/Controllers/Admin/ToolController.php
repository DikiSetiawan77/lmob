<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\DinasLuar;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class ToolController extends Controller
{
    /**
     * Menampilkan form untuk perbaikan data absensi.
     */
    public function showBackfillForm()
    {
        return view('admin.tools.backfill');
    }

    /**
     * Memproses perbaikan data absensi untuk bulan dan tahun yang dipilih.
     */
    public function processBackfill(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020',
            'target_days' => 'required|integer|min:1|max:31', // Validasi target hari
        ]);

        $month = $request->input('month');
        $year = $request->input('year');
        $targetDays = (int) $request->input('target_days');

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        
        $allPegawai = User::where('role', 'user')->get();
        $holidays = Holiday::whereBetween('date', [$startDate, $endDate])->pluck('date')->map(fn($d) => $d->format('Y-m-d'));
        $adminId = auth()->id();
        $recordsCreated = 0;
        $usersPatched = 0;

        // Loop Pertama: Hitung kehadiran setiap siswa
        foreach ($allPegawai as $siswa) {
            $currentPresence = 0;
            // Hitung absensi masuk
            $currentPresence += $siswa->attendances()->whereMonth('date', $month)->whereYear('date', $year)->count();
            // Hitung dinas luar
            $currentPresence += $siswa->dinasLuars()->where('status', 'approved')->whereMonth('start_date', $month)->whereYear('start_date', $year)->count();
            // Hitung cuti/sakit
            $currentPresence += $siswa->leaveRequests()->where('status', 'approved')->whereMonth('start_date', $month)->whereYear('start_date', $year)
                ->get()->sum(function ($leave) {
                    return $leave->start_date->diffInDays($leave->end_date) + 1;
                });

            $daysToAdd = $targetDays - $currentPresence;

            if ($daysToAdd <= 0) {
                continue; // Lanjut ke siswa berikutnya jika target sudah tercapai
            }

            $usersPatched++;

            // Cari hari kerja kosong untuk siswa ini
            $period = CarbonPeriod::create($startDate, $endDate);
            $emptyWorkDays = [];
            foreach ($period as $date) {
                if ($date->isWeekend() || $holidays->contains($date->format('Y-m-d'))) {
                    continue;
                }
                
                $hasData = Attendance::where('user_id', $siswa->id)->where('date', $date)->exists() ||
                           DinasLuar::where('user_id', $siswa->id)->where('status', 'approved')->whereDate('start_date', '<=', $date)->whereDate('end_date', '>=', $date)->exists() ||
                           LeaveRequest::where('user_id', $siswa->id)->where('status', 'approved')->whereDate('start_date', '<=', $date)->whereDate('end_date', '>=', $date)->exists();
                
                if (!$hasData) {
                    $emptyWorkDays[] = $date->copy();
                }
            }
            
            // Tambahkan record absensi sebanyak yang dibutuhkan ($daysToAdd)
            $daysToFill = array_slice($emptyWorkDays, 0, $daysToAdd);
            foreach ($daysToFill as $fillDate) {
                 Attendance::create([
                    'user_id' => $siswa->id,
                    'date' => $fillDate->format('Y-m-d'),
                    'type' => 'MASUK',
                    'check_in_time' => $fillDate->copy()->setTime(7, rand(15, 29), rand(0, 59)),
                    'check_out_time' => $fillDate->copy()->setTime(16, rand(1, 15), rand(0, 59)),
                    'late_minutes' => 0,
                    'note' => 'Data kehadiran ditambahkan oleh sistem (Backfill).',
                    'source' => 'admin',
                    'created_by_admin_id' => $adminId,
                ]);
                $recordsCreated++;
            }
        }

        return back()->with('status', "Proses Selesai! Total {$recordsCreated} record absensi berhasil ditambahkan untuk {$usersPatched} siswa pada bulan {$startDate->isoFormat('MMMM YYYY')}.");
    }
}