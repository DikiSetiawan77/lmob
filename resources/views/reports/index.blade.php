@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">

            {{-- Menampilkan pesan sukses setelah operasi CRUD --}}
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{-- Judul Halaman dan Tombol Tambah --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Daftar Laporan Harian Saya</h1>
                <a href="{{ route('reports.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Buat Laporan Baru
                </a>
            </div>
            
            {{-- Panel Cetak PDF --}}
            <div class="card mb-4">
                <div class="card-header">Cetak Laporan Bulanan</div>
                <div class="card-body">
                    <p>Pilih periode bulan dan tahun untuk men-generate laporan dalam format PDF.</p>
                    <form action="#" id="printForm" method="GET" target="_blank">
                        <div class="row align-items-end">
                            <div class="col-md-5 form-group">
                                <label for="printMonth">Bulan</label>
                                <select id="printMonth" class="form-control">
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($m)->isoFormat('MMMM') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-5 form-group">
                                 <label for="printYear">Tahun</label>
                                 <select id="printYear" class="form-control">
                                    @for ($y = now()->year; $y >= 2020; $y--)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2 form-group">
                                <button type="submit" class="btn btn-secondary w-100">Cetak PDF</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tabel Daftar Laporan --}}
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Judul Kegiatan</th>
                                <th>Jam</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reports as $report)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($report->date)->isoFormat('dddd, D MMMM Y') }}</td>
                                    <td>{{ $report->title }}</td>
                                    <td>{{ \Carbon\Carbon::parse($report->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($report->end_time)->format('H:i') }}</td>
                                    <td>
                                        <a href="{{ route('reports.edit', $report->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('reports.destroy', $report->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Anda belum membuat laporan harian.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Link Paginasi --}}
                    <div class="d-flex justify-content-center">
                        {{ $reports->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Script untuk membuat URL dinamis untuk form cetak PDF
    document.getElementById('printForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const month = document.getElementById('printMonth').value;
        const year = document.getElementById('printYear').value;
        // Ganti 'reports/print' dengan URL yang benar jika berbeda
        const baseUrl = "{{ url('reports/print') }}";
        const url = `${baseUrl}/${month}/${year}`;
        
        // Buka di tab baru
        window.open(url, '_blank');
    });
</script>
@endpush