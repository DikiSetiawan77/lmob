@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-0">Admin Dashboard</h1>
            <p class="text-muted">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</p>
        </div>
    </div>

    <!-- Kartu Statistik -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Siswa</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPegawai }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Sudah Absen Hari Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalSudahAbsen }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Siswa Terlambat</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalTerlambat }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- Dua Kolom Utama -->
    <div class="row">
        <div class="col-lg-8">
            <!-- Daftar Sudah Hadir -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-success text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-clipboard-check mr-2"></i>Daftar Kehadiran Hari Ini</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead><tr><th>Nama</th><th>Jam Masuk</th><th>Status</th></tr></thead>
                            <tbody>
                                @forelse($attendancesToday as $att)
                                        <tr>
                                            <td>{{ $att->user->name ?? 'N/A' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($att->check_in_time)->format('H:i:s') }}</td>
                                            <td>
                                                @if($att->late_minutes > 0)
                                                    <span class="badge badge-danger">Terlambat {{ $att->late_minutes }} menit</span>
                                                @else
                                                    <span class="badge badge-success">Tepat Waktu</span>
                                                @endif
                                            </td>
                                        </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted">Belum ada siswa yang absen hari ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <!-- Daftar Belum Hadir -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-danger text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-user-times mr-2"></i>Siswa Belum Hadir (Bukan DL)</h6>
                </div>
                 <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead><tr><th>Nama</th><th>NIP/NIK</th><th style="width: 15%;">Aksi</th></tr></thead>
                            <tbody>
                                @forelse ($belumAbsen as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->nip ?? '-' }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning force-absen-btn" data-toggle="modal" data-target="#forceAbsenModal" data-userid="{{ $user->id }}" data-username="{{ $user->name }}">
                                            Absenkan
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center text-muted">Semua siswa sudah tercatat hadir atau sedang dinas luar.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Dinas Luar & Cuti/Sakit --}}
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-6 bg-info text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-plane-departure mr-2"></i>Siswa Sedang Dinas Luar</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Nama</th><th class="text-right">Aksi</th></tr></thead>
                            <tbody>
                                @forelse($dinasLuarToday as $dl)
                                    <tr>
                                        <td class="align-middle"><strong>{{ $dl->user->name ?? 'N/A' }}</strong><br><small class="text-muted">{{ $dl->lokasi_nama }}</small></td>
                                        <td class="align-middle text-right">
                                            @if (!$attendedUserIds->contains($dl->user_id))
                                                <button class="btn btn-xs btn-warning force-absen-btn" data-toggle="modal" data-target="#forceAbsenModal" data-userid="{{ $dl->user_id }}" data-username="{{ $dl->user->name }}">Absenkan</button>
                                            @else
                                                <span class="badge badge-success">Sudah Absen</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                     <tr><td colspan="2" class="text-center text-muted p-3">Tidak ada siswa yang sedang dinas luar.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            {{-- --- PANEL BARU UNTUK CUTI/SAKIT --- --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-secondary text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-hospital-user mr-2"></i>Siswa Sedang Cuti / Sakit</h6>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($leaveRequestToday as $leave)
                        <div class="list-group-item">
                            <h6 class="mb-0">{{ $leave->user->name ?? 'N/A' }}</h6>
                            <small class="text-muted font-weight-bold">({{ ucfirst($leave->type) }})</small>
                            <small class="d-block text-muted">{{ $leave->reason }}</small>
                        </div>
                    @empty
                         <div class="list-group-item text-center text-muted">Tidak ada siswa yang sedang cuti atau sakit.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Absen Manual (pastikan file ini ada) -->
@include('admin.siswa._modal_force_absen')
@endsection

@push('scripts')
<script>
    // Script untuk mengisi data user ke dalam modal (tetap sama)
    $('#forceAbsenModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var userId = button.data('userid');
        var userName = button.data('username');
        var modal = $(this);
        modal.find('#modalUserName').text(userName);
        modal.find('#modalUserId').val(userId);
    });
</script>
<style>
/* CSS Tambahan untuk mempercantik tampilan kartu */
.card .border-left-primary { border-left: .25rem solid #4e73df!important; }
.card .border-left-success { border-left: .25rem solid #1cc88a!important; }
.card .border-left-info { border-left: .25rem solid #36b9cc!important; }
.card .border-left-warning { border-left: .25rem solid #f6c23e!important; }
.text-gray-300 { color: #dddfeb!important; }
.text-gray-800 { color: #5a5c69!important; }
.font-weight-bold { font-weight: 700!important; }
.text-xs { font-size: .8rem; }
.shadow { box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15)!important; }
</style>
@endpush