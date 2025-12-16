@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="mb-0">Edit Laporan Harian</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('reports.update', $report->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') {{-- Jangan lupa method PUT untuk update --}}

                        {{-- (Form field lain sama seperti create.blade.php, tapi dengan value dari $report) --}}
                        {{-- ... --}}
                        <div class="form-group mb-3">
                            <label for="date">Tanggal Kegiatan</label>
                            <input type="date" name="date" class="form-control" value="{{ old('date', $report->date) }}" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label for="start_time">Jam Mulai</label>
                                <input type="time" name="start_time" class="form-control" value="{{ old('start_time', $report->start_time) }}" required>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="end_time">Jam Selesai</label>
                                <input type="time" name="end_time" class="form-control" value="{{ old('end_time', $report->end_time) }}" required>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="title">Judul Kegiatan</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $report->title) }}" required>
                        </div>

                        {{-- === PERUBAHAN DI SINI === --}}
                        <div class="form-group mb-3">
                            <label for="description">Uraian Tugas (Minimal 50 karakter)</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="8" required>{{ old('description', $report->description) }}</textarea>
                            <small id="charCounter" class="form-text text-muted float-right">0 / 50 karakter</small>
                            @error('description')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        {{-- === AKHIR PERUBAHAN === --}}

                        <button type="submit" class="btn btn-primary">Update Laporan</button>
                        <a href="{{ route('reports.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- === SCRIPTNYA SAMA PERSIS DENGAN CREATE === --}}
<script>
    const descriptionTextarea = document.getElementById('description');
    const charCounter = document.getElementById('charCounter');
    const minChars = 50;

    const updateCounter = () => {
        const currentLength = descriptionTextarea.value.length;
        charCounter.textContent = `${currentLength} / ${minChars} karakter`;
        if (currentLength >= minChars) {
            charCounter.classList.remove('text-muted');
            charCounter.classList.add('text-success');
        } else {
            charCounter.classList.remove('text-success');
            charCounter.classList.add('text-muted');
        }
    };

    descriptionTextarea.addEventListener('input', updateCounter);
    
    // Panggil saat halaman dimuat untuk menghitung karakter yang sudah ada
    updateCounter();
</script>
@endpush