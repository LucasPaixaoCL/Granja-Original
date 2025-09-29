<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
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
        // GATES

        // verifica se o usuário é admin
        Gate::define('admin', function () {
            return auth()->user()->role === 'admin';
        });

        Gate::define('rh', function () {
            return auth()->user()->role === 'rh';
        });
    }
}
