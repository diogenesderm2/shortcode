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
        
        // === DASHBOARD ===
        Route::get('/', function () {
            return redirect()->route('dashboard');
        })->middleware('permission:view dashboard');
        
        // === USUÁRIOS ===
        Route::middleware('permission:access admin panel')->group(function () {
            Route::get('users', [UserController::class, 'index'])->name('users.index')->middleware('permission:view users');
            Route::get('users/create', [UserController::class, 'create'])->name('users.create')->middleware('permission:create users');
            Route::post('users', [UserController::class, 'store'])->name('users.store')->middleware('permission:create users');
            Route::get('users/{user}', [UserController::class, 'show'])->name('users.show')->middleware('permission:view users');
            Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('permission:edit users');
            Route::put('users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('permission:edit users');
            Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('permission:delete users');
        });
        
        // === PROPRIETÁRIOS ===
        Route::get('owners', [OwnerController::class, 'index'])->name('owners.index')->middleware('permission:view owners');
        Route::get('owners/create', [OwnerController::class, 'create'])->name('owners.create')->middleware('permission:create owners');
        Route::post('owners', [OwnerController::class, 'store'])->name('owners.store')->middleware('permission:create owners');
        Route::get('owners/{owner}', [OwnerController::class, 'show'])->name('owners.show')->middleware('permission:view owners');
        Route::get('owners/{owner}/edit', [OwnerController::class, 'edit'])->name('owners.edit')->middleware('permission:edit owners');
        Route::put('owners/{owner}', [OwnerController::class, 'update'])->name('owners.update')->middleware('permission:edit owners');
        Route::delete('owners/{owner}', [OwnerController::class, 'destroy'])->name('owners.destroy')->middleware('permission:delete owners');
        Route::get('owners/search/{id}', [OwnerController::class, 'searchById'])->name('owners.search')->middleware('permission:view owners');
        
        // === CATEGORIAS ===
        Route::resource('categories', CategoriesController::class)->middleware('permission:view owners');
        
        // === ANIMAIS ===
        Route::get('animals', [AnimalController::class, 'index'])->name('animals.index')->middleware('permission:view animals');
        Route::get('animals/create', [AnimalController::class, 'create'])->name('animals.create')->middleware('permission:create animals');
        Route::post('animals', [AnimalController::class, 'store'])->name('animals.store')->middleware('permission:create animals');
        Route::get('animals/{animal}', [AnimalController::class, 'show'])->name('animals.show')->middleware('permission:view animals');
        Route::get('animals/{animal}/edit', [AnimalController::class, 'edit'])->name('animals.edit')->middleware('permission:edit animals');
        Route::put('animals/{animal}', [AnimalController::class, 'update'])->name('animals.update')->middleware('permission:edit animals');
        Route::delete('animals/{animal}', [AnimalController::class, 'destroy'])->name('animals.destroy')->middleware('permission:delete animals');
        Route::get('animals/{animal}/genetic-results', [AnimalController::class, 'getGeneticResults'])->name('animals.genetic-results')->middleware('permission:view genetic results');

        // === AMOSTRAS ===
        Route::get('samples/create', [SampleController::class, 'create'])->name('samples.create')->middleware('permission:create samples');
        Route::post('samples', [SampleController::class, 'store'])->name('samples.store')->middleware('permission:create samples');
        Route::get('samples/animals/{owner_id}', [SampleController::class, 'getAnimalsByOwner'])->name('samples.animals')->middleware('permission:view samples');
        Route::post('samples/animals', [SampleController::class, 'storeAnimal'])->name('samples.animals.store')->middleware('permission:create animals');
        Route::post('samples/check-animal', [SampleController::class, 'checkAnimalByRg'])->name('samples.check-animal')->middleware('permission:view animals');
        Route::post('samples/search-animals', [SampleController::class, 'searchAnimalsByName'])->name('samples.search-animals')->middleware('permission:view animals');
        Route::get('samples/debug-animals', [SampleController::class, 'debugAnimals'])->name('samples.debug-animals')->middleware('permission:view samples');
        Route::get('samples/add-to-form', [SampleController::class, 'addToForm'])->name('samples.add-to-form')->middleware('permission:view samples');
        Route::post('samples/search-by-code', [SampleController::class, 'searchByCode'])->name('samples.search-by-code')->middleware('permission:view samples');
        Route::post('samples/generate-form', [SampleController::class, 'generateForm'])->name('samples.generate-form')->middleware('permission:create samples');
        Route::post('samples/{sample}/release', [SampleController::class, 'release'])->name('samples.release')->middleware('permission:release genetic results');
        
        // === TIPOS DE ANIMAIS E RAÇAS ===
        Route::get('animal-types', [SampleController::class, 'getAnimalTypes'])->name('animal-types.index')->middleware('permission:view animals');
        Route::get('breeds/by-type/{animalTypeId}', [SampleController::class, 'getBreedsByType'])->name('breeds.by-type')->middleware('permission:view animals');

        // === RELATÓRIOS ===
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index')->middleware('permission:view reports');
        Route::get('reports/create', [ReportController::class, 'create'])->name('reports.create')->middleware('permission:create reports');
        Route::post('reports', [ReportController::class, 'store'])->name('reports.store')->middleware('permission:create reports');
        Route::get('reports/{report}', [ReportController::class, 'show'])->name('reports.show')->middleware('permission:view reports');
        Route::get('reports/{report}/edit', [ReportController::class, 'edit'])->name('reports.edit')->middleware('permission:edit reports');
        Route::put('reports/{report}', [ReportController::class, 'update'])->name('reports.update')->middleware('permission:edit reports');
        Route::delete('reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy')->middleware('permission:delete reports');
        Route::post('reports/process', [ReportController::class, 'processReport'])->name('reports.process')->middleware('permission:create reports');

        // === REVISÃO DE RESULTADOS ===
        Route::get('review', [\App\Http\Controllers\Admin\Review\ReviewController::class, 'index'])->name('review.index')->middleware('permission:review results');
        Route::get('review/{result}', [\App\Http\Controllers\Admin\Review\ReviewController::class, 'show'])->name('review.show')->middleware('permission:review results');
        Route::get('review/sample/{sample}/view', [\App\Http\Controllers\Admin\Review\ReviewController::class, 'showSample'])->name('review.show-sample')->middleware('permission:review results');
        Route::post('review/{result}/status', [\App\Http\Controllers\Admin\Review\ReviewController::class, 'updateStatus'])->name('review.update-status')->middleware('permission:approve results|reject results');
        Route::post('review/bulk-update', [\App\Http\Controllers\Admin\Review\ReviewController::class, 'bulkUpdateSamples'])->name('review.bulk-update')->middleware('permission:approve results|reject results');
        Route::post('review/bulk-update-results', [\App\Http\Controllers\Admin\Review\ReviewController::class, 'bulkUpdateStatus'])->name('review.bulk-update-results')->middleware('permission:approve results|reject results');
        Route::get('review/sample/{sample}', [\App\Http\Controllers\Admin\Review\ReviewController::class, 'getBySample'])->name('review.by-sample')->middleware('permission:review results');
        Route::get('review/sample/{sample}/results', [\App\Http\Controllers\Admin\Review\ReviewController::class, 'getSampleResults'])->name('review.sample-results')->middleware('permission:review results');

    });
});
