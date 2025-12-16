<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Izin;
use Illuminate\Support\Facades\Auth;

class IzinController extends Controller
{
    public function create()
    {
        return view('izin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'izin_type' => 'required|in:terlambat,pulang_cepat',
            'date' => 'required|date',
            'allowed_time' => 'required',
            'note' => 'required|string|min:10',
        ]);

        Izin::create([
            'user_id' => auth()->id(),
            'izin_type' => $request->izin_type,
            'date' => $request->date,
            'allowed_time' => $request->allowed_time,
            'note' => $request->note,
        ]);

        return redirect()->route('dashboard')->with('status', 'Pengajuan izin berhasil dikirim dan menunggu persetujuan.');
    }

    public function destroy(Izin $izin)
    {
        // Otorisasi: Pastikan user yang login adalah pemilik data
        if (Auth::id() !== $izin->user_id) {
            abort(403, 'AKSES DITOLAK');
        }

        // Hapus data dari database
        $izin->delete();

        // Arahkan kembali ke halaman riwayat dengan pesan sukses
        return redirect()->route('riwayat.index')->with('status', 'Pengajuan izin berhasil dihapus.');
    }
}