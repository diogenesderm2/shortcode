<?php

use App\Http\Controllers\Admin\Cagetories\CategoriesController;
use App\Http\Controllers\Admin\Owner\OwnerController;
use App\Http\Controllers\Admin\Sample\SampleController;
use App\Http\Controllers\Admin\Animal\AnimalController;
use App\Http\Controllers\Admin\User\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Posts\PostController;
use App\Http\Controllers\Admin\Reports\ReportController;

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        // User management routes (temporarily without permissions for testing)
        Route::resource('users', UserController::class);
        
        Route::resource('owners', OwnerController::class);
        Route::get('owners/search/{id}', [OwnerController::class, 'searchById'])->name('owners.search');
        Route::resource('categories', CategoriesController::class);
        Route::resource('animals', AnimalController::class);
        Route::get('animals/{animal}/genetic-results', [AnimalController::class, 'getGeneticResults'])->name('animals.genetic-results');

        // Sample routes
        Route::get('samples/create', [SampleController::class, 'create'])->name('samples.create');
        Route::post('samples', [SampleController::class, 'store'])->name('samples.store');
        Route::get('samples/animals/{owner_id}', [SampleController::class, 'getAnimalsByOwner'])->name('samples.animals');
        Route::post('samples/animals', [SampleController::class, 'storeAnimal'])->name('samples.animals.store');
        Route::post('samples/check-animal', [SampleController::class, 'checkAnimalByRg'])->name('samples.check-animal');
        Route::post('samples/search-animals', [SampleController::class, 'searchAnimalsByName'])->name('samples.search-animals');
        Route::get('samples/debug-animals', [SampleController::class, 'debugAnimals'])->name('samples.debug-animals');
        Route::get('samples/add-to-form', [SampleController::class, 'addToForm'])->name('samples.add-to-form');
        Route::post('samples/search-by-code', [SampleController::class, 'searchByCode'])->name('samples.search-by-code');
        Route::post('samples/generate-form', [SampleController::class, 'generateForm'])->name('samples.generate-form');
        
        // Animal types and breeds routes
        Route::get('animal-types', [SampleController::class, 'getAnimalTypes'])->name('animal-types.index');
        Route::get('breeds/by-type/{animalTypeId}', [SampleController::class, 'getBreedsByType'])->name('breeds.by-type');

        Route::resource('reports', ReportController::class);

        // Novo endpoint para processar o relatório
        Route::post('reports/process', [ReportController::class, 'processReport'])->name('reports.process');

    });
});
