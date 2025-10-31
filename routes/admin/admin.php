<?php

use App\Http\Controllers\Admin\Cagetories\CategoriesController;
use App\Http\Controllers\Admin\Owner\OwnerController;
use App\Http\Controllers\Admin\Sample\SampleController;
use App\Http\Controllers\Admin\Animal\AnimalController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Posts\PostController;
use App\Http\Controllers\Admin\Reports\ReportController;

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('owner', OwnerController::class);
        Route::resource('categories', CategoriesController::class);
        Route::resource('animals', AnimalController::class);

        // Sample routes
        Route::get('samples/create', [SampleController::class, 'create'])->name('samples.create');
        Route::post('samples', [SampleController::class, 'store'])->name('samples.store');
        Route::get('samples/animals/{owner_id}', [SampleController::class, 'getAnimalsByOwner'])->name('samples.animals');
        Route::post('samples/animals', [SampleController::class, 'storeAnimal'])->name('samples.animals.store');
        Route::post('samples/check-animal', [SampleController::class, 'checkAnimalByRg'])->name('samples.check-animal');

        Route::resource('reports', ReportController::class);

        // Novo endpoint para processar o relatório
        Route::post('reports/process', [ReportController::class, 'processReport'])->name('reports.process');

    });
});
