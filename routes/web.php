<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\DashboardController;

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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/upload-resultado', function () {
        return Inertia::render('UploadResultado');
    })->name('upload.resultado');
    
    // Debug route for permissions
    Route::get('/debug/permissions', function () {
        $user = auth()->user();
        return response()->json([
            'user' => $user ? [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames()->toArray(),
                'permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
                'can_review' => $user->can('review results'),
            ] : null,
            'review_permission_exists' => \Spatie\Permission\Models\Permission::where('name', 'review results')->exists(),
        ]);
    })->name('debug.permissions');
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

// Rota de teste temporária para comparação genética
Route::get('/test-genetic', function () {
    $animal = \App\Models\Admin\Animal\Animal::whereHas('samples')->first();
    if (!$animal) {
        return response()->json(['error' => 'Nenhum animal com amostras encontrado']);
    }
    
    $controller = new \App\Http\Controllers\Admin\Animal\AnimalController();
    $response = $controller->getGeneticComparison($animal);
    
    return $response;
});

// Rota de teste para verificar os cálculos
Route::get('/test-calculation', function () {
    $controller = new \App\Http\Controllers\Admin\Animal\AnimalController();
    return $controller->testGeneticCalculation();
});

require_once __DIR__ . '/admin/admin.php';
