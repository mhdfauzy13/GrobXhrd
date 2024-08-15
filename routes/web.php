<?php

use App\Http\Controllers\Employee\OffemployeeController;
use App\Http\Controllers\Employee\OffrequestController;
use App\Http\Controllers\Superadmin\AttendanceController;
use App\Http\Controllers\Superadmin\CompanyController;
use App\Http\Controllers\Superadmin\DashboardController;
use App\Http\Controllers\Superadmin\DataUserController;
use App\Http\Controllers\Superadmin\EmployeeController;
use App\Http\Controllers\Superadmin\PayrollController;
use App\Http\Controllers\Superadmin\RecruitmentController;
use App\Http\Controllers\Employee\RoleController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attandance.index');
    Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');

    Route::get('/recruitment', [RecruitmentController::class, 'index'])->name('recruitment.index');
    Route::get('/recruitment/create', [RecruitmentController::class, 'create'])->name('recruitment.create');
    Route::post('/recruitment', [RecruitmentController::class, 'store'])->name('recruitment.store');
    Route::get('/recruitment/{recruitment_id}/edit', [RecruitmentController::class, 'edit'])->name('recruitment.edit');
    Route::put('/recruitment/{recruitment_id}', [RecruitmentController::class, 'update'])->name('recruitment.update');
    Route::delete('/recruitment/{recruitment_id}', [RecruitmentController::class, 'destroy'])->name('recruitment.destroy');

    Route::get('/offrequest', [OffemployeeController::class, 'index'])->name('offrequest.index');
    Route::get('/offrequest/create', [OffemployeeController::class, 'create'])->name('offrequest.create');
    Route::post('/offrequest', [OffemployeeController::class, 'store'])->name('offrequest.store');
    // Route::get('offrequest/{offrequest_id}/edit', [OffrequestController::class, 'edit'])->name('offrequest.edit');
    // Route::put('/offrequest/{offrequest_id}', [OffrequestController::class, 'update'])->name('offrequest.update');
    // Route::delete('/offrequest/{offrequest_id}', [OffrequestController::class, 'destroy'])->name('offrequest.destroy');


    Route::get('/company', [CompanyController::class, 'index'])->name('company.index');
    Route::resource('/company', \App\Http\Controllers\Superadmin\CompanyController::class);
    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::get('/datauser', [DataUserController::class, 'index'])->name('datauser.index');
    Route::resource('/datausers', \App\Http\Controllers\Superadmin\DataUserController::class);

    Route::get('/role', [RoleController::class, 'index'])->name('role.index');
    Route::get('/create-role', [RoleController::class, 'create'])->name('role.create');
    Route::post('/create-role/store', [RoleController::class, 'store'])->name('role.store');
    Route::get('/role/{id}/edit', [RoleController::class, 'edit'])->name('role.edit');
    Route::put('/role/{id}', [RoleController::class, 'update'])->name('role.update');
    Route::delete('role/{id}', [RoleController::class, 'destroy'])->name('role.destroy');

    Route::get('/Employee', [EmployeeController::class, 'index'])->name('Employee.index');
    Route::resource('/Employees', \App\Http\Controllers\Superadmin\EmployeeController::class);
    Route::get('/employees/{employee}', [EmployeeController::class, 'show'])->name('Employee.show');
});

require __DIR__ . '/auth.php';
