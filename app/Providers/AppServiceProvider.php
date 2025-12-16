<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set locale Carbon ke Bahasa Indonesia
        \Carbon\Carbon::setLocale('id');

        // Set timezone default untuk seluruh aplikasi
        date_default_timezone_set(config('app.timezone', 'Asia/Jakarta'));
        Paginator::useBootstrapFour();
    }
}
