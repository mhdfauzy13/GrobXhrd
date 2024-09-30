<?php

use App\Http\Controllers\Employee\OffemployeeController;
use App\Http\Controllers\Superadmin\CompanyController;
use App\Http\Controllers\Superadmin\DashboardController;
use App\Http\Controllers\Superadmin\DataUserController;
use App\Http\Controllers\Superadmin\EmployeeController;
use App\Http\Controllers\Superadmin\PayrollController;
use App\Http\Controllers\Superadmin\RecruitmentController;
use App\Http\Controllers\Superadmin\RoleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Superadmin\AttandanceController;
use App\Http\Controllers\Superadmin\EventController;
use App\Http\Controllers\Superadmin\HolidayController;
use Illuminate\Support\Facades\Route;

// Tambahkan route di sini
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/attandance/scan', [AttandanceController::class, 'scanView'])->name('attandance.scanView');
Route::post('/attandance/scan', [AttandanceController::class, 'scan'])->name('attandance.scan');


Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'checkRoleStatus'])
    ->name('dashboard.index');


Route::middleware(['auth', 'checkRoleStatus'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/attendance', [AttandanceController::class, 'index'])->name('attandance.index');

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

    Route::resource('/company', CompanyController::class);

    Route::get('/datauser', [DataUserController::class, 'index'])->name('datauser.index');
    Route::resource('/datausers', DataUserController::class);

    Route::get('/role', [RoleController::class, 'index'])->name('role.index');
    Route::get('/create-role', [RoleController::class, 'create'])->name('role.create');
    Route::post('/create-role/store', [RoleController::class, 'store'])->name('role.store');
    Route::get('/role/{id}/edit', [RoleController::class, 'edit'])->name('role.edit');
    Route::put('/role/{id}', [RoleController::class, 'update'])->name('role.update');
    Route::delete('/role/{id}', [RoleController::class, 'destroy'])->name('role.destroy');

    Route::get('/Employee', [EmployeeController::class, 'index'])->name('Employee.index');
    Route::resource('/Employees', EmployeeController::class);
    Route::get('/employees/{employee}', [EmployeeController::class, 'show'])->name('Employee.show');


    Route::get('/holiday/calendar', function () {
        return view('holiday.calendar');
    })->name('holiday.calendar');

    Route::get('/holiday/create', function () {
        return view('holiday.create');
    })->name('holiday.create');

    Route::prefix('superadmin/holiday')->name('holiday.')->group(function () {
        Route::get('calendar', [HolidayController::class, 'calendar'])->name('calendar'); // Menampilkan tampilan kalender
        Route::get('data', [HolidayController::class, 'data'])->name('calendar.data'); // Mendapatkan data event
        Route::get('sync', [HolidayController::class, 'syncNationalHolidays'])->name('sync'); // Sinkronkan libur nasional
        Route::post('create', [HolidayController::class, 'createEvent'])->name('create'); // Menambahkan event baru
        Route::post('save-event', [HolidayController::class, 'saveEvent'])->name('save-event'); // Menyimpan event dari frontend
        Route::post('delete-event', [HolidayController::class, 'deleteEvent'])->name('delete-event'); // Menghapus event
    });

    Route::get('events/list',  [EventController::class, 'ListEvent'])->name('events.list');
    Route::resource('events', EventController::class);
});

require __DIR__ . '/auth.php';
