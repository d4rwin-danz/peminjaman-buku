<?php

namespace App\Http\Controllers\Admin;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function loans(Request $request)
    {
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $status = $request->input('status');
        $search = $request->input('search');

        $loans = Loan::with([
            'user',
            'details.book',
        ])
            ->when($dateFrom, function ($query) use ($dateFrom) {
                $query->whereDate('loan_date', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query) use ($dateTo) {
                $query->whereDate('loan_date', '<=', $dateTo);
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
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
            ->latest('loan_date')
            ->paginate(15)
            ->withQueryString();

        $query = Loan::query()
            ->when($dateFrom, function ($query) use ($dateFrom) {
                $query->whereDate('loan_date', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query) use ($dateTo) {
                $query->whereDate('loan_date', '<=', $dateTo);
            })
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
            });

        $totalLoans = (clone $query)->count();

        $pendingLoans = (clone $query)
            ->where('status', 'pending')
            ->count();

        $activeLoans = (clone $query)
            ->whereIn('status', [
                'approved',
                'borrowed',
                'return_pending',
            ])
            ->count();

        $returnedLoans = (clone $query)
            ->where('status', 'returned')
            ->count();

        $rejectedLoans = (clone $query)
            ->where('status', 'rejected')
            ->count();

        $totalFine = (clone $query)
            ->where('status', 'returned')
            ->sum('fine');

        return view('admin.reports.loans', compact(
            'loans',
            'dateFrom',
            'dateTo',
            'status',
            'search',
            'totalLoans',
            'pendingLoans',
            'activeLoans',
            'returnedLoans',
            'rejectedLoans',
            'totalFine'
        ));
    }

    public function exportPdf(Request $request)
{
    $dateFrom = $request->input('date_from');
    $dateTo = $request->input('date_to');
    $status = $request->input('status');
    $search = $request->input('search');

    $loans = Loan::with([
        'user',
        'details.book',
    ])
        ->when($dateFrom, function ($query) use ($dateFrom) {
            $query->whereDate('loan_date', '>=', $dateFrom);
        })
        ->when($dateTo, function ($query) use ($dateTo) {
            $query->whereDate('loan_date', '<=', $dateTo);
        })
        ->when($status, function ($query) use ($status) {
            $query->where('status', $status);
        })
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
        ->latest('loan_date')
        ->get();

    $totalLoans = $loans->count();

    $totalFine = $loans
        ->where('status', 'returned')
        ->sum('fine');

    $pdf = Pdf::loadView('admin.reports.loans-pdf', compact(
        'loans',
        'dateFrom',
        'dateTo',
        'status',
        'search',
        'totalLoans',
        'totalFine'
    ));

    $pdf->setPaper('a4', 'landscape');

    return $pdf->download('laporan-peminjaman.pdf');
}
}