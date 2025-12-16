@extends('layouts.app')

@push('styles')
<style>
    .navbar { display: none !important; }
    body { background-color: #f0f2f5; }
    .card { border-radius: 1rem; }
    .rounded-left-md {
        border-top-left-radius: 1rem;
        border-bottom-left-radius: 1rem;
    }
</style>
@endpush

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-9 col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="row g-0">
                    {{-- Blue Panel --}}
                    <div class="col-md-6 bg-primary text-white d-none d-md-flex flex-column justify-content-center align-items-center p-5 rounded-left-md">
                        <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; justify-content: center; align-items: center;" class="mb-4">
                            <i class="fas fa-clipboard-check fa-2x"></i>
                        </div>
                        <h2 class="font-weight-bold">L-Mob</h2>
                        <p class="text-center small mt-2">Aplikasi Absensi dan Laporan Mobile Siswa</p>
                    </div>
                    
                    {{-- Login Form --}}
                    <div class="col-md-6 d-flex flex-column justify-content-center p-5">
                        <div class="text-center mb-4">
                            <img src="{{ asset('images/logo-lmob.png') }}" alt="Logo L-Mob" style="width: 60px; height: auto;">
                            <h3 class="font-weight-bold mt-3">Login Panel</h3>
                            <p class="text-muted small">Selamat datang kembali!</p>
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="email" class="small font-weight-bold">Username</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user"></i></span></div>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Masukkan email">
                                </div>
                                @error('email')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label for="password" class="small font-weight-bold">Password</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-lock"></i></span></div>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Masukkan password">
                                </div>
                                @error('password')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary btn-block btn-lg">Login</button>
                        </form>

                        <div class="text-center mt-4">
                            <a href="{{ url('/') }}" class="text-muted small"><i class="fas fa-arrow-left"></i> Kembali ke Beranda</a>
                        </div>
                        <p class="text-center text-muted mt-3" style="font-size: 0.8rem;">&copy; {{ date('Y') }} L-Mob. Hak Cipta Dilindungi.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection