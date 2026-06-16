<?php

use App\Http\Controllers\OffreController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('/offres')->name('offres.')->group(function () {
        Route::get('/', [OffreController::class, 'index'])->name('index');
        Route::get('/create', [OffreController::class, 'create'])->name('create');
        Route::post('/', [OffreController::class, 'store'])->name('store');
        Route::get('/{offre}', [OffreController::class, 'show'])->name('show');
        Route::get('/{offre}/edit', [OffreController::class, 'edit'])->name('edit');
        Route::put('/{offre}', [OffreController::class, 'update'])->name('update');
        Route::delete('/{offre}', [OffreController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__.'/auth.php';
