<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Superadmin\DashboardController;
use App\Http\Controllers\Superadmin\DataUserController;
use App\Http\Controllers\Superadmin\CompanyController;
use App\Http\Controllers\Superadmin\EmployeeController;
use App\Http\Controllers\Superadmin\PayrollController;
use App\Http\Controllers\Superadmin\RecruitmentController;
use App\Http\Controllers\Superadmin\RoleController;
use App\Http\Controllers\Superadmin\AttandanceController;
use App\Http\Controllers\Employee\OffemployeeController;
use App\Http\Controllers\ProfileController;

// Redirect ke login jika tidak ada route yang cocok
Route::get('/', function () {
    return redirect()->route('login');
});

// Rute yang Memerlukan Role 'superadmin'
Route::middleware(['auth', 'checkRoleStatus'])->group(function () {

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Rute khusus untuk superadmin
    Route::middleware(['checkRole:superadmin'])->prefix('superadmin')->group(function () {

        // Role management
        Route::get('/role', [RoleController::class, 'index'])->name('role.index');
        Route::get('/role/create', [RoleController::class, 'create'])->name('role.create');
        Route::post('/role', [RoleController::class, 'store'])->name('role.store');
        Route::get('/role/{id}/edit', [RoleController::class, 'edit'])->name('role.edit');
        Route::put('/role/{id}', [RoleController::class, 'update'])->name('role.update');
        Route::delete('/role/{id}', [RoleController::class, 'destroy'])->name('role.destroy');

        // User management
        Route::get('/datauser', [DataUserController::class, 'index'])->name('datauser.index');
        Route::get('/datauser/create', [DataUserController::class, 'create'])->name('datauser.create');
        Route::post('/datauser', [DataUserController::class, 'store'])->name('datauser.store');
        Route::get('/datauser/{id}/edit', [DataUserController::class, 'edit'])->name('datauser.edit');
        Route::put('/datauser/{id}', [DataUserController::class, 'update'])->name('datauser.update');
        Route::delete('/datauser/{id}', [DataUserController::class, 'destroy'])->name('datauser.destroy');

        // Attendance
        Route::get('/attendance', [AttandanceController::class, 'index'])->name('attandance.index');

        // Employee management
        Route::resource('/employees', EmployeeController::class)->names([
            'index' => 'employee.index',
            'create' => 'employee.create',
            'store' => 'employee.store',
            'edit' => 'employee.edit',
            'update' => 'employee.update',
            'destroy' => 'employee.destroy',
            'show' => 'employee.show',
        ]);

        // Payroll
        Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');

        // Recruitment
        Route::get('/recruitment', [RecruitmentController::class, 'index'])->name('recruitment.index');
        Route::get('/recruitment/create', [RecruitmentController::class, 'create'])->name('recruitment.create');
        Route::post('/recruitment', [RecruitmentController::class, 'store'])->name('recruitment.store');
        Route::get('/recruitment/{recruitment_id}/edit', [RecruitmentController::class, 'edit'])->name('recruitment.edit');
        Route::put('/recruitment/{recruitment_id}', [RecruitmentController::class, 'update'])->name('recruitment.update');
        Route::delete('/recruitment/{recruitment_id}', [RecruitmentController::class, 'destroy'])->name('recruitment.destroy');
    });
});

// Rute yang Dapat Diakses oleh Pengguna Lain
Route::middleware(['auth', 'checkRoleStatus'])
    ->prefix('employee')->group(function () {

        // Off Request
        Route::get('/offrequest', [OffemployeeController::class, 'index'])->name('offrequest.index');
        Route::get('/offrequest/create', [OffemployeeController::class, 'create'])->name('offrequest.create');
        Route::post('/offrequest', [OffemployeeController::class, 'store'])->name('offrequest.store');
    });

require __DIR__ . '/auth.php';
