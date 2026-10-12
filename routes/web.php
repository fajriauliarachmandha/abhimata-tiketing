<?php

use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\User\HomeController as UserHomeController;
use App\Http\Controllers\User\ReviewController;
use App\Http\Controllers\User\TicketController as UserTicketController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman awal: arahkan ke login atau ke beranda sesuai role
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    return auth()->user()->role === 'it_support'
        ? redirect()->route('admin.home')
        : redirect()->route('user.home');
});

/*
|--------------------------------------------------------------------------
| Login (hanya untuk yang belum login) dan logout
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Halaman pengguna departemen (alamat diawali /user)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('user')->name('user.')->group(function () {
    Route::get('/home', [UserHomeController::class, 'index'])->name('home');

    // Tiket
    Route::get('/ticket', [UserTicketController::class, 'index'])->name('ticket.index');
    Route::post('/ticket', [UserTicketController::class, 'store'])->name('ticket.store');
    Route::get('/ticket/{ticket}', [UserTicketController::class, 'show'])->name('ticket.show');
    Route::put('/ticket/{ticket}', [UserTicketController::class, 'update'])->name('ticket.update'); // membatalkan tiket

    // Rating tiket yang sudah selesai
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});

/*
|--------------------------------------------------------------------------
| Halaman admin IT Support (alamat diawali /admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'it_support'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/home', [AdminHomeController::class, 'index'])->name('home');

    // Departemen
    Route::get('/department', [DepartmentController::class, 'index'])->name('department.index');
    Route::post('/department', [DepartmentController::class, 'store'])->name('department.store');
    Route::put('/department/{department}', [DepartmentController::class, 'update'])->name('department.update');
    Route::delete('/department/{department}', [DepartmentController::class, 'destroy'])->name('department.destroy');

    // Pengguna
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::post('/users', [UsersController::class, 'store'])->name('users.store');
    Route::put('/users/{users}', [UsersController::class, 'update'])->name('users.update');
    Route::delete('/users/{users}', [UsersController::class, 'destroy'])->name('users.destroy');

    // Tiket
    Route::get('/history', [AdminTicketController::class, 'history'])->name('ticket.history');
    Route::get('/ticket/{ticket}', [AdminTicketController::class, 'show'])->name('ticket.show');
    Route::put('/ticket/{ticket}', [AdminTicketController::class, 'process'])->name('ticket.process'); // proses tiket (pilih teknisi)
    Route::put('/ticket/{ticket}/done', [AdminTicketController::class, 'done'])->name('ticket.done');

    // Catatan pelacakan tiket
    Route::post('/ticket/{ticket}/tracking', [AdminTicketController::class, 'storeTracking'])->name('ticket.tracking.store');
    Route::put('/ticket/{ticket}/tracking/{tracking}', [AdminTicketController::class, 'updateTracking'])->name('ticket.tracking.update');
    Route::delete('/ticket/{ticket}/tracking/{tracking}', [AdminTicketController::class, 'destroyTracking'])->name('ticket.tracking.destroy');
});
