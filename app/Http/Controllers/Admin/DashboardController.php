<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Statistik Buku
        |--------------------------------------------------------------------------
        */

        $totalBuku = Book::count();

        $totalStok = Book::sum('stock');


        /*
        |--------------------------------------------------------------------------
        | Statistik Peminjaman
        |--------------------------------------------------------------------------
        */

        $peminjamanPending = Loan::where('status', 'pending')->count();

        $peminjamanDisetujui = Loan::where('status', 'approved')->count();

        $sedangDipinjam = Loan::where('status', 'borrowed')->count();

        $sudahDikembalikan = Loan::where('status', 'returned')->count();

        $ditolak = Loan::where('status', 'rejected')->count();


        /*
        |--------------------------------------------------------------------------
        | Statistik Siswa
        |--------------------------------------------------------------------------
        */

        $totalSiswa = User::where('role', 'siswa')->count();


        /*
        |--------------------------------------------------------------------------
        | Total Buku yang Sedang Dipinjam
        |--------------------------------------------------------------------------
        |
        | Berbeda dengan jumlah transaksi.
        | Contoh:
        | 1 transaksi meminjam 3 buku = 3 buku sedang dipinjam.
        |
        */

        $jumlahBukuDipinjam = Loan::where('status', 'borrowed')
            ->with('details')
            ->get()
            ->sum(function ($loan) {
                return $loan->details->sum('quantity');
            });


        /*
        |--------------------------------------------------------------------------
        | Peminjaman Terbaru
        |--------------------------------------------------------------------------
        */

        $peminjamanTerbaru = Loan::with([
            'user',
            'details.book',
        ])
            ->latest()
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Data untuk Dashboard
        |--------------------------------------------------------------------------
        */

        return view('admin.dashboard', compact(
            'totalBuku',
            'totalStok',
            'peminjamanPending',
            'peminjamanDisetujui',
            'sedangDipinjam',
            'sudahDikembalikan',
            'ditolak',
            'totalSiswa',
            'jumlahBukuDipinjam',
            'peminjamanTerbaru'
        ));
    }
}