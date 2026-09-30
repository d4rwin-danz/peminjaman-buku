<?php

use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LoanController as AdminLoanController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Siswa\BookController as SiswaBookController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Siswa\LoanController as SiswaLoanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('siswa.dashboard');
    }

    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.process');

    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.process');

});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');


        // Kategori
        Route::resource('categories', CategoryController::class);


        // Buku
        Route::resource('books', BookController::class);


        // Peminjaman
        Route::get('/loans', [AdminLoanController::class, 'index'])
            ->name('loans.index');

        Route::get('/loans/{loan}', [AdminLoanController::class, 'show'])
            ->name('loans.show');

        Route::post('/loans/{loan}/approve', [AdminLoanController::class, 'approve'])
            ->name('loans.approve');

        Route::post('/loans/{loan}/reject', [AdminLoanController::class, 'reject'])
            ->name('loans.reject');

        Route::post('/loans/{loan}/borrow', [AdminLoanController::class, 'borrow'])
            ->name('loans.borrow');

        Route::post('/loans/{loan}/confirm-return', [AdminLoanController::class, 'confirmReturn'])
            ->name('loans.confirm-return');


        // Laporan
        Route::get('/reports/loans', [ReportController::class, 'loans'])
            ->name('reports.loans');

        Route::get('/reports/loans/pdf', [ReportController::class, 'exportPdf'])
            ->name('reports.loans.pdf');
    });


/*
|--------------------------------------------------------------------------
| Siswa
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:siswa'])
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [SiswaDashboardController::class, 'index'])
            ->name('dashboard');


        // Katalog Buku
        Route::get('/books', [SiswaBookController::class, 'index'])
            ->name('books.index');

        Route::get('/books/{book}', [SiswaBookController::class, 'show'])
            ->name('books.show');


        // Pengajuan Peminjaman
        Route::get('/books/{book}/loan', [SiswaLoanController::class, 'create'])
            ->name('loans.create');

        Route::post('/books/{book}/loan', [SiswaLoanController::class, 'store'])
            ->name('loans.store');


        // Peminjaman Saya
        Route::get('/loans', [SiswaLoanController::class, 'index'])
            ->name('loans.index');

        Route::get('/loans/{loan}', [SiswaLoanController::class, 'show'])
            ->name('loans.show');


        // Pengajuan Pengembalian
        Route::get('/loans/{loan}/return', [SiswaLoanController::class, 'returnCreate'])
            ->name('loans.return.create');

        Route::post('/loans/{loan}/return', [SiswaLoanController::class, 'returnStore'])
            ->name('loans.return.store');
    });