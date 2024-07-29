<?php



use App\Http\Controllers\Superadmin\AttendanceController;
use App\Http\Controllers\Superadmin\EmployeeData;
use App\Http\Controllers\Superadmin\DashboardController;
use App\Http\Controllers\Superadmin\PayrollController;
use App\Http\Controllers\Superadmin\DataUserController;
use App\Http\Controllers\Superadmin\RecruitmentController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/attendance', [AttendanceController::class, 'index'])->name('attandance.index');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');
Route::get('/recruitment', [RecruitmentController::class, 'index'])->name('recruitment.index');


