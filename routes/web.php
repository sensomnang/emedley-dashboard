<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('dashboard', [
        'totalStudents'  => 300,
        'absentRate'     => 15,
        'attendanceRate' => 15,
        'satisfaction'   => 55,
    ]);
})->name('dashboard');
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');