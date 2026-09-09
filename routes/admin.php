<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('admin.users.index');
    })->name('dashboard');

    // Manajemen Pengguna (SRS Module 1: FR-ADM-01 s/d FR-ADM-04)
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('users/{id}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
});
