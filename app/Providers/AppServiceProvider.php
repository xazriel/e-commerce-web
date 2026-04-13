<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Request;

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
        /**
         * Fix untuk Ngrok: 
         * Memaksa semua URL menggunakan HTTPS jika diakses melalui Ngrok.
         * Ini akan memperbaiki masalah CSS tidak terbaca (Mixed Content).
         */
        if (str_contains(Request::header('host'), 'ngrok-free.dev') || Request::header('x-forwarded-proto') === 'https') {
            URL::forceScheme('https');
        }
    }
}