<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class PegawaiController extends Controller
{
    /**
     * Menampilkan halaman daftar semua siswa.
     * Mengambil data pengguna dengan role 'user', diurutkan dari yang terbaru, dan dipaginasi.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request) // <-- Tambahkan Request $request
    {
        // Ambil kata kunci pencarian dari request
        $keyword = $request->input('search');

        // Mulai query ke model User
        $query = User::where('role', 'user');

        // Jika ada kata kunci pencarian, tambahkan kondisi WHERE
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                  ->orWhere('nip', 'LIKE', "%{$keyword}%")
                  ->orWhere('email', 'LIKE', "%{$keyword}%");
            });
        }

        // Eksekusi query dengan paginasi
        $users = $query->latest()->paginate(10);
        
        // Agar paginasi tetap berjalan saat ada filter
        $users->appends($request->only('search'));

        // Kirim data users dan keyword ke view
        return view('admin.siswa.index', compact('users', 'keyword'));
    }

    /**
     * Menampilkan form untuk membuat data siswa baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.siswa.create');
    }

    /**
     * Menyimpan data siswa baru ke dalam database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'nip' => ['required', 'string', 'max:20', 'unique:users'],
            'bidang_unit' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nip' => $request->nip,
            'bidang_unit' => $request->bidang_unit,
            'password' => Hash::make($request->password),
            'role' => 'user', // Otomatis mengatur role sebagai 'user'
        ]);

        return redirect()->route('admin.siswa.index')->with('status', 'Siswa baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan halaman detail seorang siswa, termasuk dokumen-dokumennya.
     * Menggunakan route-model binding ($siswa akan otomatis diambil dari ID di URL).
     *
     * @param  \App\Models\User  $siswa
     * @return \Illuminate\View\View
     */
    public function show(User $siswa)
    {
        // Eager load relasi documents untuk efisiensi query
        $siswa->load('documents');
        return view('admin.siswa.show', compact('siswa'));
    }

    /**
     * Menampilkan form untuk mengedit data siswa.
     *
     * @param  \App\Models\User  $siswa
     * @return \Illuminate\View\View
     */
    public function edit(User $siswa) // <-- Namanya $siswa
    {
        return view('admin.siswa.edit', compact('siswa')); // <-- Dikirim sebagai 'siswa'
    }

    /**
     * Mengupdate data siswa yang ada di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $siswa
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $siswa)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // Validasi 'unique' mengabaikan ID user saat ini agar tidak error saat email/NIP tidak diubah
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $siswa->id],
            'nip' => ['required', 'string', 'max:20', 'unique:users,nip,' . $siswa->id],
            'bidang_unit' => ['nullable', 'string', 'max:255'],
            // Password tidak wajib diisi saat update
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $siswa->update([
            'name' => $request->name,
            'email' => $request->email,
            'nip' => $request->nip,
            'bidang_unit' => $request->bidang_unit,
        ]);

        // Hanya update password jika field password diisi oleh admin
        if ($request->filled('password')) {
            $siswa->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.siswa.index')->with('status', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Menghapus data siswa dari database.
     *
     * @param  \App\Models\User  $siswa
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $siswa)
    {
        // Menghapus user akan otomatis menghapus dokumen terkait karena 'cascadeOnDelete' di migrasi
        $siswa->delete();

        return redirect()->route('admin.siswa.index')->with('status', 'Data siswa berhasil dihapus.');
    }
}