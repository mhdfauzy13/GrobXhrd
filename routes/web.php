<?php

use App\Http\Controllers\Superadmin\DataUserController;
use App\Http\Controllers\Superadmin\RecruitmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/datauser', [DataUserController::class, 'index'])->name('data-user.index');




