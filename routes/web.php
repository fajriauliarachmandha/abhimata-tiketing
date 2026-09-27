<?php

use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
});

Route::get('/about', function () {
    return Inertia::render('About');
});

Route::get('/pengajuantiket', function () {
    return Inertia::render('User/PengajuanTiket');
});

Route::middleware('auth','it_support')->prefix('admin')->group(function() {
    Route::get('/department',[DepartmentController::class,'index'])->name('departments.index');
    Route::post('/department',[DepartmentController::class,'store'])->name('departments.store');
    Route::put('/department/{department}', [DepartmentController::class,'update'])->name('departments.update');
    Route::delete('/department/{department}', [DepartmentController::class,'destroy'])->name('departments.destroy');
    Route::get('/users',[UsersController::class,'index'])->name('users.index');
    Route::post('/users',[UsersController::class,'store'])->name('users.store');
    Route::put('/users/{users}',[UsersController::class,'update'])->name('users.update');
    Route::delete('/users/{users}',[UsersController::class,'destroy'])->name('users.destroy');
    Route::get('/home', [HomeController::class, 'index'])->name('admin.home');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// nanti pas udah bikin controller buat tiket, route ini bisa diaktifin lagi
// Route::middleware('auth')->group(function () {
//     Route::get('/pengajuantiket', [TicketController::class, 'create'])->name('tickets.create');
// });


