@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Manajemen Persetujuan</h1>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- KARTU PENGAJUAN TERTUNDA --}}
    <div class="card shadow-sm mb-5">
        <div class="card-header bg-warning">
            <h5 class="mb-0"><i class="fas fa-clock mr-2"></i>Pengajuan Tertunda</h5>
        </div>
        <div class="card-body">
            
            {{-- Bagian Cuti & Sakit --}}
            <h6 class="font-weight-bold">Pengajuan Cuti & Sakit</h6>
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Nama Siswa</th>
                            <th>Tipe</th>
                            <th>Periode</th>
                            <th>Lampiran</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingLeaveRequests as $leave)
                            <tr>
                                <td>{{ $leave->user->name ?? 'N/A' }}</td>
                                <td>{{ ucfirst($leave->type) }}</td>
                                <td>{{ \Carbon\Carbon::parse($leave->start_date)->isoFormat('D MMM Y') }} - {{ \Carbon\Carbon::parse($leave->end_date)->isoFormat('D MMM Y') }}</td>
                                <td>
                                    @if($leave->attachment_path)
                                        <a href="{{ route('admin.approvals.leave.attachment', $leave->id) }}" target="_blank">Lihat</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.approvals.leave.process', $leave->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="btn btn-sm btn-success">Setujui</button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#rejectLeaveModal{{ $leave->id }}">Tolak</button>
                                </td>
                            </tr>
                        @empty
                             <tr><td colspan="5" class="text-center text-muted">Tidak ada pengajuan cuti/sakit yang tertunda.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div> {{-- <-- TAG DIV PENUTUP YANG HILANG --}}

            <hr>
            <h6 class="font-weight-bold">Pengajuan Izin</h6>
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Nama Siswa</th>
                            <th>Tanggal Izin</th>
                            <th>Tipe</th>
                            <th>Keterangan</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pendingIzins as $izin)
                            <tr>
                                <td>{{ $izin->user->name ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($izin->date)->isoFormat('D MMM Y') }} ({{ \Carbon\Carbon::parse($izin->allowed_time)->format('H:i') }})</td>
                                <td>{{ $izin->izin_type == 'terlambat' ? 'Izin Terlambat' : 'Izin Pulang Cepat' }}</td>
                                <td>{{ $izin->note }}</td>
                                <td>
                                    <form action="{{ route('admin.approvals.izin.process', $izin->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="btn btn-sm btn-success">Setujui</button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#rejectIzinModal{{ $izin->id }}">
                                        Tolak
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted">Tidak ada pengajuan izin yang tertunda.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <hr>

            <h6 class="font-weight-bold mt-4">Pengajuan Dinas Luar</h6>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Nama Siswa</th>
                            <th>Periode</th>
                            <th>Tipe</th>
                            <th>Lokasi</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                         @forelse ($pendingDinasLuars as $dl)
                            <tr>
                                <td>{{ $dl->user->name ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($dl->start_date)->isoFormat('D MMM Y H:i') }} s/d {{ \Carbon\Carbon::parse($dl->end_date)->isoFormat('D MMM Y H:i') }}</td>
                                <td>{{ ucwords(str_replace('_', ' ', $dl->tipe)) }}</td>
                                <td>{{ $dl->lokasi_nama }}</td>
                                <td>
                                    <form action="{{ route('admin.approvals.dinasluar.process', $dl->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="btn btn-sm btn-success">Setujui</button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#rejectDlModal{{ $dl->id }}">
                                        Tolak
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted">Tidak ada pengajuan dinas luar yang tertunda.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    {{-- KARTU RIWAYAT PERSETUJUAN --}}
    <div class="card shadow-sm">
            <div class="card-header bg-success">
                <h5 class="mb-0"><i class="fas fa-history mr-2"></i>Riwayat Persetujuan (Terbaru)</h5>
            </div>
            <div class="card-body">
                @php
                    $historyIzins = \App\Models\Izin::where('status', '!=', 'pending')->with(['user', 'approver'])->latest()->take(5)->get();
                    $historyDinasLuars = \App\Models\DinasLuar::where('status', '!=', 'pending')->with(['user', 'approver'])->latest()->take(5)->get();
                    // --- MENGAMBIL DATA RIWAYAT CUTI/SAKIT ---
                    $historyLeaveRequests = \App\Models\LeaveRequest::where('status', '!=', 'pending')->with(['user', 'approver'])->latest()->take(5)->get();
                @endphp
                
                <h6 class="font-weight-bold">Riwayat Cuti & Sakit</h6>
                <table class="table table-sm table-striped">
                    <thead><tr><th>Siswa</th><th>Periode</th><th>Status</th><th>Diproses Oleh</th></tr></thead>
                    <tbody>
                        @forelse ($historyLeaveRequests as $leave)
                            <tr>
                                <td>{{ $leave->user->name ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($leave->start_date)->isoFormat('D MMM Y') }}</td>
                                <td>
                                    @if($leave->status == 'approved')<span class="badge badge-success">Disetujui</span>
                                    @else<span class="badge badge-danger">Ditolak</span>@endif
                                </td>
                                <td>{{ $leave->approver->name ?? 'Admin Sistem' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">Belum ada riwayat.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <hr>
            <h6 class="font-weight-bold">Riwayat Izin</h6>
            <table class="table table-sm table-striped">
                <thead><tr><th>Siswa</th><th>Tanggal</th><th>Status</th><th>Diproses Oleh</th></tr></thead>
                <tbody>
                    @forelse ($historyIzins as $izin)
                        <tr>
                            <td>{{ $izin->user->name ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($izin->date)->isoFormat('D MMM Y') }}</td>
                            <td>
                                @if($izin->status == 'approved')
                                    <span class="badge badge-success">Disetujui</span>
                                @else
                                    <span class="badge badge-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>{{ $izin->approver->name ?? 'Admin Sistem' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted">Belum ada riwayat.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <hr>
            <h6 class="font-weight-bold mt-4">Riwayat Dinas Luar</h6>
            <table class="table table-sm table-striped">
                <thead><tr><th>Siswa</th><th>Periode</th><th>Status</th><th>Diproses Oleh</th></tr></thead>
                 <tbody>
                    @forelse ($historyDinasLuars as $dl)
                        <tr>
                            <td>{{ $dl->user->name ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($dl->start_date)->isoFormat('D MMM Y') }}</td>
                            <td>
                                @if($dl->status == 'approved')
                                    <span class="badge badge-success">Disetujui</span>
                                @else
                                    <span class="badge badge-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>{{ $dl->approver->name ?? 'Admin Sistem' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted">Belum ada riwayat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL UNTUK MENOLAK IZIN --}}
@foreach ($pendingIzins as $izin)
<div class="modal fade" id="rejectIzinModal{{ $izin->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Tolak Pengajuan Izin</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <form action="{{ route('admin.approvals.izin.process', $izin->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="status" value="rejected">
                    <div class="form-group">
                        <label for="rejection_note">Alasan Penolakan (Wajib diisi)</label>
                        <textarea name="rejection_note" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Kirim Penolakan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

{{-- MODAL UNTUK MENOLAK DINAS LUAR --}}
@foreach ($pendingDinasLuars as $dl)
<div class="modal fade" id="rejectDlModal{{ $dl->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
             <div class="modal-header"><h5 class="modal-title">Tolak Pengajuan Dinas Luar</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <form action="{{ route('admin.approvals.dinasluar.process', $dl->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="status" value="rejected">
                    <div class="form-group">
                        <label for="rejection_note">Alasan Penolakan (Wajib diisi)</label>
                        <textarea name="rejection_note" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Kirim Penolakan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection