@extends('layouts.landing')

@push('styles')
<style>
    body { font-family: 'Poppins', sans-serif; }
    section { padding: 100px 0; }

    /* Navigasi */
    #navbar { transition: background-color 0.3s ease-in-out, padding 0.3s ease-in-out; }
    #navbar.scrolled { background-color: #fff !important; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 10px 0; }
    #navbar.scrolled .nav-link, #navbar.scrolled .navbar-brand { color: #333 !important; }
    #navbar.scrolled .btn-login { background-color: #ffc107; color: #333; }
    .navbar-brand, .nav-link { color: #fff; font-weight: 500; }
    .btn-login { background-color: #ffc107; color: #333; font-weight: 600; padding: 8px 25px; border-radius: 20px; }

    /* Hero Section */
    #hero {
        background: linear-gradient(45deg, #0056b3, #007bff);
        background-color: #007bff; /* fallback */
        background-image: url('data:image/svg+xml,...'); /* Ganti dengan SVG background jika ada */
        color: #fff;
        padding-top: 150px;
    }
    #hero h1 { font-size: 3.5rem; font-weight: 700; }
    .btn-register { background-color: #fff; color: #007bff; font-weight: 600; padding: 12px 30px; border-radius: 30px; }

    /* Features Section */
    .feature-card { border: none; box-shadow: 0 0 30px rgba(0,0,0,0.08); padding: 30px; border-radius: 15px; text-align: center; }
    .feature-icon { font-size: 3rem; color: #007bff; }
    
    /* Scroll to Top Button */
    .scroll-top-btn { position: fixed; bottom: 20px; right: 20px; background: #007bff; color: #fff; width: 40px; height: 40px; border-radius: 50%; display: none; justify-content: center; align-items: center; text-decoration: none; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
</style>
@endpush

@section('content')

{{-- 1. Hero Section --}}
<section id="hero" class="d-flex align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 d-flex flex-column justify-content-center">
                <h1>L-MOB</h1>
                <h2 class="h4">Aplikasi Laporan berbasis Website yang dibuat untuk laporan siswa PKL/Magang.</h2>
                <div class="mt-4">
                   <a href="{{ route('login') }}" class="btn-register">Login Siswa </a>
                </div>
            </div>
            <div class="col-lg-6">
                {{-- Ganti dengan path ilustrasi Anda --}}
                <img src="{{ asset('images/intro.png') }}" class="img-fluid" alt="About Image">
            </div>
        </div>
    </div>
</section>

{{-- 2. Features Section --}}
<section id="features" class="bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="feature-card">
                    <div class="feature-icon mb-3"><i class="fas fa-database"></i></div>
                    <h4>Database Siswa</h4>
                    <p class="text-muted">Data siswa lebih mudah untuk dikumpulkan dan tidak perlu dikumpulkan ulang jika dibutuhkan.</p>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="feature-card">
                    <div class="feature-icon mb-3"><i class="fas fa-calendar-alt"></i></div>
                    <h4>Kegiatan Harian</h4>
                    <p class="text-muted">Kegiatan harian di input langsung pada hari kerja dan ada batas waktu input.</p>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="feature-card">
                    <div class="feature-icon mb-3"><i class="fas fa-file-invoice"></i></div>
                    <h4>Laporan Akhir</h4>
                    <p class="text-muted">Laporan setiap bulannya sudah otomatis masuk ke akun pimpinan.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 3. About Section --}}
<section id="about">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                {{-- Ganti dengan path ilustrasi Anda --}}
                <img src="{{ asset('images/business-img.png') }}" class="img-fluid" alt="About Image">
            </div>
            <div class="col-lg-6">
                <h2>Tentang L-MOB</h2>
                <p class="text-muted">L-MOB adalah Aplikasi Laporan berbasis Website yang dibuat untuk laporan siswa PKL/Magang. Aplikasi ini sebagai data siswa PKL/Magang jadi suatu saat jika dibutuhkan data siswa PKL/Magang tidak perlu memintanya lagi kepada yang bersangkutan, cukup dengan membuka aplikasi dan download data dari aplikasi L-MOB ini.</p>
                <a href="{{ route('login') }}" class="btn btn-primary mt-3">Login Sekarang</a>
            </div>
        </div>
    </div>
</section>

{{-- 4. Contact Section --}}
<section id="contact" class="bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Kontak</h2>
            <p class="text-muted">Silakan kontak kami jika ada hal yang kiranya perlu kami bantu.</p>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="mb-4">
                    <h5><i class="fas fa-map-marker-alt mr-2 text-primary"></i>Alamat</h5>
                    <p>JJl. Kapten Piere Tandean No. 01<br>Dangdeur, Kec. Subang, Kabupaten Subang, Jawa Barat 41211</p>
                </div>
                <div class="mb-4">
                    <h5><i class="fas fa-phone mr-2 text-primary"></i>Telepon</h5>
                    <p>(0260) 418116</p>
                </div>
                <div class="mb-4">
                    <h5><i class="fas fa-envelope mr-2 text-primary"></i>Email</h5>
                    <p>bkpsdm.subang@gmail.com</p>
                </div>
                {{-- Google Maps Embed --}}
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1981.8822105353431!2d107.74165017320526!3d-6.551399007201625!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e693c4fdafdc80f%3A0xbb0775907e8c707c!2sBKPSDM%20Kabupaten%20Subang!5e0!3m2!1sen!2sid!4v1762834235676!5m2!1sen!2sid" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>            </div>
            <div class="col-lg-6">
                {{-- Ganti dengan path ilustrasi Anda --}}
                <img src="{{ asset('images/01.png') }}" class="img-fluid" alt="About Image">
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // JavaScript untuk mengubah warna navbar saat scroll
    $(window).on('scroll', function() {
        if ($(window).scrollTop() > 50) {
            $('#navbar').addClass('scrolled');
        } else {
            $('#navbar').removeClass('scrolled');
        }
    });

    // JavaScript untuk tombol scroll to top
    $(window).on('scroll', function() {
        if ($(this).scrollTop() > 200) {
            $('#scrollTopBtn').fadeIn();
        } else {
            $('#scrollTopBtn').fadeOut();
        }
    });
    $('#scrollTopBtn').on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({scrollTop: 0}, '300');
    });
</script>
@endpush