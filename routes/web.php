<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CsrfCookieController;

Route::get('/sanctum/csrf-cookie', [CsrfCookieController::class, 'show'])
    ->name('api.csrf-cookie');