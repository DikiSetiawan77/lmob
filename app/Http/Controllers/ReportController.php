<?php
namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    // Menampilkan daftar laporan milik user yang login
    public function index()
    {
        $reports = Report::where('user_id', auth()->id())->orderBy('date', 'desc')->paginate(10);
        return view('reports.index', compact('reports'));
    }

    // Menampilkan form untuk membuat laporan baru
    public function create()
    {
        return view('reports.create');
    }

    // Menyimpan laporan baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after_or_equal:start_time',
            'title' => 'required|string|max:255',
            'description' => ['required', 'string', 'min:10'], // Validasi minimal 10 karakter
            'photos.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('public/report_photos');
                $photoPaths[] = Storage::url($path);
            }
        }

        Report::create([
            'user_id' => auth()->id(),
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'title' => $request->title,
            'description' => $request->description,
            'photos' => json_encode($photoPaths)
        ]);

        return redirect()->route('reports.index')->with('status', 'Laporan harian berhasil dibuat.');
    }

    // Menampilkan form untuk mengedit laporan
    public function edit(Report $report)
    {
        // Otorisasi: Pastikan user hanya bisa mengedit laporannya sendiri
        if (auth()->id() !== $report->user_id) {
            abort(403);
        }
        return view('reports.edit', compact('report'));
    }

    // Mengupdate laporan di database
    public function update(Request $request, Report $report)
    {
        // Otorisasi
        if (auth()->id() !== $report->user_id) {
            abort(403);
        }
        
        // (Logika validasi dan update mirip dengan store, bisa ditambahkan di sini)
        // ...
        
        return redirect()->route('reports.index')->with('status', 'Laporan harian berhasil diperbarui.');
    }

    // Menghapus laporan dari database
    public function destroy(Report $report)
    {
        // Otorisasi
        if (auth()->id() !== $report->user_id) {
            abort(403);
        }

        // Hapus foto jika ada
        // ...

        $report->delete();
        return redirect()->route('reports.index')->with('status', 'Laporan harian berhasil dihapus.');
    }

    public function print($month, $year)
    {
        $user = auth()->user();
        $reports = Report::where('user_id', $user->id)
                         ->whereMonth('date', $month)
                         ->whereYear('date', $year)
                         ->orderBy('date', 'asc')
                         ->get();
        
        $monthName = \Carbon\Carbon::createFromDate(null, $month, 1)->isoFormat('MMMM');

        $pdf = Pdf::loadView('reports.print', compact('reports', 'user', 'monthName', 'year'));

        // Opsi: return $pdf->stream(); // untuk preview di browser
        return $pdf->download("laporan_harian_{$user->name}_{$monthName}_{$year}.pdf");
    }
}