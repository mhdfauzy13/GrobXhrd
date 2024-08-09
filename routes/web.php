<?php

use App\Http\Controllers\Superadmin\AttendanceController;
use App\Http\Controllers\Superadmin\CompanyController;
use App\Http\Controllers\Superadmin\DashboardController;
use App\Http\Controllers\Superadmin\PayrollController;
use App\Http\Controllers\Superadmin\RecruitmentController;
use App\Http\Controllers\Superadmin\RoleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/attendance', [AttendanceController::class, 'index'])->name('attandance.index');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');

// Recruitment Routes
Route::get('/recruitment', [RecruitmentController::class, 'index'])->name('recruitment.index');
Route::resource('/recruitment', RecruitmentController::class);
Route::resource('recruitment', \App\Http\Controllers\Superadmin\RecruitmentController::class);


// Company Routes
Route::get('/company', [CompanyController::class, 'index'])->name('company.index');
Route::resource('/company', CompanyController::class);

// Role Routes
Route::get('/role', [RoleController::class, 'index'])->name('role.index');
Route::get('/create-role', [RoleController::class, 'create'])->name('role.create');
Route::post('/create-role/store', [RoleController::class, 'store'])->name('role.store');
Route::get('/role/{id}/edit', [RoleController::class, 'edit'])->name('role.edit');
Route::put('/role/{id}', [RoleController::class, 'update'])->name('role.update');
Route::delete('/role/{id}', [RoleController::class, 'destroy'])->name('role.destroy');
