<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'L-Mob') }} - Aplikasi Absensi & Laporan</title>
    <link rel="icon" href="{{ asset('images/logo-lmob.png') }}" type="image/png">

    {{-- Bootstrap & Font Awesome dari CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Custom CSS akan kita taruh di sini --}}
    @stack('styles')
</head>
<body>
    
    {{-- Navigasi --}}
    <nav id="navbar" class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('images/logo-lmob.png') }}" width="40" height="40" alt="L-Mob Logo">
                <span class="font-weight-bold ml-2">L-Mob</span>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="#hero">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#features">Fitur</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Kontak</a></li>
                    <li class="nav-item ml-lg-3"><a class="btn btn-login" href="{{ route('login') }}">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Konten Halaman --}}
    @yield('content')

    {{-- Footer --}}
    <footer class="text-center py-4">
        <p class="mb-0">&copy; {{ date('Y') }} L-Mob. All Rights Reserved.</p>
    </footer>

    {{-- Tombol Scroll to Top --}}
    <a href="#" id="scrollTopBtn" class="scroll-top-btn"><i class="fas fa-arrow-up"></i></a>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

</body>
</html>