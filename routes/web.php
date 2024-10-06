<?php

declare(strict_types=1);

use App\Http\Controllers\CardsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeactivatedNetworksController;
use App\Http\Controllers\DeactivatedUsersController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\FileUploadsController;
use App\Http\Controllers\NetworkManagersController;
use App\Http\Controllers\NetworkPartnersController;
use App\Http\Controllers\NetworksController;
use App\Http\Controllers\OrderFileDownloadsController;
use App\Http\Controllers\OrderFilesController;
use App\Http\Controllers\OrderItemsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellerOrdersController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware('auth')->resource('users', UsersController::class)->except('show');
Route::middleware('auth')->resource('networks', NetworksController::class)->except('delete');
Route::middleware('auth')->resource('cards', CardsController::class)->except('show');
Route::middleware('auth')->resource('orders', OrdersController::class);
Route::middleware('auth')->resource('payments', PaymentsController::class);
Route::middleware('auth')->resource('expenses', ExpensesController::class);

Route::middleware('auth')->group(function () {
    Route::post('/users/{user}', [DeactivatedUsersController::class, 'store'])->name('users.deactivate');
    Route::delete('/users/{user}', [DeactivatedUsersController::class, 'destroy'])->name('users.activate');
});

Route::middleware('auth')->group(function () {
    Route::post('/networks/{network}', [DeactivatedNetworksController::class, 'store'])->name('networks.deactivate');
    Route::delete('/networks/{network}', [DeactivatedNetworksController::class, 'destroy'])->name('networks.activate');
});

Route::middleware('auth')->group(function () {
    Route::post('/networks/{network}/managers/{user}', [NetworkManagersController::class, 'store'])->name('network.managers.store');
    Route::get('/networks/{network}/partners', [NetworkPartnersController::class, 'create'])->name('network.partners.create');
    Route::post('/networks/{network}/partners', [NetworkPartnersController::class, 'store'])->name('network.partners.store');
    Route::delete('/networks/{network}/partners/{partner}', [NetworkPartnersController::class, 'destroy'])->name('network.partners.destroy');
});

Route::middleware('auth')->group(function () {
    Route::post('/orders/{order}/items', [OrderItemsController::class, 'store'])->name('order-items.store');
    Route::delete('/order-items/{item}', [OrderItemsController::class, 'destroy'])->name('order-items.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/order-files/{file}', OrderFileDownloadsController::class)->name('order-files.download');

    Route::post('/orders/{order}/files', [OrderFilesController::class, 'store'])->name('order-files.store');
    Route::delete('/order-files/{file}', [OrderFilesController::class, 'destroy'])->name('order-files.destroy');
});

Route::middleware('auth')->group(function () {
    Route::middleware(['throttle:seller-orders'])->post('/seller/orders', SellerOrdersController::class)->name('seller-orders.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/transactions', TransactionsController::class)->name('transactions.index');
});

Route::middleware('auth')->post('/upload', [FileUploadsController::class, 'store'])->name('upload');
