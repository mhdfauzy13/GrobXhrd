<?php


use App\Http\Controllers\Superadmin\AttendanceController;
use App\Http\Controllers\Superadmin\EmployeeData;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/attendance', [AttendanceController::class, 'index'])->name('attandance.index');

