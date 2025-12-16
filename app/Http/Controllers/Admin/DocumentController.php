<?php
// app/Http/Controllers/Admin/DocumentController.php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function download(Document $document)
    {
        // Pastikan file ada sebelum di-download
        if (!Storage::disk('local')->exists($document->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }
        return Storage::disk('local')->download($document->file_path, $document->original_name);
    }

    public function verify(Request $request, Document $document)
    {
        $request->validate([
            'status' => 'required|in:verified,rejected',
            'rejection_note' => 'nullable|string|required_if:status,rejected',
        ]);

        $document->update([
            'status' => $request->status,
            'rejection_note' => $request->status == 'rejected' ? $request->rejection_note : null,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        return back()->with('status', 'Status dokumen berhasil diperbarui.');
    }
}