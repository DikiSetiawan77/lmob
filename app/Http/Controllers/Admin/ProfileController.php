<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman edit profil (termasuk form ubah password) untuk admin.
     */
    public function edit()
    {
        // Mengambil data admin yang sedang login
        $admin = Auth::user();
        return view('admin.profile.edit', compact('admin'));
    }

    /**
     * Mengupdate password admin yang sedang login.
     */
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
        return back()->with('status', 'Password Anda berhasil diperbarui.');
    }
}