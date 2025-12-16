<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DinasLuar;
use Illuminate\Support\Facades\Auth;

class DinasLuarController extends Controller
{
    public function create()
    {
        return view('dinasluar.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe' => 'required|in:full,masuk_kerja_dinasluar,dinasluar_masukkerja',
            'lokasi_nama' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'note' => 'required|string|min:10',
        ]);

        DinasLuar::create([
            'user_id' => auth()->id(),
            'tipe' => $request->tipe,
            'lokasi_nama' => $request->lokasi_nama,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'note' => $request->note,
        ]);

        return redirect()->route('dashboard')->with('status', 'Pengajuan Dinas Luar berhasil disimpan.');
    }

    public function destroy(DinasLuar $dinasLuar)
    {
        // Otorisasi: Pastikan user yang login adalah pemilik data
        if (Auth::id() !== $dinasLuar->user_id) {
            // Jika bukan, tolak akses
            abort(403, 'AKSES DITOLAK');
        }

        // Hapus data dari database
        $dinasLuar->delete();

        // Arahkan kembali ke halaman riwayat dengan pesan sukses
        return redirect()->route('riwayat.index')->with('status', 'Pengajuan dinas luar berhasil dihapus.');
    }
}