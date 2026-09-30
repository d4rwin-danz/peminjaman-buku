<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Total stok buku yang tersedia
        |--------------------------------------------------------------------------
        */

        $bukuTersedia = Book::sum('stock');


        /*
        |--------------------------------------------------------------------------
        | Jumlah buku yang sedang dipinjam
        |--------------------------------------------------------------------------
        */

        $sedangDipinjam = Loan::where('user_id', $user->id)
            ->whereIn('status', ['approved', 'borrowed'])
            ->with('details')
            ->get()
            ->sum(function ($loan) {
                return $loan->details->sum('quantity');
            });


        /*
        |--------------------------------------------------------------------------
        | Pengajuan yang masih menunggu
        |--------------------------------------------------------------------------
        */

        $peminjamanPending = Loan::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Total riwayat transaksi
        |--------------------------------------------------------------------------
        */

        $riwayatPeminjaman = Loan::where('user_id', $user->id)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Peminjaman aktif
        |--------------------------------------------------------------------------
        */

        $peminjamanAktif = Loan::with([
            'details.book',
        ])
            ->where('user_id', $user->id)
            ->whereIn('status', ['approved', 'borrowed'])
            ->latest()
            ->take(3)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Peminjaman terbaru
        |--------------------------------------------------------------------------
        */

        $peminjamanTerbaru = Loan::with([
            'details.book',
        ])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();


        return view('siswa.dashboard', compact(
            'user',
            'bukuTersedia',
            'sedangDipinjam',
            'peminjamanPending',
            'riwayatPeminjaman',
            'peminjamanAktif',
            'peminjamanTerbaru'
        ));
    }
}