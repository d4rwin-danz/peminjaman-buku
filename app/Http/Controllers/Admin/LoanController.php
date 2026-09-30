<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    /**
     * Daftar seluruh peminjaman.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $loans = Loan::with([
            'user',
            'details.book',
        ])

            // Search siswa / email / buku
            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->whereHas('user', function ($userQuery) use ($search) {
                        $userQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });

                    $q->orWhereHas('details.book', function ($bookQuery) use ($search) {
                        $bookQuery
                            ->where('title', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%");
                    });

                });

            })

            // Filter status
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })

            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Statistik
        $totalLoans = Loan::count();

        $pendingLoans = Loan::where('status', 'pending')->count();

        $activeLoans = Loan::whereIn('status', [
            'approved',
            'borrowed',
            'return_pending',
        ])->count();

        $returnedLoans = Loan::where('status', 'returned')->count();

        $rejectedLoans = Loan::where('status', 'rejected')->count();

        return view('admin.loans.index', compact(
            'loans',
            'search',
            'status',
            'totalLoans',
            'pendingLoans',
            'activeLoans',
            'returnedLoans',
            'rejectedLoans'
        ));
    }

    /**
     * Detail peminjaman.
     */
    public function show(Loan $loan)
    {
        $loan->load([
            'user',
            'details.book.category',
        ]);

        return view('admin.loans.show', compact('loan'));
    }

    /**
     * Menyetujui peminjaman.
     */
    public function approve(Loan $loan)
    {
        try {

            DB::transaction(function () use ($loan) {

                $loan = Loan::whereKey($loan->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($loan->status !== 'pending') {
                    throw new \Exception(
                        'Peminjaman ini sudah tidak berada dalam status menunggu persetujuan.'
                    );
                }

                $loan->load([
                    'details.book',
                ]);

                foreach ($loan->details as $detail) {

                    $book = $detail->book;

                    if ($book->stock < $detail->quantity) {
                        throw new \Exception(
                            "Stok buku \"{$book->title}\" tidak mencukupi."
                        );
                    }

                    $book->decrement(
                        'stock',
                        $detail->quantity
                    );
                }

                $loan->update([
                    'status' => 'approved',
                ]);
            });

            return redirect()
                ->route('admin.loans.show', $loan)
                ->with(
                    'success',
                    'Peminjaman berhasil disetujui.'
                );

        } catch (\Throwable $e) {

            return redirect()
                ->route('admin.loans.show', $loan)
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    /**
     * Menolak peminjaman.
     */
    public function reject(Request $request, Loan $loan)
    {
        $validated = $request->validate([
            'notes' => [
                'required',
                'string',
                'max:1000',
            ],
        ], [
            'notes.required' => 'Alasan penolakan wajib diisi.',
            'notes.max' => 'Alasan maksimal 1000 karakter.',
        ]);

        if ($loan->status !== 'pending') {
            return redirect()
                ->route('admin.loans.show', $loan)
                ->with(
                    'error',
                    'Peminjaman ini sudah tidak dapat ditolak.'
                );
        }

        $loan->update([
            'status' => 'rejected',
            'notes' => $validated['notes'],
        ]);

        return redirect()
            ->route('admin.loans.show', $loan)
            ->with(
                'success',
                'Peminjaman berhasil ditolak.'
            );
    }

    /**
     * Menandai buku sudah diserahkan kepada siswa.
     */
    public function borrow(Loan $loan)
    {
        if ($loan->status !== 'approved') {
            return redirect()
                ->route('admin.loans.show', $loan)
                ->with(
                    'error',
                    'Hanya peminjaman yang sudah disetujui yang dapat ditandai sebagai sedang dipinjam.'
                );
        }

        $loan->update([
            'status' => 'borrowed',
        ]);

        return redirect()
            ->route('admin.loans.show', $loan)
            ->with(
                'success',
                'Peminjaman berhasil ditandai sebagai sedang dipinjam.'
            );
    }

    /**
     * Konfirmasi pengembalian buku.
     */
    public function confirmReturn(Loan $loan)
    {
        try {

            DB::transaction(function () use ($loan) {

                $loan = Loan::whereKey($loan->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($loan->status !== 'return_pending') {
                    throw new \Exception(
                        'Peminjaman ini belum menunggu konfirmasi pengembalian.'
                    );
                }

                $loan->load([
                    'details.book',
                ]);

                $returnDate = now()->startOfDay();

                $lateDays = max(
                    0,
                    $loan->due_date->diffInDays(
                        $returnDate,
                        false
                    )
                );

                $finePerBookPerDay = config(
                    'library.fine_per_book_per_day',
                    1000
                );

                $totalFine = 0;

                foreach ($loan->details as $detail) {

                    $detail->book->increment(
                        'stock',
                        $detail->quantity
                    );

                    $totalFine +=
                        $detail->quantity
                        * $lateDays
                        * $finePerBookPerDay;
                }

                $loan->update([
                    'status' => 'returned',
                    'return_date' => $returnDate->toDateString(),
                    'late_days' => $lateDays,
                    'fine' => $totalFine,
                ]);
            });

            return redirect()
                ->route('admin.loans.show', $loan)
                ->with(
                    'success',
                    'Pengembalian berhasil dikonfirmasi. Stok buku telah dikembalikan.'
                );

        } catch (\Throwable $e) {

            return redirect()
                ->route('admin.loans.show', $loan)
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }
}