<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Models\User;

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

        ResetPassword::createUrlUsing(function ($notifiable, string $token) {
            $front = rtrim(env('FRONTEND_URL', 'http://localhost:5173'), '/');
            $email = urlencode($notifiable->getEmailForPasswordReset());
            return "{$front}/reset-password?token={$token}&email={$email}";
        });
        // GATES

        // verifica se o usuário é admin
        Gate::define('admin', function (?User $user) {
            return $user && $user->role === 'admin';
        });

        Gate::define('rh', function (?User $user) {
            return $user && $user->role === 'rh';
        });
    }
}
