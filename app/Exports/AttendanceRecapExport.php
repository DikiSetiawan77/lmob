<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class AttendanceRecapExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $month;
    protected $year;

    public function construct(int $month, int $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    /**
     * Mengambil koleksi data user beserta semua relasi yang dibutuhkan untuk rekap.
     */
    public function collection()
    {
        return User::where('role', 'user')->with([
            'attendances' => function ($query) {
                $query->whereMonth('date', $this->month)->whereYear('date', $this->year);
            }, 
            'dinasLuars' => function ($query) {
                $query->whereMonth('start_date', $this->month)->whereYear('start_date', $this->year)->where('status', 'approved');
            }, 
            'izins' => function ($query) {
                $query->whereMonth('date', $this->month)->whereYear('date', $this->year)->where('status', 'approved');
            }, 
            'leaveRequests' => function ($query) {
                $query->whereMonth('start_date', $this->month)->whereYear('start_date', $this->year)->where('status', 'approved');
            }
        ])->get();
    }

    /**
     * Mendefinisikan judul header untuk setiap kolom di file Excel.
     */
    public function headings(): array
    {
        return [
            'Nama Siswa',
            'NIP',
            'Total Hadir (Hari)',
            'Total Terlambat (Menit)',
            'Dinas Luar (Hari)',
            'Izin Jam (Kali)',
            'Cuti/Sakit (Hari)',
        ];
    }

    /**
     * Memetakan data dari setiap user ke dalam baris Excel.
     */
    public function map($user): array
    {
        // Hitung data dari relasi yang sudah di-load
        $totalHadir = $user->attendances->where('type', 'MASUK')->count();
        $totalTerlambatMenit = $user->attendances->sum('late_minutes');
        
        $totalDinasLuar = $user->dinasLuars->count();
        $totalIzinJam = $user->izins->count();
        
        // Hitung total hari cuti/sakit
        $totalCutiSakit = $user->leaveRequests->sum(function ($leave) {
            // Menghitung jumlah hari dari rentang tanggal (inklusif)
            return Carbon::parse($leave->start_date)->diffInDays(Carbon::parse($leave->end_date)) + 1;
        });

        return [
            $user->name,
            "'" . $user->nip, // Menambahkan kutip untuk format teks
            $totalHadir,
            $totalTerlambatMenit,
            $totalDinasLuar,
            $totalIzinJam,
            $totalCutiSakit,
        ];
    }
}