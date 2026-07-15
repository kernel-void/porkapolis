<?php

use Illuminate\Http\Request;
use App\Models\Biaya;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\PemasukanController as AdminPemasukanController;
use App\Http\Controllers\Admin\PengeluaranController as AdminPengeluaranController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\AplikasiController as AdminAplikasiController;

use App\Http\Controllers\Kasir\DashboardController as KasirDashboardController;
use App\Http\Controllers\Kasir\MenuController as KasirMenuController;
use App\Http\Controllers\Kasir\PemasukanController as KasirPemasukanController;
use App\Http\Controllers\Kasir\PengeluaranController as KasirPengeluaranController;

use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\MenuController as OwnerMenuController;
use App\Http\Controllers\Owner\PemasukanController as OwnerPemasukanController;
use App\Http\Controllers\Owner\PengeluaranController as OwnerPengeluaranController;

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

Route::middleware(['auth', 'session.timeout'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [KasirDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
});

Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.changePassword');

Route::post('/login', [LoginController::class, 'authenticate'])->middleware('throttle:10,1');

// Middleware Authentication dan Role Protection
Route::middleware(['auth'])->group(function () {
    // **ADMIN ROUTES**
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        Route::get('/menu', [AdminMenuController::class, 'index'])->name('admin.menu.index');
        Route::post('/menu', [AdminMenuController::class, 'store'])->name('admin.menu.store');
        Route::put('/menu/{id}', [AdminMenuController::class, 'update'])->name('admin.menu.update');
        Route::delete('/menu/{id}', [AdminMenuController::class, 'destroy'])->name('admin.menu.destroy');
        Route::put('/menu/restore/{id}', [AdminMenuController::class, 'restore'])->name('admin.menu.restore');

        Route::get('/pemasukan', [AdminPemasukanController::class, 'index'])->name('admin.pemasukan.index');
        Route::post('/pemasukan', [AdminPemasukanController::class, 'store'])->name('admin.pemasukan.store');
        Route::put('/pemasukan/{id}', [AdminPemasukanController::class, 'update'])->name('admin.pemasukan.update');
        Route::delete('/pemasukan/{id}', [AdminPemasukanController::class, 'destroy'])->name('admin.pemasukan.destroy');
        Route::put('/pemasukan/restore/{id}', [AdminPemasukanController::class, 'restore'])->name('admin.pemasukan.restore');
        
        Route::get('/pengeluaran', [AdminPengeluaranController::class, 'index'])->name('admin.pengeluaran.index');
        Route::post('/pengeluaran', [AdminPengeluaranController::class, 'store'])->name('admin.pengeluaran.store');
        Route::put('/pengeluaran/{id}', [AdminPengeluaranController::class, 'update'])->name('admin.pengeluaran.update');
        Route::delete('/pengeluaran/{id}', [AdminPengeluaranController::class, 'destroy'])->name('admin.pengeluaran.destroy');
        Route::put('/pengeluaran/restore/{id}', [AdminPengeluaranController::class, 'restore'])->name('admin.pengeluaran.restore');

        // Manajemen User
        Route::get('/user', [AdminUserController::class, 'index'])->name('admin.user.index');
        Route::get('/users/edit/{id}', [AdminUserController::class, 'edit'])->name('admin.user.edit');
        Route::put('/users/{id}', [AdminUserController::class, 'update'])->name('admin.user.update');

        // Manajemen Settings
        Route::get('/pengaturan', [AdminAplikasiController::class, 'index'])->name('admin.pengaturan');
        Route::post('/pengaturan/update', [AdminAplikasiController::class, 'update'])->name('admin.pengaturan.update');
    });

    // **KASIR ROUTES**
    Route::middleware('role:kasir')->prefix('kasir')->group(function () {
        Route::get('/dashboard', [KasirDashboardController::class, 'index'])->name('kasir.dashboard');
        
        Route::get('/menu', [KasirMenuController::class, 'index'])->name('kasir.menu.index');
        Route::post('/menu', [KasirMenuController::class, 'store'])->name('kasir.menu.store');
        Route::put('/menu/{id}', [KasirMenuController::class, 'update'])->name('kasir.menu.update');
        Route::delete('/menu/{id}', [KasirMenuController::class, 'destroy'])->name('kasir.menu.destroy');

        Route::get('/pemasukan', [KasirPemasukanController::class, 'index'])->name('kasir.pemasukan.index');
        Route::post('/pemasukan', [KasirPemasukanController::class, 'store'])->name('kasir.pemasukan.store');
        Route::put('/pemasukan/{id}', [KasirPemasukanController::class, 'update'])->name('kasir.pemasukan.update');
        Route::post('/pemasukan/export-pdf', [KasirPemasukanController::class, 'exportPdf'])
            ->name('kasir.pemasukan.exportPdf');
        Route::delete('/pemasukan/{id}', [KasirPemasukanController::class, 'destroy'])->name('kasir.pemasukan.destroy');
        
        Route::get('/pengeluaran', [KasirPengeluaranController::class, 'index'])->name('kasir.pengeluaran.index');
        Route::post('/pengeluaran', [KasirPengeluaranController::class, 'store'])->name('kasir.pengeluaran.store');
        Route::put('/pengeluaran/{id}', [KasirPengeluaranController::class, 'update'])->name('kasir.pengeluaran.update');
        Route::post('/pengeluaran/export-pdf', [KasirPengeluaranController::class, 'exportPdf'])
            ->name('kasir.pengeluaran.exportPdf');
        Route::delete('/pengeluaran/{id}', [KasirPengeluaranController::class, 'destroy'])->name('kasir.pengeluaran.destroy');
    });

    // **OWNER ROUTES**
    Route::middleware('role:owner')->prefix('owner')->group(function () {
        Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('owner.dashboard');
        
        Route::get('/menu', [OwnerMenuController::class, 'index'])->name('owner.menu.index');
        Route::post('/menu', [OwnerMenuController::class, 'store'])->name('owner.menu.store');
        Route::put('/menu/{id}', [OwnerMenuController::class, 'update'])->name('owner.menu.update');
        Route::delete('/menu/{id}', [OwnerMenuController::class, 'destroy'])->name('owner.menu.destroy');

        Route::get('/pemasukan', [OwnerPemasukanController::class, 'index'])->name('owner.pemasukan.index');
        Route::post('/pemasukan/export-pdf', [OwnerPemasukanController::class, 'exportPdf'])
            ->name('owner.pemasukan.exportPdf');
        
        Route::get('/pengeluaran', [OwnerPengeluaranController::class, 'index'])->name('owner.pengeluaran.index');
        Route::post('/pengeluaran', [OwnerPengeluaranController::class, 'store'])->name('owner.pengeluaran.store');
        Route::put('/pengeluaran/{id}', [OwnerPengeluaranController::class, 'update'])->name('owner.pengeluaran.update');
        Route::post('/pengeluaran/export-pdf', [OwnerPengeluaranController::class, 'exportPdf'])
            ->name('owner.pengeluaran.exportPdf');
        Route::delete('/pengeluaran/{id}', [OwnerPengeluaranController::class, 'destroy'])->name('owner.pengeluaran.destroy');
    });
});

// **LOGOUT ROUTE**
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/auth');
})->name('logout');
