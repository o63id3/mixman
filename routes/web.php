<?php

declare(strict_types=1);

use App\Http\Controllers\CardsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegionsController;
use App\Http\Controllers\SellersController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/sellers', [SellersController::class, 'index'])->name('sellers.index');

    Route::get('/sellers/create', [SellersController::class, 'create'])->name('sellers.create');
    Route::post('/sellers', [SellersController::class, 'store'])->name('sellers.store');

    Route::get('/sellers/{seller}/edit', [SellersController::class, 'edit'])->name('sellers.edit');
    Route::patch('/sellers/{seller}', [SellersController::class, 'update'])->name('sellers.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/regions', [RegionsController::class, 'index'])->name('regions.index');

    Route::get('/regions/create', [RegionsController::class, 'create'])->name('regions.create');
    Route::post('/regions', [RegionsController::class, 'store'])->name('regions.store');

    Route::get('/regions/{region}/edit', [RegionsController::class, 'edit'])->name('regions.edit');
    Route::patch('/regions/{region}', [RegionsController::class, 'update'])->name('regions.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/cards', [CardsController::class, 'index'])->name('cards.index');

    Route::get('/cards/create', [CardsController::class, 'create'])->name('cards.create');
    Route::post('/cards', [CardsController::class, 'store'])->name('cards.store');

    Route::get('/cards/{card}/edit', [CardsController::class, 'edit'])->name('cards.edit');
    Route::patch('/cards/{card}', [CardsController::class, 'update'])->name('cards.update');
});
