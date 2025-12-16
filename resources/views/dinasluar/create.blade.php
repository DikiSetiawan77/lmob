@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Form Pengajuan Dinas Luar') }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('dinasluar.store') }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="tipe">Tipe Dinas Luar</label>
                            <select name="tipe" id="tipe" class="form-control @error('tipe') is-invalid @enderror" required>
                                <option value="">-- Pilih Tipe --</option>
                                <option value="full">Dinas Luar (Full Day)</option>
                                <option value="masuk_kerja_dinasluar">Masuk Kerja, lalu Dinas Luar</option>
                                <option value="dinasluar_masukkerja">Dinas Luar, lalu Masuk Kerja</option>
                            </select>
                            @error('tipe')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="lokasi_nama">Lokasi Dinas Luar (Nama Tempat)</label>
                            <input type="text" name="lokasi_nama" id="lokasi_nama" class="form-control @error('lokasi_nama') is-invalid @enderror" value="{{ old('lokasi_nama') }}" required>
                             @error('lokasi_nama')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="start_date">Waktu Mulai</label>
                            <input type="datetime-local" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" required>
                             @error('start_date')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="end_date">Waktu Selesai</label>
                            <input type="datetime-local" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}" required>
                             @error('end_date')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="note">Keterangan</label>
                            <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror" rows="3" required>{{ old('note') }}</textarea>
                             @error('note')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Ajukan Dinas Luar</button>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection