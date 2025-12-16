@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Form Pengajuan Izin') }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('izin.store') }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="izin_type">Tipe Izin</label>
                            <select name="izin_type" id="izin_type" class="form-control @error('izin_type') is-invalid @enderror" required>
                                <option value="terlambat">Izin Terlambat Masuk</option>
                                <option value="pulang_cepat">Izin Pulang Cepat</option>
                            </select>
                            @error('izin_type')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="date">Tanggal Izin</label>
                            <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date') }}" required>
                             @error('date')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="allowed_time">Jam yang Diizinkan</label>
                            <input type="time" name="allowed_time" id="allowed_time" class="form-control @error('allowed_time') is-invalid @enderror" value="{{ old('allowed_time') }}" required>
                             @error('allowed_time')
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

                        <button type="submit" class="btn btn-primary">Ajukan Izin</button>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection