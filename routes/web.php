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
Route::get('/recruitment', [RecruitmentController::class, 'index'])->name('recruitment.index');
Route::get('/recruitment/create', [RecruitmentController::class, 'create'])->name('recruitment.create');
Route::post('/recruitment', [RecruitmentController::class, 'store'])->name('recruitment.store');

// Route::resource('company', CompanyController::class);

Route::get('/company', [CompanyController::class, 'index'])->name('company.index');
Route::resource('/company', \App\Http\Controllers\Superadmin\CompanyController::class);

Route::get('/role', [RoleController::class, 'index'])->name('role.index');
Route::get('/create-role', [RoleController::class, 'create'])->name('role.create');
Route::post('/create-role/store', [RoleController::class, 'store'])->name('role.store');
Route::get('/role/{id}/edit', [RoleController::class, 'edit'])->name('role.edit');
Route::put('/role/{id}', [RoleController::class, 'update'])->name('role.update');
Route::delete('role/{id}', [RoleController::class, 'destroy'])->name('role.destroy');
