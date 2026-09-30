<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    /**
     * Menampilkan daftar peminjaman siswa.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $loans = Loan::with([
            'details.book',
        ])
            ->where('user_id', Auth::id())

            // Filter pencarian
            ->when($search, function ($query) use ($search) {
                $query->whereHas('details.book', function ($bookQuery) use ($search) {
                    $bookQuery
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            })

            // Filter status
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })

            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Statistik dihitung di database agar tidak memuat seluruh riwayat ke memory.
        $userLoans = Loan::where('user_id', Auth::id());

        $totalLoans = (clone $userLoans)->count();

        $pendingLoans = (clone $userLoans)
            ->where('status', 'pending')
            ->count();

        $activeLoans = (clone $userLoans)
            ->whereIn('status', [
                'approved',
                'borrowed',
                'return_pending',
            ])
            ->count();

        $returnedLoans = (clone $userLoans)
            ->where('status', 'returned')
            ->count();

        return view('siswa.loans.index', compact(
            'loans',
            'search',
            'status',
            'totalLoans',
            'pendingLoans',
            'activeLoans',
            'returnedLoans'
        ));
    }

    /**
     * Menampilkan detail peminjaman.
     */
    public function show(Loan $loan)
    {
        if ($loan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke peminjaman ini.');
        }

        $loan->load([
            'details.book.category',
        ]);

        return view('siswa.loans.show', compact('loan'));
    }

    /**
     * Form pengajuan peminjaman.
     */
    public function create(Book $book)
    {
        if ($book->stock <= 0) {
            return redirect()
                ->route('siswa.books.show', $book)
                ->with('error', 'Stok buku sedang habis.');
        }

        return view('siswa.loans.create', compact('book'));
    }

    /**
     * Menyimpan pengajuan peminjaman.
     */
    public function store(Request $request, Book $book)
    {
        if ($book->stock <= 0) {
            return redirect()
                ->route('siswa.books.show', $book)
                ->with('error', 'Stok buku sedang habis.');
        }

        $validated = $request->validate([
            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:' . min(
                    $book->stock,
                    config('library.max_books_per_loan', 3)
                ),
            ],

            'borrowing_proof' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ], [
            'quantity.required' => 'Jumlah buku wajib diisi.',
            'quantity.integer' => 'Jumlah buku harus berupa angka.',
            'quantity.min' => 'Jumlah buku minimal 1.',
            'quantity.max' => 'Jumlah buku melebihi batas yang diperbolehkan.',

            'borrowing_proof.required' => 'Foto bukti peminjaman wajib diunggah.',
            'borrowing_proof.image' => 'File harus berupa gambar.',
            'borrowing_proof.mimes' => 'Format foto harus JPG, JPEG, PNG, atau WEBP.',
            'borrowing_proof.max' => 'Ukuran foto maksimal 5 MB.',

            'notes.max' => 'Catatan maksimal 1000 karakter.',
        ]);

        // Cek apakah siswa masih memiliki peminjaman aktif
        $existingLoan = Loan::where('user_id', Auth::id())
            ->whereIn('status', [
                'pending',
                'approved',
                'borrowed',
                'return_pending',
            ])
            ->whereHas('details', function ($query) use ($book) {
                $query->where('book_id', $book->id);
            })
            ->exists();

        if ($existingLoan) {
            return redirect()
                ->route('siswa.books.show', $book)
                ->with(
                    'error',
                    'Anda masih memiliki peminjaman aktif untuk buku ini.'
                );
        }

        $borrowingProof = $request
            ->file('borrowing_proof')
            ->store('loan-proofs', 'public');

        $loanDuration = config('library.loan_duration_days', 7);

        DB::transaction(function () use (
            $validated,
            $borrowingProof,
            $loanDuration,
            $book
        ) {
            $loan = Loan::create([
                'user_id' => Auth::id(),
                'loan_date' => now()->toDateString(),
                'due_date' => now()
                    ->addDays($loanDuration)
                    ->toDateString(),
                'status' => 'pending',
                'borrowing_proof' => $borrowingProof,
                'notes' => $validated['notes'] ?? null,
            ]);

            $loan->details()->create([
                'book_id' => $book->id,
                'quantity' => $validated['quantity'],
            ]);
        });

        return redirect()
            ->route('siswa.loans.index')
            ->with(
                'success',
                'Pengajuan peminjaman berhasil dikirim dan menunggu persetujuan admin.'
            );
    }

    /**
     * Form pengajuan pengembalian.
     */
    public function returnCreate(Loan $loan)
    {
        if ($loan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke peminjaman ini.');
        }

        if ($loan->status !== 'borrowed') {
            return redirect()
                ->route('siswa.loans.index')
                ->with(
                    'error',
                    'Hanya buku yang sedang dipinjam yang dapat diajukan untuk pengembalian.'
                );
        }

        $loan->load([
            'details.book',
        ]);

        return view('siswa.loans.return', compact('loan'));
    }

    /**
     * Menyimpan pengajuan pengembalian.
     */
    public function returnStore(Request $request, Loan $loan)
    {
        if ($loan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke peminjaman ini.');
        }

        if ($loan->status !== 'borrowed') {
            return redirect()
                ->route('siswa.loans.index')
                ->with(
                    'error',
                    'Peminjaman ini tidak dapat diajukan untuk pengembalian.'
                );
        }

        $validated = $request->validate([
            'return_proof' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ], [
            'return_proof.required' => 'Foto bukti pengembalian wajib diunggah.',
            'return_proof.image' => 'File harus berupa gambar.',
            'return_proof.mimes' => 'Format foto harus JPG, JPEG, PNG, atau WEBP.',
            'return_proof.max' => 'Ukuran foto maksimal 5 MB.',

            'notes.max' => 'Catatan maksimal 1000 karakter.',
        ]);

        $returnProof = $request
            ->file('return_proof')
            ->store('return-proofs', 'public');

        $loan->update([
            'status' => 'return_pending',
            'return_proof' => $returnProof,
            'notes' => $validated['notes'] ?? $loan->notes,
        ]);

        return redirect()
            ->route('siswa.loans.index')
            ->with(
                'success',
                'Pengajuan pengembalian berhasil dikirim. Silakan tunggu verifikasi admin.'
            );
    }
}