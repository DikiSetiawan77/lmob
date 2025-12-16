@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header">
            {{-- --- PERBAIKAN DI SINI --- --}}
            Rekapitulasi Absensi - {{ $monthName }} {{ $year }}
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.recap.index') }}" class="form-inline mb-3">
                <select name="month" class="form-control mr-2">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->isoFormat('MMMM') }}
                        </option>
                    @endfor
                </select>
                <select name="year" class="form-control mr-2">
                    @for ($y = now()->year; $y >= 2020; $y--)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.recap.export', ['month' => $month, 'year' => $year]) }}" class="btn btn-success ml-auto">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Nama Siswa</th>
                            <th>NIP/NIK</th>
                            <th>Total Hadir</th>
                            <th>Total Terlambat</th>
                            <th>Dinas Luar</th>
                            <th>Izin</th>
                            <th>Cuti/Sakit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recapData as $data)
                            <tr>
                                <td>{{ $data['name'] }}</td>
                                <td>{{ $data['nip'] }}</td>
                                <td>{{ $data['total_hadir'] }} hari</td>
                                <td>{{ $data['total_terlambat_menit'] }} menit</td>
                                <td>{{ $data['total_dinas_luar'] }} hari</td>
                                <td>{{ $data['total_izin'] }} kali</td>
                                <td>{{ $data['total_cuti_sakit'] }} hari</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection