<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinancialController;
use App\Http\Controllers\ImprovementController;
use App\Http\Controllers\MasterInfoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('students', StudentController::class)->except(['show']);

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Master info
    Route::prefix('master-info')->name('master-info.')->group(function () {
        Route::get('/', [MasterInfoController::class, 'index'])->name('index');

        Route::post('/teachers', [MasterInfoController::class, 'storeTeacher'])->name('teachers.store');
        Route::put('/teachers/{teacher}', [MasterInfoController::class, 'updateTeacher'])->name('teachers.update');
        Route::delete('/teachers/{teacher}', [MasterInfoController::class, 'destroyTeacher'])->name('teachers.destroy');

        Route::post('/groups', [MasterInfoController::class, 'storeGroup'])->name('groups.store');
        Route::put('/groups/{group}', [MasterInfoController::class, 'updateGroup'])->name('groups.update');
        Route::delete('/groups/{group}', [MasterInfoController::class, 'destroyGroup'])->name('groups.destroy');
    });

    // Financial
    Route::resource('financial', FinancialController::class)
        ->parameters(['financial' => 'payment'])
        ->only(['index', 'store', 'update', 'destroy']);

    // Improvement
    Route::get('/improvement', [ImprovementController::class, 'index'])->name('improvement.index');
    Route::post('/improvement', [ImprovementController::class, 'store'])->name('improvement.store');
});

require __DIR__.'/auth.php';