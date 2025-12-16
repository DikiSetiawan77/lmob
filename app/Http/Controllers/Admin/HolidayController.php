<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller {
    public function index() {
        $holidays = Holiday::orderBy('date', 'desc')->paginate(10);
        return view('admin.holidays.index', compact('holidays'));
    }
    public function store(Request $request) {
        $request->validate(['date' => 'required|date|unique:holidays,date', 'name' => 'required|string|max:255']);
        Holiday::create($request->all());
        cache()->forget('holidays_'.date('Y', strtotime($request->date))); // Hapus cache agar data baru terbaca
        return back()->with('status', 'Hari libur berhasil ditambahkan.');
    }
    public function destroy(Holiday $holiday) {
        cache()->forget('holidays_'.date('Y', strtotime($holiday->date))); // Hapus cache
        $holiday->delete();
        return back()->with('status', 'Hari libur berhasil dihapus.');
    }
}