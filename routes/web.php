<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Employee\DashboardEmployeeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Superadmin\DashboardController;
use App\Http\Controllers\Superadmin\DataUserController;
use App\Http\Controllers\Superadmin\CompanyController;
use App\Http\Controllers\Superadmin\EmployeeController;
use App\Http\Controllers\Superadmin\PayrollController;
use App\Http\Controllers\Superadmin\RecruitmentController;
use App\Http\Controllers\Superadmin\RoleController;
use App\Http\Controllers\Superadmin\AttandanceController;
use App\Http\Controllers\Superadmin\EventController;
use App\Http\Controllers\Employee\OffemployeeController;
use App\Http\Controllers\ProfileController;
use App\Notifications\LeaveRequestNotification;
use App\Http\Controllers\Superadmin\EmployeeBookController;
use App\Http\Controllers\Superadmin\EmployeeBooksController;


Route::get('/', function () {
    return redirect()->route('login');
});



Route::middleware(['auth', 'checkRoleStatus'])->group(function () {

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])
        ->name('dashboard.index')
        ->middleware('permission:dashboard.view');

    // Role Management
    Route::prefix('Superadmin')->group(function () {
        Route::get('/role', [RoleController::class, 'index'])
            ->name('role.index')
            ->middleware('permission:role.index');
        Route::get('/role/create', [RoleController::class, 'create'])
            ->name('role.create')
            ->middleware('permission:role.create');
        Route::post('/role', [RoleController::class, 'store'])
            ->name('role.store')
            ->middleware('permission:role.create');
        Route::get('/role/{id}/edit', [RoleController::class, 'edit'])
            ->name('role.edit')
            ->middleware('permission:role.edit');
        Route::put('/role/{id}', [RoleController::class, 'update'])
            ->name('role.update')
            ->middleware('permission:role.edit');
        Route::delete('/role/{id}', [RoleController::class, 'destroy'])
            ->name('role.destroy')
            ->middleware('permission:role.delete');

        // Data User
        Route::get('/datauser', [DataUserController::class, 'index'])
            ->name('datauser.index')
            ->middleware('permission:user.index');
        Route::get('/datauser/create', [DataUserController::class, 'create'])
            ->name('datauser.create')
            ->middleware('permission:user.create');
        Route::post('/datauser', [DataUserController::class, 'store'])
            ->name('datauser.store')
            ->middleware('permission:user.create');
        Route::get('/datauser/{user_id}/edit', [DataUserController::class, 'edit'])
            ->name('datauser.edit')
            ->middleware('permission:user.edit');

        Route::put('/datauser/{user_id}', [DataUserController::class, 'update'])
            ->name('datauser.update')
            ->middleware('permission:user.edit');

        Route::delete('/datauser/{user_id}', [DataUserController::class, 'destroy'])
            ->name('datauser.destroy')
            ->middleware('permission:user.delete');

        // Attendance
        Route::get('/attendance', [AttandanceController::class, 'index'])
            ->name('attandance.index')
            ->middleware('permission:attandance.index');

        // Off Request



        Route::get('/company', [CompanyController::class, 'index'])
            ->name('company.index')
            ->middleware('permission:company.index');

        // routes/web.php

        // Payroll
        Route::get('/payrolls', [PayrollController::class, 'index'])
            ->name('payroll.index')
            ->middleware('permission:payroll.index');

        // Menampilkan form create payroll dan menyimpan data payroll
        Route::get('/payrolls/create', [PayrollController::class, 'create'])
            ->name('payroll.create')
            ->middleware('permission:payroll.create');

        Route::post('/payrolls', [PayrollController::class, 'store'])
            ->name('payroll.store')
            ->middleware('permission:payroll.create');
        // Menampilkan form edit payroll dan mengupdate payroll
        Route::get('/payrolls/{id}/edit', [PayrollController::class, 'edit'])
            ->name('payroll.edit')
            ->middleware('permission:payroll.edit');

        Route::put('/payrolls/{id}', [PayrollController::class, 'update'])
            ->name('payroll.update')
            ->middleware('permission:payroll.edit');

        // Menghapus payroll
        Route::delete('/payrolls/{id}', [PayrollController::class, 'destroy'])
            ->name('payroll.destroy')
            ->middleware('permission:payroll.delete');


        // event
        Route::get('/events', [EventController::class, 'index'])
            ->name('event.index')
            ->middleware('permission:event.index');

        // Menampilkan form list event
        Route::get('events/list',  [EventController::class, 'ListEvent'])
            ->name('events.list')
            ->middleware('permission:events.list');

        Route::get('/events/create', [EventController::class, 'create'])
            ->name('event.create')
            ->middleware('permission:event.create');

        Route::post('/events', [EventController::class, 'store'])
            ->name('event.store')
            ->middleware('permission:event.create');

        Route::get('/events/{event}/edit', [EventController::class, 'edit'])
            ->name('event.edit')
            ->middleware('permission:event.edit');

        Route::put('/events/{event}', [EventController::class, 'update'])
            ->name('event.update')
            ->middleware('permission:event.edit');

        Route::delete('/events/{event}', [EventController::class, 'destroy'])
            ->name('event.destroy')
            ->middleware('permission:event.delete');


        // employeebooks
        Route::get('/employeebooks', [EmployeeBookController::class, 'index'])
            ->name('superadmin.employeebooks.index')
            ->middleware('permission:employeebook.index');

        Route::get('/employeebooks/create', [EmployeeBookController::class, 'create'])
            ->name('superadmin.employeebooks.create')
            ->middleware('permission:employeebook.create');

        Route::post('/employeebooks', [EmployeeBookController::class, 'store'])
            ->name('superadmin.employeebooks.store')
            ->middleware('permission:employeebook.create');

        Route::get('/employeebooks/{employeebook_id}/edit', [EmployeeBookController::class, 'edit'])
            ->name('superadmin.employeebooks.edit')
            ->middleware('permission:employeebook.edit');

        Route::put('/employeebooks/{employeebook_id}', [EmployeeBookController::class, 'update'])
            ->name('superadmin.employeebooks.update')
            ->middleware('permission:employeebook.edit');

        Route::delete('/employeebooks/{employeebook_id}', [EmployeeBookController::class, 'destroy'])
            ->name('superadmin.employeebooks.destroy')
            ->middleware('permission:employeebook.delete');


        // Recruitment
        Route::get('/recruitment', [RecruitmentController::class, 'index'])
            ->name('recruitment.index')
            ->middleware('permission:recruitment.index');
        Route::get('/recruitment/create', [RecruitmentController::class, 'create'])
            ->name('recruitment.create')
            ->middleware('permission:recruitment.create');
        Route::post('/recruitment', [RecruitmentController::class, 'store'])
            ->name('recruitment.store')
            ->middleware('permission:recruitment.create');
        Route::get('/recruitment/{recruitment_id}/edit', [RecruitmentController::class, 'edit'])
            ->name('recruitment.edit')
            ->middleware('permission:recruitment.edit');
        Route::put('/recruitment/{recruitment_id}', [RecruitmentController::class, 'update'])
            ->name('recruitment.update')
            ->middleware('permission:recruitment.edit');
        Route::delete('/recruitment/{recruitment_id}', [RecruitmentController::class, 'destroy'])
            ->name('recruitment.destroy')
            ->middleware('permission:recruitment.delete');

        // Employee
        Route::get('/employee', [EmployeeController::class, 'index'])
            ->name('employee.index')
            ->middleware('permission:employee.index');

        Route::get('/employee/create', [EmployeeController::class, 'create'])
            ->name('employee.create')
            ->middleware('permission:employee.create');

        Route::post('/employee', [EmployeeController::class, 'store'])
            ->name('employee.store')
            ->middleware('permission:employee.create');

        Route::get('/employee/{id}/edit', [EmployeeController::class, 'edit'])
            ->name('employee.edit')
            ->middleware('permission:employee.edit');

        Route::put('/employee/{id}', [EmployeeController::class, 'update'])
            ->name('employee.update')
            ->middleware('permission:employee.edit');

        Route::delete('/employee/{id}', [EmployeeController::class, 'destroy'])
            ->name('employee.destroy')
            ->middleware('permission:employee.destroy');

        Route::get('/employees/{employee}', [EmployeeController::class, 'show'])
            ->name('employee.show')
            ->middleware('permission:employee.show');
    });

    // Employee Routes
    Route::prefix('Employee')->group(function () {
        Route::get('dashboard', [DashboardEmployeeController::class, 'index'])
            ->name('dashboardemployee.index')
            ->middleware('permission:dashboardemployee.view');

        // Attendance Scan
        Route::get('/attandance/scan', [AttandanceController::class, 'scanView'])
            ->name('attandance.scanView')
            ->middleware('permission:attandance.scanView');

        Route::post('/attandance/scan', [AttandanceController::class, 'scan'])
            ->name('attandance.scan')
            ->middleware('permission:attandance.scan');


        // Tambahkan rute untuk mendapatkan status kehadiran
        Route::get('/attandance/state', [AttandanceController::class, 'getAttendanceState'])
            ->name('attandance.getState')
            ->middleware('permission:attandance.scan'); // Tambahkan middleware auth jika diperlukan

        // Off Request


        Route::get('/offrequest', [OffemployeeController::class, 'index'])
            ->name('offrequest.index')
            ->middleware('permission:offrequest.index');

        Route::get('/offrequest/create', [OffemployeeController::class, 'create'])
            ->name('offrequest.create')
            ->middleware('permission:offrequest.create');
        Route::post('/offrequest', [OffemployeeController::class, 'store'])
            ->name('offrequest.store')
            ->middleware('permission:offrequest.store');
        Route::post('/offrequest/{offrequest_id}/approve', [OffemployeeController::class, 'approve'])
            ->name('offrequest.approve')
            ->middleware('permission:offrequest.approver');

        Route::post('/offrequest/{offrequest_id}/reject', [OffemployeeController::class, 'reject'])
            ->name('offrequest.reject')
            ->middleware('permission:offrequest.approver');

        Route::get('/offrequest/history', [OffemployeeController::class, 'history'])
            ->name('offrequest.history')
            ->middleware('permission:offrequest.approver');

        Route::get('/offrequest/approver', [OffemployeeController::class, 'approverIndex'])
            ->name('offrequest.approver')
            ->middleware('permission:offrequest.approver');

        
    });
});





require __DIR__ . '/auth.php';
