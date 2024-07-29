<?php

use App\Http\Controllers\Superadmin\DashboardController;
use App\Http\Controllers\Superadmin\PayrollController;
use App\Http\Controllers\Superadmin\RecruitmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');
Route::get('/recruitment', [RecruitmentController::class, 'index'])->name('recruitment.index');

?>