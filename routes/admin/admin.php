<?php

use App\Http\Controllers\Admin\Cagetories\CategoriesController;
use App\Http\Controllers\Admin\Owner\OwnerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Posts\PostController;


Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('owner', OwnerController::class);
        Route::resource('categories', CategoriesController::class);
        Route::resource('posts', PostController::class);
    });
});
