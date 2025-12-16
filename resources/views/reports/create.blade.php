@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="mb-0">Buat Laporan Harian Baru</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data">
                        @csrf
                        {{-- Tanggal --}}
                        <div class="form-group mb-3">
                            <label for="date">Tanggal Kegiatan</label>
                            <input type="date" name="date" class="form-control" value="{{ old('date', now()->format('Y-m-d')) }}" required>
                        </div>
                        {{-- Jam Mulai & Selesai --}}
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label for="start_time">Jam Mulai</label>
                                <input type="time" name="start_time" class="form-control" value="{{ old('start_time') }}" required>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="end_time">Jam Selesai</label>
                                <input type="time" name="end_time" class="form-control" value="{{ old('end_time') }}" required>
                            </div>
                        </div>
                        {{-- Judul --}}
                        <div class="form-group mb-3">
                            <label for="title">Judul Kegiatan</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                        </div>

                        {{-- === PERUBAHAN DI SINI === --}}
                        <div class="form-group mb-3">
                            <label for="description">Uraian Tugas (Minimal 10 karakter)</label>
                            {{-- Tambahkan id="description" pada textarea --}}
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="8" required>{{ old('description') }}</textarea>
                            {{-- Tambahkan elemen untuk counter dengan id="charCounter" --}}
                            <small id="charCounter" class="form-text text-muted float-right">0 / 10 karakter</small>
                            @error('description')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        {{-- === AKHIR PERUBAHAN === --}}

                        {{-- Upload Foto --}}
                        <div class="form-group mb-3">
                            <label for="photos">Upload Foto (Opsional, bisa lebih dari satu)</label>
                            <input type="file" name="photos[]" class="form-control" multiple>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Laporan</button>
                        <a href="{{ route('reports.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- === TAMBAHKAN SCRIPT INI === --}}
<script>
    // Ambil elemen textarea dan counter
    const descriptionTextarea = document.getElementById('description');
    const charCounter = document.getElementById('charCounter');
    const minChars = 50;

    // Buat fungsi untuk mengupdate counter
    const updateCounter = () => {
        const currentLength = descriptionTextarea.value.length;
        
        // Update teks counter
        charCounter.textContent = `${currentLength} / ${minChars} karakter`;

        // Ubah warna teks berdasarkan jumlah karakter
        if (currentLength >= minChars) {
            charCounter.classList.remove('text-muted', 'text-danger');
            charCounter.classList.add('text-success');
        } else {
            charCounter.classList.remove('text-success');
            charCounter.classList.add('text-muted');
        }
    };

    // Panggil fungsi saat ada input di textarea
    descriptionTextarea.addEventListener('input', updateCounter);

    // Panggil fungsi sekali saat halaman dimuat, untuk menangani data 'old()' jika ada
    updateCounter();
</script>
@endpush