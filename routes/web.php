<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ElectoralDataController as AdminElectoralDataController;
use App\Http\Controllers\Assistant\DashboardController as AssistantDashboardController;

// Authentication routes
Route::get('/', [AuthController::class, 'login'])->name('home');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/authenticate', [AuthController::class, 'authenticate'])->name('authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.electoral');
    });
    Route::get('/dashboard', function () {
        return redirect()->route('admin.electoral');
    });
    Route::get('/electoral', [AdminElectoralDataController::class, 'index'])->name('electoral');
    Route::get('/electoral/data', [AdminElectoralDataController::class, 'getData'])->name('electoral.data');
    Route::get('/electoral/years', [AdminElectoralDataController::class, 'getYears'])->name('electoral.years');
    Route::post('/electoral/add-year', [AdminElectoralDataController::class, 'addYear'])->name('electoral.add_year');
    Route::post('/electoral/save-data', [AdminElectoralDataController::class, 'saveData'])->name('electoral.save_data');
    Route::get('/electoral/geojson', [AdminElectoralDataController::class, 'getBarangays'])->name('electoral.geojson');
    Route::post('/electoral/save-geojson', [AdminElectoralDataController::class, 'saveGeojson'])->name('electoral.save_geojson');
});

// Assistant routes
Route::prefix('assistant')->name('assistant.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('assistant.electoral');
    });
    Route::get('/dashboard', function () {
        return redirect()->route('assistant.electoral');
    });
    Route::get('/electoral', [AdminElectoralDataController::class, 'index'])->name('electoral');
});
