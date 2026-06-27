<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Fix for "Specified key was too long" error
        Schema::defaultStringLength(191);
    }

    public function register(): void
    {
        //
    }
}
