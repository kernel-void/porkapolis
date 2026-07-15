<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AplikasiController;
use App\Http\Controllers\RolePermissionController;

Route::fallback(function () {
    return response()->view('blank', [], 404);
});

// Halaman Login
Route::get('/', function () {
    return view('auth');
});

Route::get('/auth', function () {
    return view('auth');
})->name('auth');

Route::post('/login/authenticate', [LoginController::class, 'authenticate'])->name('login.authenticate');
Route::post('/login', [LoginController::class, 'authenticate'])->middleware('throttle:10,1');

Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.changePassword');

// **SEMUA ROLE (owner, admin, kasir) LEWAT SATU GRUP INI**
Route::middleware(['auth', 'session.timeout'])->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // MENU
    Route::get('/menu', [MenuController::class, 'index'])->name('menu.index')->middleware('permission:menu.view');
    Route::post('/menu', [MenuController::class, 'store'])->name('menu.store')->middleware('permission:menu.create');
    Route::put('/menu/{id}', [MenuController::class, 'update'])->name('menu.update')->middleware('permission:menu.update');
    Route::delete('/menu/{id}', [MenuController::class, 'destroy'])->name('menu.destroy')->middleware('permission:menu.delete');

    // PEMASUKAN
    Route::get('/pemasukan', [PemasukanController::class, 'index'])->name('pemasukan.index')->middleware('permission:pemasukan.view');
    Route::post('/pemasukan', [PemasukanController::class, 'store'])->name('pemasukan.store')->middleware('permission:pemasukan.create');
    Route::put('/pemasukan/{id}', [PemasukanController::class, 'update'])->name('pemasukan.update')->middleware('permission:pemasukan.update');
    Route::delete('/pemasukan/{id}', [PemasukanController::class, 'destroy'])->name('pemasukan.destroy')->middleware('permission:pemasukan.delete');

    // PENGELUARAN
    Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran.index')->middleware('permission:pengeluaran.view');
    Route::post('/pengeluaran', [PengeluaranController::class, 'store'])->name('pengeluaran.store')->middleware('permission:pengeluaran.create');
    Route::put('/pengeluaran/{id}', [PengeluaranController::class, 'update'])->name('pengeluaran.update')->middleware('permission:pengeluaran.update');
    Route::delete('/pengeluaran/{id}', [PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy')->middleware('permission:pengeluaran.delete');;
    Route::post('/pengeluaran/export-pdf', [PengeluaranController::class, 'exportPdf'])->name('pengeluaran.exportPdf')->middleware('permission:pengeluaran.export');

    // MANAJEMEN USER (khusus owner biasanya)
    Route::get('/user', [UserController::class, 'index'])->name('user.index')->middleware('permission:user.view');
    Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('user.edit')->middleware('permission:user.update');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('user.update')->middleware('permission:user.update');

    // MANAJEMEN SETTINGS (khusus owner biasanya)
    Route::get('/pengaturan', [AplikasiController::class, 'index'])->name('pengaturan')->middleware('permission:pengaturan.view');
    Route::post('/pengaturan/update', [AplikasiController::class, 'update'])->name('pengaturan.update')->middleware('permission:pengaturan.update');

    Route::get('/roles', [RolePermissionController::class, 'index'])->name('role.index')->middleware('permission:role.view');
    Route::put('/roles/{role}', [RolePermissionController::class, 'update'])->name('role.update')->middleware('permission:role.update');
});

// **LOGOUT ROUTE**
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/auth');
})->name('logout');