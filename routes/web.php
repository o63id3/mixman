<?php

declare(strict_types=1);

use App\Http\Controllers\AdminsController;
use App\Http\Controllers\CardsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeactivatedUsersController;
use App\Http\Controllers\OrderItemsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegionsController;
use App\Http\Controllers\SellerOrdersController;
use App\Http\Controllers\SellersController;
use App\Http\Controllers\TransactionsController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::post('/users/{user}', [DeactivatedUsersController::class, 'store'])->name('users.deactivate');
    Route::delete('/users/{user}', [DeactivatedUsersController::class, 'destroy'])->name('users.activate');
});

Route::middleware('auth')->group(function () {
    Route::get('/sellers', [SellersController::class, 'index'])->name('sellers.index');

    Route::get('/sellers/create', [SellersController::class, 'create'])->name('sellers.create');
    Route::post('/sellers', [SellersController::class, 'store'])->name('sellers.store');

    Route::get('/sellers/{seller}/edit', [SellersController::class, 'edit'])->name('sellers.edit');
    Route::patch('/sellers/{seller}', [SellersController::class, 'update'])->name('sellers.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/admins', [AdminsController::class, 'index'])->name('admins.index');

    Route::get('/admins/create', [AdminsController::class, 'create'])->name('admins.create');
    Route::post('/admins', [AdminsController::class, 'store'])->name('admins.store');

    Route::get('/admins/{admin}/edit', [AdminsController::class, 'edit'])->name('admins.edit');
    Route::patch('/admins/{admin}', [AdminsController::class, 'update'])->name('admins.update');

    Route::delete('/admins/{admin}', [AdminsController::class, 'destroy'])->name('admins.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/regions', [RegionsController::class, 'index'])->name('regions.index');

    Route::get('/regions/create', [RegionsController::class, 'create'])->name('regions.create');
    Route::post('/regions', [RegionsController::class, 'store'])->name('regions.store');

    Route::get('/regions/{region}/edit', [RegionsController::class, 'edit'])->name('regions.edit');
    Route::patch('/regions/{region}', [RegionsController::class, 'update'])->name('regions.update');

    Route::delete('/regions/{region}', [RegionsController::class, 'destroy'])->name('regions.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/cards', [CardsController::class, 'index'])->name('cards.index');

    Route::get('/cards/create', [CardsController::class, 'create'])->name('cards.create');
    Route::post('/cards', [CardsController::class, 'store'])->name('cards.store');

    Route::get('/cards/{card}/edit', [CardsController::class, 'edit'])->name('cards.edit');
    Route::patch('/cards/{card}', [CardsController::class, 'update'])->name('cards.update');

    Route::delete('/cards/{card}', [CardsController::class, 'destroy'])->name('cards.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/orders', [OrdersController::class, 'index'])->name('orders.index');

    Route::get('/orders/create', [OrdersController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrdersController::class, 'store'])->name('orders.store');

    Route::get('/orders/{order}/edit', [OrdersController::class, 'edit'])->name('orders.edit');
    Route::patch('/orders/{order}', [OrdersController::class, 'update'])->name('orders.update');

    Route::delete('/orders/{order}', [OrdersController::class, 'destroy'])->name('orders.destroy');
});

Route::middleware('auth')->group(function () {
    Route::post('/orders/{order}/items', [OrderItemsController::class, 'store'])->name('order-items.store');

    Route::delete('/orders-items/{item}', [OrderItemsController::class, 'destroy'])->name('order-items.destroy');
});

Route::middleware('auth')->group(function () {
    Route::middleware(['throttle:seller-orders'])->post('/seller/orders', SellerOrdersController::class)->name('seller-orders.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/payments', [PaymentsController::class, 'index'])->name('payments.index');

    Route::get('/payments/create', [PaymentsController::class, 'create'])->name('payments.create');
    Route::post('/payments', [PaymentsController::class, 'store'])->name('payments.store');

    Route::get('/payments/{payment}/edit', [PaymentsController::class, 'edit'])->name('payments.edit');
    Route::patch('/payments/{payment}', [PaymentsController::class, 'update'])->name('payments.update');

    Route::delete('/payments/{payment}', [PaymentsController::class, 'destroy'])->name('payments.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/transactions', TransactionsController::class)->name('transactions.index');
});
