@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                {{-- PERUBAHAN: $user menjadi $siswa --}}
                <div class="card-header"><h3>Edit Data Siswa: {{ $siswa->name }}</h3></div>
                
                <div class="card-body">
                    <form action="{{ route('admin.siswa.update', $siswa->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="name">Nama Lengkap</label>
                            {{-- PERUBAHAN: $user menjadi $siswa --}}
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $siswa->name) }}" required autofocus>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">Alamat Email</label>
                             {{-- PERUBAHAN: $user menjadi $siswa --}}
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $siswa->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="nip">NIP</label>
                             {{-- PERUBAHAN: $user menjadi $siswa --}}
                            <input type="text" id="nip" name="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip', $siswa->nip) }}" required>
                            @error('nip')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- <div class="form-group mb-3"> 
                            <label for="bidang_unit">Bidang / Unit Kerja</label>
                             {{-- PERUBAHAN: $user menjadi $siswa --}}
                            <input type="text" id="bidang_unit" name="bidang_unit" class="form-control @error('bidang_unit') is-invalid @enderror" value="{{ old('bidang_unit', $siswa->bidang_unit) }}">
                            @error('bidang_unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div> 
                        -->

                        <div class="form-group mb-3">
                        <label for="bidang_unit">Bidang / Unit Kerja</label>
                        {{-- PERUBAHAN: $user menjadi $siswa --}}
                        <select id="bidang_unit" name="bidang_unit" class="form-control @error('bidang_unit') is-invalid @enderror">
                            <option value="bidang kinerja" {{ old('bidang_unit') == 'bidang kinerja' ? 'selected' : '' }}>bidang kinerja</option>
                            <option value="bidang pengembangan aparatur" {{ old('bidang_unit') == 'bidang pengembangan aparatur' ? 'selected' : '' }}>bidang pengembangan aparatur</option>
                            <option value="bidang mutasi" {{ old('bidang_unit') == 'bidang mutasi' ? 'selected' : '' }}>bidang mutasi</option>
                            <option value="bidang keuangan" {{ old('bidang_unit') == 'bidang keuangan' ? 'selected' : '' }}>bidang keuangan</option>
                            <option value="bidang umum" {{ old('bidang_unit') == 'bidang umum' ? 'selected' : '' }}>bidang umum</option>
                            <option value="resepsionis" {{ old('bidang_unit') == 'resepsionis' ? 'selected' : '' }}>resepsionis</option>
                            <option value="bidang pengadaan" {{ old('bidang_unit') == 'bidang pengadaan' ? 'selected' : '' }}>bidang pengadaan</option>
                        </select>
                        @error('bidang_unit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        </div>

                        <hr>
                        <p class="text-muted">Kosongkan password jika tidak ingin mengubahnya.</p>

                        <div class="form-group mb-3">
                            <label for="password">Password Baru</label>
                            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password_confirmation">Konfirmasi Password Baru</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Update Data Siswa</button>
                            <a href="{{ route('admin.siswa.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Script untuk menampilkan nama file di input file Bootstrap
    document.querySelectorAll('.custom-file-input').forEach(function(input) {
        input.addEventListener('change', function(e) {
            var fileName = e.target.files.length > 0 ? e.target.files[0].name : 'Pilih file...';
            var nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
        });
    });
</script>
@endpush