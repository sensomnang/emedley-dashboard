<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\MasterInfoController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('students', StudentController::class)->except(['show']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::prefix('master-info')->name('master-info.')->group(function () {
        Route::get('/', [MasterInfoController::class, 'index'])->name('index');

        Route::post('/teachers', [MasterInfoController::class, 'storeTeacher'])->name('teachers.store');
        Route::put('/teachers/{teacher}', [MasterInfoController::class, 'updateTeacher'])->name('teachers.update');
        Route::delete('/teachers/{teacher}', [MasterInfoController::class, 'destroyTeacher'])->name('teachers.destroy');

        Route::post('/groups', [MasterInfoController::class, 'storeGroup'])->name('groups.store');
        Route::put('/groups/{group}', [MasterInfoController::class, 'updateGroup'])->name('groups.update');
        Route::delete('/groups/{group}', [MasterInfoController::class, 'destroyGroup'])->name('groups.destroy');
    });
});

require __DIR__.'/auth.php';