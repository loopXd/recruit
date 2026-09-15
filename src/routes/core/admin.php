<?php

use App\Http\Controllers\Core\DashboardController;
use Illuminate\Support\Facades\Route;

// All route names are prefixed with 'admin.'.
Route::redirect('/', '/admin/dashboard', 301);

Route::group([], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
});