<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::middleware('guest')->post('login', [AuthenticatedSessionController::class, 'store']);
Route::middleware('auth')->post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::middleware('auth')->put('password', [PasswordController::class, 'update'])->name('password.update');

Route::middleware('auth')->singleton('profile', ProfileController::class)->except('show');
