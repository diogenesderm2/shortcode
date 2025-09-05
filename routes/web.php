<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\GoogleController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

use Santander\Token\Token;
Route::get('santander', function () {
    $crt = storage_path('app/cert/certificate.crt');
    $key = storage_path('app/cert/private.key');

    $token = new Token();
    return $token->getToken();
});

Route::get('auth/google', [GoogleController::class, 'redirectToProvider'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleProviderCallback']);
require_once __DIR__ . '/admin/admin.php';
