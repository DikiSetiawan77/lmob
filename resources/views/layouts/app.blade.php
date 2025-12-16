<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'L-Mob') }}</title>
    <link rel="icon" href="{{ asset('images/logo-lmob.png') }}" type="image/png">

    <!-- Styles from CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    

    <!-- Stack for custom page styles -->
    @stack('styles')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    L-Mob
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @auth
                            @if(Auth::user()->role == 'admin')
                                <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard Admin</a></li>
                                <li class="nav-item {{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.siswa.index') }}">Manajemen Siswa</a></li>
                                 <li class="nav-item {{ request()->routeIs('admin.approvals.index') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('admin.approvals.index') }}">Persetujuan</a>
                                </li>
                                <li class="nav-item {{ request()->routeIs('admin.recap.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.recap.index') }}">Rekap Absensi</a></li>
                                <li class="nav-item {{ request()->routeIs('admin.holidays.*') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('admin.holidays.index') }}">Hari Libur</a>
                                </li>
                            @else
                                <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('reports.index') }}">Laporan Harian</a></li>
                                <li class="nav-item {{ request()->routeIs('riwayat.index') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('riwayat.index') }}">Riwayat</a>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        @guest
                            @if (Route::has('login'))<li class="nav-item"><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>@endif
                            @if (Route::has('register'))<li class="nav-item"><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li>@endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fas fa-user-circle mr-1"></i> {{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    @if(Auth::user()->role == 'user')<a class="dropdown-item" href="{{ route('profile.edit') }}">Profil Saya</a>@endif
                                     @if(Auth::user()->role == 'admin')
                                        <a class="dropdown-item" href="{{ route('admin.profile.edit') }}">
                                            Profil & Ubah Password
                                        </a>
                                         <a class="dropdown-item" href="{{ route('admin.tools.backfill.form') }}">Perbaikan Data</a>
                                    @endif
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- Scripts from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    
    <!-- Stack for custom page scripts -->
    @stack('scripts')
</body>
</html>