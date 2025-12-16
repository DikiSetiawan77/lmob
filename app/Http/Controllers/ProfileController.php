<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Document;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil dan daftar dokumen untuk user yang sedang login.
     */
    public function edit()
    {
        $user = Auth::user();
        
        // Mengambil dokumen user dan mengelompokkannya berdasarkan tipe untuk kemudahan akses di view
        $documents = $user->documents()->get()->keyBy('type');
        
        // Daftar tipe dokumen yang wajib diunggah
        $documentTypes = ['ktp', 'npwp', 'sk_terbaru', 'ijazah_terakhir', 'bpjs_kes', 'bpjs_ket'];
        
        return view('profile.edit', compact('user', 'documents', 'documentTypes'));
    }
     public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'nip' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'bidang_unit' => ['nullable', 'string', 'max:255'],
        ]);

        $user->update($request->only('name', 'email', 'nip', 'bidang_unit'));

        return back()->with('status_profile', 'Biodata berhasil diperbarui.');
    }

    public function downloadDocument(Document $document)
    {
        // Otorisasi: pastikan user hanya bisa download dokumen miliknya
        if (Auth::id() !== $document->user_id) {
            abort(403);
        }

        if (!Storage::disk('local')->exists($document->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }
        
        return Storage::disk('local')->download($document->file_path, $document->original_name);
    }

    public function destroyDocument(Document $document)
    {
        // Otorisasi
        if (Auth::id() !== $document->user_id) {
            abort(403);
        }

        // Hapus file dari storage
        Storage::disk('local')->delete($document->file_path);

        // Hapus record dari database
        $document->delete();

        return back()->with('status', 'Dokumen berhasil dihapus.');
    }

    /**
     * Menangani proses upload dokumen dari halaman profil.
     */
    public function uploadDocument(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'document_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $user = Auth::user();
        $file = $request->file('document_file');
        $type = $request->type;
        
        // Simpan file ke storage/app/documents/{user_id}/...
        // Menggunakan 'local' disk berarti file tidak dapat diakses publik secara langsung
        $path = $file->store("documents/{$user->id}", 'local');

        // Hapus dokumen lama dengan tipe yang sama jika ada, untuk memastikan hanya ada satu file per tipe
        $user->documents()->where('type', $type)->delete();
        
        // Buat record dokumen baru di database
        Document::create([
            'user_id' => $user->id,
            'type' => $type,
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'status' => 'pending' // Status awal adalah 'pending' menunggu verifikasi admin
        ]);

        return back()->with('status', 'Dokumen berhasil diunggah dan menunggu verifikasi.');
    }

    public function updatePassword(Request $request)
    {
        // Validasi input
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        // Update password di database
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Arahkan kembali dengan pesan sukses
        return back()->with('status_password', 'Password berhasil diperbarui.');
    }
}