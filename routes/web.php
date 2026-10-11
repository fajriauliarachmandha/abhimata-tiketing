<?php

use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\User\TicketController;
use App\Http\Controllers\User\HomeController as UserHomeController;
use App\Http\Controllers\User\ReviewController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    $user = auth()->user();

    // Belum login → ke halaman login
    if (! $user) {
        return redirect()->route('login');
    }

    // Sudah login → ke beranda sesuai role
    return $user->role === 'it_support'
        ? redirect()->route('admin.home')
        : redirect()->route('user.home');
});

Route::middleware(['auth','it_support'])->prefix('admin')->group(function() {
    Route::get('/department',[DepartmentController::class,'index'])->name('admin.departments.index');
    Route::post('/department',[DepartmentController::class,'store'])->name('admin.departments.store');
    Route::put('/department/{department}', [DepartmentController::class,'update'])->name('admin.departments.update');
    Route::delete('/department/{department}', [DepartmentController::class,'destroy'])->name('admin.departments.destroy');
    Route::get('/users',[UsersController::class,'index'])->name('admin.users.index');
    Route::post('/users',[UsersController::class,'store'])->name('admin.users.store');
    Route::put('/users/{users}',[UsersController::class,'update'])->name('admin.users.update');
    Route::delete('/users/{users}',[UsersController::class,'destroy'])->name('admin.users.destroy');
    Route::get('/home', [HomeController::class, 'index'])->name('admin.home');
    // Route::get('/ticket', [TicketController::class, 'index'])->name('ticket.index');
    Route::put('/ticket/{ticket}', [AdminTicketController::class, 'update'])->name('admin.tickets.update');
    Route::get('/ticket/{ticket}', [AdminTicketController::class, 'show'])->name('admin.tickets.show');
    Route::get('/history', [AdminTicketController::class, 'history'])->name('admin.tickets.history');
    Route::post('/ticket/{ticket}/tracking',[AdminTicketController::class,'storeTracking'])->name('admin.tickets.tracking');
    Route::delete('/ticket/{ticket}/tracking/{tracking}',[AdminTicketController::class,'destroy'])->name('admin.tracking.destroy');
    Route::put('/ticket/{ticket}/tracking/{tracking}',[AdminTicketController::class,'updateTracking'])->name('admin.tracking.update');
    Route::put('/ticket/{ticket}/done', [AdminTicketController::class, 'done'])
    ->name('admin.ticket.done');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::middleware('auth')->prefix('user')->group(function() {
    Route::get('/home', [UserHomeController::class, 'index'])->name('user.home');
    Route::get('/ticket', [TicketController::class, 'index'])->name('user.ticket.index');
    Route::get('/ticket/create', [TicketController::class, 'create'])->name('user.ticket.create');
    Route::post('/ticket', [TicketController::class, 'store'])->name('user.ticket.store');
    Route::put('/ticket/{ticket}', [TicketController::class, 'update'])->name('user.ticket.update');
    Route::get('/ticket/{ticket}', [TicketController::class, 'show'])->name('user.ticket.show');
    Route::post('/reviews', [ReviewController::class, 'store']);
});
// nanti pas udah bikin controller buat tiket, route ini bisa diaktifin lagi
// Route::middleware('auth')->group(function () {
//     Route::get('/pengajuantiket', [TicketController::class, 'create'])->name('tickets.create');
// });


