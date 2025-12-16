@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="mb-4">Riwayat Saya</h1>

            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('status') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header">
                    {{-- === NAVIGASI TAB YANG DIPERBAIKI === --}}
                    <ul class="nav nav-tabs card-header-tabs" id="historyTab" role="tablist">
                        {{-- Tab 1 (Default Aktif) --}}
                        <li class="nav-item">
                            <a class="nav-link active" id="cuti-sakit-tab-link" data-toggle="tab" href="#cuti-sakit-tab" role="tab" aria-controls="cuti-sakit-tab" aria-selected="true">Cuti & Sakit</a>
                        </li>
                        {{-- Tab 2 --}}
                        <li class="nav-item">
                            <a class="nav-link" id="izin-tab-link" data-toggle="tab" href="#izin-tab" role="tab" aria-controls="izin-tab" aria-selected="false">Izin Jam</a>
                        </li>
                        {{-- Tab 3 --}}
                        <li class="nav-item">
                            <a class="nav-link" id="dl-tab-link" data-toggle="tab" href="#dl-tab" role="tab" aria-controls="dl-tab" aria-selected="false">Dinas Luar</a>
                        </li>
                        {{-- Tab 4 --}}
                        <li class="nav-item">
                            <a class="nav-link" id="terlambat-tab-link" data-toggle="tab" href="#terlambat-tab" role="tab" aria-controls="terlambat-tab" aria-selected="false">Keterlambatan</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    {{-- === KONTEN TAB YANG DIPERBAIKI === --}}
                    <div class="tab-content" id="historyTabContent">
                        
                        {{-- Konten Tab 1: Cuti & Sakit (Default Aktif) --}}
                        <div class="tab-pane fade show active" id="cuti-sakit-tab" role="tabpanel" aria-labelledby="cuti-sakit-tab-link">
                            <h5 class="mb-3">Daftar Pengajuan Cuti & Sakit Anda</h5>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead class="thead-light"><tr><th>Periode</th><th>Tipe</th><th>Status</th><th>Alasan</th><th>Aksi</th></tr></thead>
                                    <tbody>
                                        @forelse ($riwayatCutiSakit as $leave)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($leave->start_date)->isoFormat('D MMM Y') }} - {{ \Carbon\Carbon::parse($leave->end_date)->isoFormat('D MMM Y') }}</td>
                                                <td>{{ ucfirst($leave->type) }}</td>
                                                <td class="text-center">
                                                    @php $statusClass = ['pending'=>'warning', 'approved'=>'success', 'rejected'=>'danger']; @endphp
                                                    <span class="badge badge-{{ $statusClass[$leave->status] ?? 'secondary' }} p-2">{{ ucfirst($leave->status) }}</span>
                                                </td>
                                                <td>
                                                    {{ Str::limit($leave->reason, 40) }}
                                                    @if($leave->status == 'rejected' && $leave->rejection_note)
                                                        <small class="d-block text-danger">Alasan: {{ $leave->rejection_note }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($leave->status == 'pending')
                                                        {{-- Tambahkan tombol hapus di sini, perlu rute & controller --}}
                                                        <small class="text-muted">Menunggu</small>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5" class="text-center">Anda belum pernah mengajukan cuti atau sakit.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{ $riwayatCutiSakit->links() }}
                        </div>

                        {{-- Konten Tab 2: Izin Jam --}}
                        <div class="tab-pane fade" id="izin-tab" role="tabpanel" aria-labelledby="izin-tab-link">
                            <h5 class="mb-3">Daftar Pengajuan Izin Jam Anda</h5>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead class="thead-light"><tr><th>Tanggal</th><th>Tipe</th><th>Jam</th><th>Status</th><th>Keterangan</th><th>Aksi</th></tr></thead>
                                    <tbody>
                                        @forelse ($riwayatIzin as $izin)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($izin->date)->isoFormat('D MMM Y') }}</td>
                                                <td>{{ $izin->izin_type == 'terlambat' ? 'Terlambat' : 'Pulang Cepat' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($izin->allowed_time)->format('H:i') }}</td>
                                                <td class="text-center">
                                                    @php $statusClass = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger']; @endphp
                                                    <span class="badge badge-{{ $statusClass[$izin->status] ?? 'secondary' }} p-2">{{ ucfirst($izin->status) }}</span>
                                                </td>
                                                <td>{{ Str::limit($izin->note, 40) }}</td>
                                                <td>
                                                    @if($izin->status == 'pending')
                                                        <form action="{{ route('izin.destroy', $izin->id) }}" method="POST" onsubmit="return confirm('Batalkan pengajuan izin ini?');">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" title="Batalkan"><i class="fas fa-trash"></i></button>
                                                        </form>
                                                    @else - @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="6" class="text-center">Anda belum pernah mengajukan izin jam.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{ $riwayatIzin->links() }}
                        </div>

                        {{-- Tab 2: Riwayat Dinas Luar --}}
                        <div class="tab-pane fade" id="dl-tab" role="tabpanel">
                            <h5 class="mb-3">Daftar Pengajuan Dinas Luar Anda</h5>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Periode</th>
                                            <th>Tipe</th>
                                            <th>Lokasi</th>
                                            <th>Status</th> {{-- <-- KOLOM BARU --}}
                                            <th>Keterangan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($riwayatDinasLuar as $dl)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($dl->start_date)->isoFormat('D MMM Y') }} - {{ \Carbon\Carbon::parse($dl->end_date)->isoFormat('D MMM Y') }}</td>
                                                <td>{{ ucwords(str_replace('_', ' ', $dl->tipe)) }}</td>
                                                <td>{{ $dl->lokasi_nama }}</td>
                                                <td class="text-center">
                                                    @php
                                                        $statusClass = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'];
                                                    @endphp
                                                    <span class="badge badge-{{ $statusClass[$dl->status] ?? 'secondary' }} p-2">{{ ucfirst($dl->status) }}</span>
                                                </td>
                                                <td>
                                                    {{ Str::limit($dl->note, 40) }}
                                                    @if($dl->status == 'rejected' && $dl->rejection_note)
                                                        <small class="d-block text-danger">Alasan: {{ $dl->rejection_note }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($dl->status == 'pending')
                                                        <form action="{{ route('dinasluar.destroy', $dl->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan ini?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" title="Batalkan"><i class="fas fa-trash"></i></button>
                                                        </form>
                                                    @else
                                                        <span>-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="6" class="text-center">Anda belum pernah mengajukan dinas luar.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{ $riwayatDinasLuar->links() }}
                        </div>
                        {{-- Tab 3: Riwayat Keterlambatan --}}
                        <div class="tab-pane fade" id="terlambat-tab" role="tabpanel">
                            <h5 class="mb-3">Daftar Hari Keterlambatan Anda</h5>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jam Masuk</th>
                                            <th class="text-danger">Terlambat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($riwayatTerlambat as $absen)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($absen->date)->isoFormat('dddd, D MMMM Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($absen->check_in_time)->format('H:i:s') }}</td>
                                                <td class="text-danger font-weight-bold">{{ $absen->late_minutes }} menit</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="3" class="text-center">Selamat! Anda tidak memiliki catatan keterlambatan.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{ $riwayatTerlambat->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection