<?php

namespace App\Providers;

use App\Models\Akun;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

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
        Cache::remember('akun_filter_options', 3600, function () {
            return Akun::orderBy('nama_akun')->pluck('nama_akun', 'id');
        });
    }
}