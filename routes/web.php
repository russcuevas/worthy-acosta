<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ElectoralDataController as AdminElectoralDataController;
use App\Http\Controllers\Admin\AssistanceController as AdminAssistanceController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\DirectoryController as AdminDirectoryController;
use App\Http\Controllers\Admin\IssueController as AdminIssueController;
use App\Http\Controllers\Admin\SurveyController as AdminSurveyController;
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
    
    // Electoral Data
    Route::get('/electoral', [AdminElectoralDataController::class, 'index'])->name('electoral');
    Route::get('/electoral/data', [AdminElectoralDataController::class, 'getData'])->name('electoral.data');
    Route::get('/electoral/years', [AdminElectoralDataController::class, 'getYears'])->name('electoral.years');
    Route::get('/electoral/barangays', [AdminElectoralDataController::class, 'getBarangayList'])->name('electoral.barangays');
    Route::post('/electoral/add-year', [AdminElectoralDataController::class, 'addYear'])->name('electoral.add_year');
    Route::post('/electoral/update-year', [AdminElectoralDataController::class, 'updateYear'])->name('electoral.update_year');
    Route::post('/electoral/delete-year', [AdminElectoralDataController::class, 'deleteYear'])->name('electoral.delete_year');
    Route::post('/electoral/save-data', [AdminElectoralDataController::class, 'saveData'])->name('electoral.save_data');
    Route::get('/electoral/geojson', [AdminElectoralDataController::class, 'getBarangays'])->name('electoral.geojson');
    Route::post('/electoral/save-geojson', [AdminElectoralDataController::class, 'saveGeojson'])->name('electoral.save_geojson');

    // Assistance Module
    Route::prefix('assistance')->name('assistance.')->group(function () {
        Route::get('/', [AdminAssistanceController::class, 'index'])->name('index');
        Route::get('/data', [AdminAssistanceController::class, 'getData'])->name('data');
        Route::post('/store', [AdminAssistanceController::class, 'store'])->name('store');
        Route::get('/record/{id}', [AdminAssistanceController::class, 'show'])->name('show');
        Route::post('/update/{id}', [AdminAssistanceController::class, 'update'])->name('update');
        Route::post('/delete/{id}', [AdminAssistanceController::class, 'destroy'])->name('delete');
        Route::get('/export', [AdminAssistanceController::class, 'exportCsv'])->name('export');
    });

    // Events Module
    Route::prefix('events')->name('events.')->group(function () {
        Route::get('/', [AdminEventController::class, 'index'])->name('index');
        Route::get('/data', [AdminEventController::class, 'getData'])->name('data');
        Route::post('/store', [AdminEventController::class, 'store'])->name('store');
        Route::get('/record/{id}', [AdminEventController::class, 'show'])->name('show');
        Route::post('/update/{id}', [AdminEventController::class, 'update'])->name('update');
        Route::post('/mark-past/{id}', [AdminEventController::class, 'markPast'])->name('mark_past');
        Route::post('/delete/{id}', [AdminEventController::class, 'destroy'])->name('delete');
        Route::get('/export', [AdminEventController::class, 'exportCsv'])->name('export');
    });

    // Directory Module
    Route::prefix('directory')->name('directory.')->group(function () {
        Route::get('/', [AdminDirectoryController::class, 'index'])->name('index');
        Route::get('/data', [AdminDirectoryController::class, 'getData'])->name('data');
        Route::post('/store', [AdminDirectoryController::class, 'store'])->name('store');
        Route::get('/record/{id}', [AdminDirectoryController::class, 'show'])->name('show');
        Route::post('/update/{id}', [AdminDirectoryController::class, 'update'])->name('update');
        Route::post('/delete/{id}', [AdminDirectoryController::class, 'destroy'])->name('delete');
        Route::get('/export', [AdminDirectoryController::class, 'exportCsv'])->name('export');
    });

    // Issues Module
    Route::prefix('issues')->name('issues.')->group(function () {
        Route::get('/', [AdminIssueController::class, 'index'])->name('index');
        Route::get('/data', [AdminIssueController::class, 'getData'])->name('data');
        Route::post('/store', [AdminIssueController::class, 'store'])->name('store');
        Route::get('/record/{id}', [AdminIssueController::class, 'show'])->name('show');
        Route::post('/update/{id}', [AdminIssueController::class, 'update'])->name('update');
        Route::post('/delete/{id}', [AdminIssueController::class, 'destroy'])->name('delete');
        Route::get('/export', [AdminIssueController::class, 'exportCsv'])->name('export');
    });

    // Survey Module
    Route::prefix('survey')->name('survey.')->group(function () {
        Route::get('/', [AdminSurveyController::class, 'index'])->name('index');
        Route::get('/data', [AdminSurveyController::class, 'getData'])->name('data');
        Route::post('/store', [AdminSurveyController::class, 'store'])->name('store');
        Route::post('/period/store', [AdminSurveyController::class, 'storePeriod'])->name('period.store');
        Route::get('/record/{id}', [AdminSurveyController::class, 'show'])->name('show');
        Route::post('/update/{id}', [AdminSurveyController::class, 'update'])->name('update');
        Route::post('/delete/{id}', [AdminSurveyController::class, 'destroy'])->name('delete');
        Route::post('/period/delete/{id}', [AdminSurveyController::class, 'destroyPeriod'])->name('period.delete');
        Route::get('/export', [AdminSurveyController::class, 'exportCsv'])->name('export');
    });
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

    // Assistance Module
    Route::prefix('assistance')->name('assistance.')->group(function () {
        Route::get('/', [AdminAssistanceController::class, 'index'])->name('index');
        Route::get('/data', [AdminAssistanceController::class, 'getData'])->name('data');
        Route::post('/store', [AdminAssistanceController::class, 'store'])->name('store');
        Route::get('/record/{id}', [AdminAssistanceController::class, 'show'])->name('show');
        Route::post('/update/{id}', [AdminAssistanceController::class, 'update'])->name('update');
        Route::post('/delete/{id}', [AdminAssistanceController::class, 'destroy'])->name('delete');
        Route::get('/export', [AdminAssistanceController::class, 'exportCsv'])->name('export');
    });

    // Events Module
    Route::prefix('events')->name('events.')->group(function () {
        Route::get('/', [AdminEventController::class, 'index'])->name('index');
        Route::get('/data', [AdminEventController::class, 'getData'])->name('data');
        Route::post('/store', [AdminEventController::class, 'store'])->name('store');
        Route::get('/record/{id}', [AdminEventController::class, 'show'])->name('show');
        Route::post('/update/{id}', [AdminEventController::class, 'update'])->name('update');
        Route::post('/mark-past/{id}', [AdminEventController::class, 'markPast'])->name('mark_past');
        Route::post('/delete/{id}', [AdminEventController::class, 'destroy'])->name('delete');
        Route::get('/export', [AdminEventController::class, 'exportCsv'])->name('export');
    });

    // Directory Module
    Route::prefix('directory')->name('directory.')->group(function () {
        Route::get('/', [AdminDirectoryController::class, 'index'])->name('index');
        Route::get('/data', [AdminDirectoryController::class, 'getData'])->name('data');
        Route::post('/store', [AdminDirectoryController::class, 'store'])->name('store');
        Route::get('/record/{id}', [AdminDirectoryController::class, 'show'])->name('show');
        Route::post('/update/{id}', [AdminDirectoryController::class, 'update'])->name('update');
        Route::post('/delete/{id}', [AdminDirectoryController::class, 'destroy'])->name('delete');
        Route::get('/export', [AdminDirectoryController::class, 'exportCsv'])->name('export');
    });

    // Issues Module
    Route::prefix('issues')->name('issues.')->group(function () {
        Route::get('/', [AdminIssueController::class, 'index'])->name('index');
        Route::get('/data', [AdminIssueController::class, 'getData'])->name('data');
        Route::post('/store', [AdminIssueController::class, 'store'])->name('store');
        Route::get('/record/{id}', [AdminIssueController::class, 'show'])->name('show');
        Route::post('/update/{id}', [AdminIssueController::class, 'update'])->name('update');
        Route::post('/delete/{id}', [AdminIssueController::class, 'destroy'])->name('delete');
        Route::get('/export', [AdminIssueController::class, 'exportCsv'])->name('export');
    });

    // Survey Module
    Route::prefix('survey')->name('survey.')->group(function () {
        Route::get('/', [AdminSurveyController::class, 'index'])->name('index');
        Route::get('/data', [AdminSurveyController::class, 'getData'])->name('data');
        Route::post('/store', [AdminSurveyController::class, 'store'])->name('store');
        Route::post('/period/store', [AdminSurveyController::class, 'storePeriod'])->name('period.store');
        Route::get('/record/{id}', [AdminSurveyController::class, 'show'])->name('show');
        Route::post('/update/{id}', [AdminSurveyController::class, 'update'])->name('update');
        Route::post('/delete/{id}', [AdminSurveyController::class, 'destroy'])->name('delete');
        Route::post('/period/delete/{id}', [AdminSurveyController::class, 'destroyPeriod'])->name('period.delete');
        Route::get('/export', [AdminSurveyController::class, 'exportCsv'])->name('export');
    });
});
