@extends('layouts.app')

@push('styles')
{{-- Menambahkan CSS custom di dalam tag <head> melalui @stack --}}
<style>
    /* Mengubah layout utama agar konten di tengah */
    body, #app, .py-4 { background-color: #f8f9fa; }
    .content-wrapper { display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 80vh; text-align: center; }
    .time-display { color: #6c757d; margin-bottom: 2rem; font-size: 1.1rem; }
    .presence-status { margin-bottom: 1rem; }
    .presence-status h3 { font-weight: bold; margin-bottom: 0.25rem; font-size: 1.5rem; }
    .presence-status p { color: #6c757d; }
    .bell-button-wrapper { margin: 2rem 0; }
    .bell-button { width: 130px; height: 130px; border-radius: 50%; background-color: #dc3545; border: none; color: white; display: flex; justify-content: center; align-items: center; box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4); transition: all 0.2s ease-in-out; cursor: pointer; }
    .bell-button:hover:not(:disabled) { transform: scale(1.05); box-shadow: 0 6px 20px rgba(220, 53, 69, 0.5); }
    .bell-button:disabled { background-color: #6c757d; box-shadow: none; cursor: not-allowed; }
    .time-summary { display: flex; justify-content: space-between; width: 100%; max-width: 450px; margin-top: 2rem; }
    .time-block { flex: 1; padding: 0 1rem; }
    .time-block .schedule { font-size: 0.9rem; color: #343a40; font-weight: bold; }
    .time-block .actual-time { border: 2px solid; border-radius: 20px; padding: 0.5rem 1rem; margin-top: 0.5rem; font-weight: bold; font-size: 1.1rem; }
    .time-block .actual-time.check-in { border-color: #28a745; color: #28a745; }
    .time-block .actual-time.check-out { border-color: #dc3545; color: #dc3545; }
    .time-block .actual-time.late { border-color: #dc3545; color: #dc3545; }
    .recap-card { background-color: #e9ecef; border-radius: 10px; }
</style>
@endpush

@section('content')
<div class="content-wrapper">
    
    @php
        $now = \Carbon\Carbon::now('UTC')->addHours(7);
        $statusText = '';
        $jadwalText = '';
        $buttonDisabled = true; // Defaultnya non-aktif
        $isLate = false;

        $jamMasukStandar = \Carbon\Carbon::parse(($today ?? now()->format('Y-m-d')) . ' ' . $jamMasuk);
        $jamPulangStandar = \Carbon\Carbon::parse(($today ?? now()->format('Y-m-d')) . ' ' . $jamPulang);

        $checkInTime = $attendance ? \Carbon\Carbon::parse($attendance->check_in_time) : null;
        $checkOutTime = $attendance && $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time) : null;

        $batasWaktuPulang = $jamPulangStandar;
        if ($izinToday && $izinToday->izin_type == 'pulang_cepat') {
            $batasWaktuPulang = \Carbon\Carbon::parse($izinToday->date . ' ' . $izinToday->allowed_time);
        }

        if ($isTodayHoliday) {
            // Logika ditangani oleh blok @if di bawah
        } elseif ($isOnLeave) {
            $statusText = 'STATUS : Cuti / Sakit';
            $jadwalText = 'Anda tidak perlu melakukan absensi hari ini.';
            $buttonDisabled = true;
        } elseif (!$checkInTime) {
            $statusText = 'PRESENSI : Masuk Kantor';
            $jadwalText = 'Jadwal : ' . $jamMasukStandar->format('H:i:s');
            $buttonDisabled = false;
        } elseif (!$checkOutTime) {
            if ($now->isAfter($batasWaktuPulang)) {
                $statusText = 'PRESENSI : Pulang Kantor';
                $jadwalText = 'Jadwal : ' . $batasWaktuPulang->format('H:i:s');
                $buttonDisabled = false;
            } else {
                $statusText = 'STATUS : Sedang Bekerja';
                $jadwalText = 'Waktu Pulang : ' . $batasWaktuPulang->format('H:i:s');
                $buttonDisabled = true;
            }
        } else {
            $statusText = 'STATUS : Selesai Bekerja';
            $jadwalText = 'Terima kasih untuk hari ini!';
            $buttonDisabled = true;
        }
        $isLate = $attendance && $attendance->late_minutes > 0;
    @endphp

    <div class="time-display">
        <span>{{ $now->isoFormat('dddd, D MMMM Y') }} | <span id="live-clock"></span></span>
    </div>

    @if($isTodayHoliday)
        <div class="presence-status"><h3>HARI LIBUR</h3><p>{{ $holidayName }}</p></div>
        <div class="bell-button-wrapper"><button class="bell-button" disabled><i class="fas fa-calendar-check fa-3x"></i></button></div>
    @elseif($isOnLeave)
        <div class="presence-status"><h3>STATUS : CUTI / SAKIT</h3><p>Anda tidak perlu absensi hari ini.</p></div>
        <div class="bell-button-wrapper"><button class="bell-button" disabled><i class="fas fa-bed fa-3x"></i></button></div>
    @else
        <div class="presence-status"><h3>{{ $statusText }}</h3><p>{{ $jadwalText }}</p></div>
        <div class="bell-button-wrapper"><button id="attendanceButton" class="bell-button" {{ $buttonDisabled ? 'disabled' : '' }}><i class="fas fa-bell fa-3x"></i></button></div>
    @endif
    
    <div id="loading" style="display: none;" class="text-center text-muted"><div class="spinner-border" role="status"></div><p class="mt-2">Memproses...</p></div>
    <div id="error-message" class="alert alert-danger mt-3" style="display: none;"></div>

    <div class="time-summary">
        <div class="time-block">
            <p class="schedule">Masuk Kantor<br>({{ $jamMasukStandar->format('H:i:s') }})</p>
            <div class="actual-time {{ $isLate ? 'late' : 'check-in' }}">{{ $checkInTime ? $checkInTime->format('H:i:s') : '00:00:00' }}</div>
        </div>
        <div class="time-block">
            <p class="schedule">Pulang Kantor<br>({{ $jamPulangStandar->format('H:i:s') }})</p>
            <div class="actual-time check-out">{{ $checkOutTime ? $checkOutTime->format('H:i:s') : '00:00:00' }}</div>
        </div>
    </div>

    <hr style="width: 50%; margin: 2rem 0;">

    <div class="container" style="max-width: 500px;">
        <div class="card recap-card mb-3">
            <div class="card-body">
                <h6 class="card-title text-center font-weight-bold">REKAP BULAN INI</h6>
                <div class="row text-center">
                    <div class="col-6"><div class="font-weight-bold h5">{{ $totalHadir ?? 0 }}</div><small class="text-muted">Hari Hadir</small></div>
                    <div class="col-6"><div class="font-weight-bold h5">{{ $totalTerlambat ?? 0 }}</div><small class="text-muted">Menit Terlambat</small></div>
                </div>
            </div>
        </div>
        <div class="btn-group w-100">
            <a href="{{ route('leave.create') }}" class="btn btn-warning">Ajukan Cuti/Sakit</a>
            <a href="{{ route('dinasluar.create') }}" class="btn btn-success">Ajukan Dinas Luar</a>
            <a href="{{ route('izin.create') }}" class="btn btn-info">Ajukan Izin Jam</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const clockElement = document.getElementById('live-clock');
    function updateClock() {
        const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
        clockElement.textContent = new Date().toLocaleTimeString('id-ID', timeOptions);
    }
    setInterval(updateClock, 1000);
    updateClock();

    const attendanceButton = document.getElementById('attendanceButton');
    const loadingDiv = document.getElementById('loading');
    const errorDiv = document.getElementById('error-message');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    if (attendanceButton) {
        attendanceButton.addEventListener('click', function() {
            if (this.disabled) return;

            const hasCheckedIn = {{ $attendance ? 'true' : 'false' }};
            const url = hasCheckedIn ? "{{ route('attendance.checkout') }}" : "{{ route('attendance.checkin') }}";
            const skipGeofence = {{ $skipGeofenceCheckIn ?? false ? 'true' : 'false' }};

            loadingDiv.style.display = 'block';
            if(attendanceButton) attendanceButton.parentElement.style.display = 'none';
            errorDiv.style.display = 'none';

            if (skipGeofence && !hasCheckedIn) {
                sendAttendanceRequest(url, 0, 0);
            } else {
                if (!navigator.geolocation) {
                    showError('Browser Anda tidak mendukung Geolocation.');
                    return;
                }
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const lat = position.coords.latitude;
                        const long = position.coords.longitude;
                        sendAttendanceRequest(url, lat, long);
                    },
                    (error) => {
                        showError("Gagal mendapatkan lokasi. Pesan: " + error.message);
                    }
                );
            }
        });
    }

    function sendAttendanceRequest(url, lat, long) {
        fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({ lat, long })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.reload();
            } else {
                showError(data.message || 'Terjadi kesalahan yang tidak diketahui.');
            }
        })
        .catch(err => {
            showError("Terjadi kesalahan koneksi. Pastikan Anda terhubung ke internet.");
        });
    }

    function showError(message) {
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
        loadingDiv.style.display = 'none';
        if (attendanceButton) attendanceButton.parentElement.style.display = 'block';
    }
</script>
@endpush