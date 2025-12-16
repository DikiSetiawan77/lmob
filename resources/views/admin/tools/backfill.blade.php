@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header"><h4><i class="fas fa-wrench mr-2"></i>Perbaikan Data Absensi (Tambal Sulam)</h4></div>
                <div class="card-body">
                    @if (session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif

                    <div class="alert alert-warning">
                        <strong>Perhatian!</strong> Fitur ini akan menambahkan data absensi "HADIR" pada hari kerja yang kosong hingga siswa mencapai target hari kerja yang Anda tentukan.
                    </div>
                    
                    <form action="{{ route('admin.tools.backfill.process') }}" method="POST" onsubmit="return confirm('Anda yakin ingin menjalankan proses ini? Aksi ini tidak dapat diurungkan.');">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="month">Pilih Bulan</label>
                                <select name="month" id="month" class="form-control" required>
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ $m == now()->subMonth()->month ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->isoFormat('MMMM') }}</option>
                                    @endfor
                                </select>
                            </div>
                             <div class="col-md-4 form-group">
                                <label for="year">Pilih Tahun</label>
                                <select name="year" id="year" class="form-control" required>
                                     @for ($y = now()->year; $y >= 2020; $y--)
                                        <option value="{{ $y }}" {{ $y == now()->subMonth()->year ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            {{-- --- INPUT BARU DI SINI --- --}}
                            <div class="col-md-4 form-group">
                                <label for="target_days">Target Hari Kerja</label>
                                <input type="number" name="target_days" id="target_days" class="form-control" placeholder="Contoh: 21" required>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-danger">Jalankan Proses</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection