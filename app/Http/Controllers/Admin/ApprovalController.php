<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Izin;
use App\Models\DinasLuar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\LeaveRequest;

class ApprovalController extends Controller
{
    /**
     * Display a listing of pending approvals.
     */
    public function index()
    {
        $pendingIzins = Izin::where('status', 'pending')->with('user')->latest()->get();
        $pendingDinasLuars = DinasLuar::where('status', 'pending')->with('user')->latest()->get();
        $pendingLeaveRequests = LeaveRequest::where('status', 'pending')->with('user')->latest()->get(); // <-- Tambah
        return view('admin.approvals.index', compact('pendingIzins', 'pendingDinasLuars', 'pendingLeaveRequests')); // <-- Tambah
    }

    public function processLeaveRequest(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate(['status' => 'required|in:approved,rejected', 'rejection_note' => 'nullable|string|required_if:status,rejected']);
        $leaveRequest->update([
            'status' => $request->status,
            'rejection_note' => $request->status == 'rejected' ? $request->rejection_note : null,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        cache()->flush();
        return back()->with('status', 'Pengajuan Cuti/Sakit telah diproses.');
    }

    /**
     * Process an "Izin" request.
     */
    public function processIzin(Request $request, Izin $izin)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_note' => 'nullable|string|required_if:status,rejected',
        ]);

        $izin->update([
            'status' => $request->status,
            'rejection_note' => $request->status == 'rejected' ? $request->rejection_note : null,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        cache()->flush();

        return back()->with('status', 'Pengajuan izin telah diproses.');
    }
    
    /**
     * Process a "Dinas Luar" request.
     */
    public function processDinasLuar(Request $request, DinasLuar $dinasLuar)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_note' => 'nullable|string|required_if:status,rejected',
        ]);

        $dinasLuar->update([
            'status' => $request->status,
            'rejection_note' => $request->status == 'rejected' ? $request->rejection_note : null,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        cache()->flush();

        return back()->with('status', 'Pengajuan dinas luar telah diproses.');
    }
}