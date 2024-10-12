<?php

declare(strict_types=1);

use App\Http\Controllers\CardsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeactivatedCardsController;
use App\Http\Controllers\DeactivatedNetworksController;
use App\Http\Controllers\DeactivatedUsersController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\FileUploadsController;
use App\Http\Controllers\NetworkManagersController;
use App\Http\Controllers\NetworkPartnersController;
use App\Http\Controllers\NetworksController;
use App\Http\Controllers\OrderCardsController;
use App\Http\Controllers\OrderFileDownloadsController;
use App\Http\Controllers\OrderFilesController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\UserOrdersController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WeeklyReportPdfGeneratorController;
use App\Http\Controllers\WeeklyReportsController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::middleware('auth')->get('/', DashboardController::class)->name('dashboard');
Route::middleware('auth')->resource('users', UsersController::class)->except('show');
Route::middleware('auth')->resource('networks', NetworksController::class)->except('delete');
Route::middleware('auth')->resource('network.partners', NetworkPartnersController::class)->only(['create', 'store', 'destroy']);
Route::middleware('auth')->post('/networks/{network}/managers/{user}', [NetworkManagersController::class, 'store'])->name('network.managers.store');
Route::middleware('auth')->resource('cards', CardsController::class)->except('show', 'destroy');
Route::middleware('auth')->resource('orders', OrdersController::class);
Route::middleware(['auth', 'throttle:user-orders'])->post('/user/orders', UserOrdersController::class)->name('user-orders.store');
Route::middleware('auth')->resource('payments', PaymentsController::class);
Route::middleware('auth')->resource('expenses', ExpensesController::class);
Route::middleware('auth')->get('/transactions', TransactionsController::class)->name('transactions.index');
Route::middleware('auth')->resource('reports', WeeklyReportsController::class)->only(['index', 'show']);
Route::middleware('auth')->get('/reports/{report}/pdf', WeeklyReportPdfGeneratorController::class);
Route::middleware('auth')->post('/upload', [FileUploadsController::class, 'store'])->name('upload');

Route::middleware('auth')->group(function () {
    Route::post('/users/{user}', [DeactivatedUsersController::class, 'store'])->name('users.deactivate');
    Route::delete('/users/{user}', [DeactivatedUsersController::class, 'destroy'])->name('users.activate');
});

Route::middleware('auth')->group(function () {
    Route::post('/cards/{card}', [DeactivatedCardsController::class, 'store'])->name('cards.deactivate');
    Route::delete('/cards/{card}', [DeactivatedCardsController::class, 'destroy'])->name('cards.activate');
});

Route::middleware('auth')->group(function () {
    Route::post('/networks/{network}', [DeactivatedNetworksController::class, 'store'])->name('networks.deactivate');
    Route::delete('/networks/{network}', [DeactivatedNetworksController::class, 'destroy'])->name('networks.activate');
});

Route::middleware('auth')->group(function () {
    Route::post('/orders/{order}/cards', [OrderCardsController::class, 'store'])->name('order-cards.store');
    Route::delete('/order-cards/{card}', [OrderCardsController::class, 'destroy'])->name('order-cards.destroy');

    Route::get('/order-files/{file}', OrderFileDownloadsController::class)->name('order-files.download');
    Route::post('/orders/{order}/files', [OrderFilesController::class, 'store'])->name('order-files.store');
    Route::delete('/order-files/{file}', [OrderFilesController::class, 'destroy'])->name('order-files.destroy');
});
