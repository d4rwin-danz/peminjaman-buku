<?php

use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LoanController as AdminLoanController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Siswa\BookController as SiswaBookController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Siswa\LoanController as SiswaLoanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Publik (tanpa login)
|--------------------------------------------------------------------------
| Pengunjung bisa melihat dashboard, katalog, dan detail buku.
| Login baru diminta saat mengajukan peminjaman.
*/

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/books', [SiswaBookController::class, 'index'])
    ->name('siswa.books.index');

Route::get('/books/{book}', [SiswaBookController::class, 'show'])
    ->name('siswa.books.show');

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:10,1')
        ->name('login.process');

    // Siswa tidak lagi mendaftar sendiri: akun dibuat lewat aktivasi NIS + ACC admin.
    Route::get('/aktivasi', [AuthController::class, 'showActivation'])
        ->name('activation.show');

    Route::post('/aktivasi', [AuthController::class, 'requestActivation'])
        ->middleware('throttle:10,1')
        ->name('activation.process');

    Route::redirect('/register', '/aktivasi')->name('register');

});

// POST dari tombol Keluar. GET diizinkan sebagai cadangan: proteksi bot hosting
// gratis (InfinityFree) kadang mengubah POST menjadi GET /logout?i=1.
// Tanpa middleware auth agar sesi yang sudah habis tetap diarahkan ke beranda.
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])
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

        // Scan QR / cari kode (harus di atas /loans/{loan})
        Route::get('/loans/scan', [AdminLoanController::class, 'scan'])
            ->name('loans.scan');

        Route::get('/loans/code/{code}', [AdminLoanController::class, 'findByCode'])
            ->where('code', '[A-Za-z0-9\-]+')
            ->name('loans.code');

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
        
        // Aktivasi akun & data siswa (DADIR)
        Route::get('/students', [StudentController::class, 'index'])
            ->name('students.index');

        Route::get('/students/create', [StudentController::class, 'create'])
            ->name('students.create');

        Route::post('/students', [StudentController::class, 'store'])
            ->name('students.store');

        Route::post('/students/bulk-approve', [StudentController::class, 'bulkApprove'])
            ->name('students.bulk-approve');

        Route::get('/students/{student}/edit', [StudentController::class, 'edit'])
            ->name('students.edit');

        Route::put('/students/{student}', [StudentController::class, 'update'])
            ->name('students.update');

        Route::delete('/students/{student}', [StudentController::class, 'destroy'])
            ->name('students.destroy');

        Route::post('/students/{student}/approve', [StudentController::class, 'approve'])
            ->name('students.approve');

        Route::post('/students/{student}/reject', [StudentController::class, 'reject'])
            ->name('students.reject');

        Route::post('/students/{student}/deactivate', [StudentController::class, 'deactivate'])
            ->name('students.deactivate');

        // Pegawai & staf sekolah (NIK)
        Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
        Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
        Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
        Route::post('/staff/bulk-approve', [StaffController::class, 'bulkApprove'])->name('staff.bulk-approve');
        Route::get('/staff/{staff}/edit', [StaffController::class, 'edit'])->name('staff.edit');
        Route::put('/staff/{staff}', [StaffController::class, 'update'])->name('staff.update');
        Route::delete('/staff/{staff}', [StaffController::class, 'destroy'])->name('staff.destroy');
        Route::post('/staff/{staff}/approve', [StaffController::class, 'approve'])->name('staff.approve');
        Route::post('/staff/{staff}/reject', [StaffController::class, 'reject'])->name('staff.reject');
        Route::post('/staff/{staff}/deactivate', [StaffController::class, 'deactivate'])->name('staff.deactivate');

        // Laporan
        Route::get('/reports/loans', [ReportController::class, 'loans'])
            ->name('reports.loans');

        Route::get('/reports/loans/pdf', [ReportController::class, 'exportPdf'])
            ->name('reports.loans.pdf');

        Route::get('/reports/loans/excel', [ReportController::class, 'exportExcel'])
            ->name('reports.loans.excel');
    });


/*
|--------------------------------------------------------------------------
| Siswa
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:siswa,pegawai'])
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [SiswaDashboardController::class, 'index'])
            ->name('dashboard');


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