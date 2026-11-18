<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Staff;
use App\Models\Student;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        // Statistik buku
        $totalBuku = Book::count();
        $totalStok = Book::sum('stock');

        // Statistik peminjaman
        $peminjamanPending = Loan::where('status', 'pending')->count();
        $sedangDipinjam = Loan::whereIn('status', ['approved', 'borrowed'])->count();
        $menungguKembali = Loan::where('status', 'return_pending')->count();
        $sudahDikembalikan = Loan::where('status', 'returned')->count();
        $ditolak = Loan::where('status', 'rejected')->count();

        $terlambat = Loan::where('status', 'borrowed')
            ->whereDate('due_date', '<', $today)
            ->count();

        // Jumlah eksemplar yang sedang dipinjam (bukan jumlah transaksi)
        $jumlahBukuDipinjam = Loan::where('status', 'borrowed')
            ->with('details')
            ->get()
            ->sum(fn ($loan) => $loan->details->sum('quantity'));

        // Statistik siswa, pegawai & aktivasi akun
        $totalSiswa = Student::count();
        $siswaAktif = Student::where('activation_status', Student::ACTIVE)->count();
        $siswaPending = Student::where('activation_status', Student::PENDING)->count();

        $totalPegawai = Staff::count();
        $pegawaiAktif = Staff::where('activation_status', Student::ACTIVE)->count();
        $pegawaiPending = Staff::where('activation_status', Student::PENDING)->count();

        $aktivasiPending = $siswaPending + $pegawaiPending;

        // Gabungan permintaan aktivasi (siswa + pegawai), yang paling lama menunggu di atas.
        $aktivasiMenunggu = Student::where('activation_status', Student::PENDING)
            ->orderBy('activation_requested_at')->take(5)->get()
            ->map(fn ($s) => [
                'name' => $s->name,
                'id' => $s->nis,
                'detail' => $s->class_name,
                'time' => $s->activation_requested_at,
                'approve' => route('admin.students.approve', $s),
            ])
            ->concat(
                Staff::where('activation_status', Student::PENDING)
                    ->orderBy('activation_requested_at')->take(5)->get()
                    ->map(fn ($s) => [
                        'name' => $s->name,
                        'id' => $s->staff_number,
                        'detail' => $s->position,
                        'time' => $s->activation_requested_at,
                        'approve' => route('admin.staff.approve', $s),
                    ])
            )
            ->sortBy('time')
            ->take(5)
            ->values();

        // Daftar peminjaman dibagi tiga: perlu diurus -> berjalan -> selesai
        $with = ['user.student', 'user.staff', 'details.book'];

        $perluDiurus = Loan::with($with)
            ->where(function ($q) use ($today) {
                $q->whereIn('status', ['pending', 'return_pending', 'approved'])
                    ->orWhere(function ($q) use ($today) {
                        $q->where('status', 'borrowed')->whereDate('due_date', '<', $today);
                    });
            })
            ->priorityOrder()
            ->take(15)
            ->get();

        $sedangBerjalan = Loan::with($with)
            ->where('status', 'borrowed')
            ->whereDate('due_date', '>=', $today)
            ->latest()
            ->take(5)
            ->get();

        $selesai = Loan::with($with)
            ->whereIn('status', ['returned', 'rejected'])
            ->latest()
            ->take(5)
            ->get();

        $jumlahPerluDiurus = $peminjamanPending + $menungguKembali + $terlambat
            + Loan::where('status', 'approved')->count();

        return view('admin.dashboard', compact(
            'totalBuku',
            'totalStok',
            'peminjamanPending',
            'sedangDipinjam',
            'menungguKembali',
            'sudahDikembalikan',
            'ditolak',
            'terlambat',
            'jumlahBukuDipinjam',
            'totalSiswa',
            'siswaAktif',
            'siswaPending',
            'totalPegawai',
            'pegawaiAktif',
            'pegawaiPending',
            'aktivasiPending',
            'aktivasiMenunggu',
            'perluDiurus',
            'sedangBerjalan',
            'selesai',
            'jumlahPerluDiurus'
        ));
    }
}
