@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-slate-50 px-4 py-6 sm:px-6 lg:px-8">

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-blue-600">ADMINISTRATOR</p>
            <h1 class="mt-1 text-2xl font-bold text-slate-900 sm:text-3xl">Laporan Peminjaman</h1>
            <p class="mt-1 text-sm text-slate-500">Rekapitulasi dan pencarian data transaksi perpustakaan.</p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.loans.index') }}"
               class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                ← Peminjaman
            </a>

            <a href="{{ route('admin.reports.loans.excel', request()->query()) }}"
               class="inline-flex items-center justify-center rounded-xl bg-green-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-green-700">
                Export Excel
            </a>

            <button type="button"
                    onclick="window.print()"
                    class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                Cetak
            </button>
        </div>
    </div>

    <div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-5">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Total Peminjaman</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ $totalLoans }}</p>
        </div>

        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
            <p class="text-sm font-medium text-amber-700">Pending</p>
            <p class="mt-2 text-3xl font-bold text-amber-800">{{ $pendingLoans }}</p>
        </div>

        <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5">
            <p class="text-sm font-medium text-blue-700">Aktif</p>
            <p class="mt-2 text-3xl font-bold text-blue-800">{{ $activeLoans }}</p>
        </div>

        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">
            <p class="text-sm font-medium text-emerald-700">Dikembalikan</p>
            <p class="mt-2 text-3xl font-bold text-emerald-800">{{ $returnedLoans }}</p>
        </div>

        <div class="rounded-2xl border border-red-200 bg-red-50 p-5">
            <p class="text-sm font-medium text-red-700">Ditolak</p>
            <p class="mt-2 text-3xl font-bold text-red-800">{{ $rejectedLoans }}</p>
        </div>
    </div>

    <div class="mb-6 rounded-2xl border border-orange-200 bg-orange-50 p-5">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-orange-700">Total Denda dari Hasil Filter</p>
                <p class="text-xs text-orange-600">Dihitung dari transaksi yang sudah dikembalikan.</p>
            </div>
            <p class="text-2xl font-bold text-orange-800">
                Rp {{ number_format($totalFine, 0, ',', '.') }}
            </p>
        </div>
    </div>

    <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-4">
            <h2 class="text-lg font-bold text-slate-900">Filter Laporan</h2>
            <p class="text-sm text-slate-500">Gunakan filter untuk menampilkan data tertentu.</p>
        </div>

        <form action="{{ route('admin.reports.loans') }}" method="GET"
              class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-5">

            <div class="lg:col-span-2">
                <label for="search" class="mb-1.5 block text-sm font-semibold text-slate-700">Cari</label>
                <input id="search" type="text" name="search" value="{{ $search }}"
                       placeholder="Nama siswa, NIS, judul buku..."
                       class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
            </div>

            <div>
                <label for="date_from" class="mb-1.5 block text-sm font-semibold text-slate-700">Dari Tanggal</label>
                <input id="date_from" type="date" name="date_from" value="{{ $dateFrom }}"
                       class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
            </div>

            <div>
                <label for="date_to" class="mb-1.5 block text-sm font-semibold text-slate-700">Sampai Tanggal</label>
                <input id="date_to" type="date" name="date_to" value="{{ $dateTo }}"
                       class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
            </div>

            <div>
                <label for="status" class="mb-1.5 block text-sm font-semibold text-slate-700">Status</label>
                <select id="status" name="status"
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ ($status ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ ($status ?? '') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="borrowed" {{ ($status ?? '') === 'borrowed' ? 'selected' : '' }}>Sedang Dipinjam</option>
                    <option value="return_pending" {{ ($status ?? '') === 'return_pending' ? 'selected' : '' }}>Menunggu Pengembalian</option>
                    <option value="returned" {{ ($status ?? '') === 'returned' ? 'selected' : '' }}>Dikembalikan</option>
                    <option value="rejected" {{ ($status ?? '') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div class="flex items-end gap-2 md:col-span-2 lg:col-span-5">
                <button type="submit"
                        class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                    Terapkan Filter
                </button>

                <a href="{{ route('admin.reports.loans') }}"
                   class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-5 py-4">
            <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Data Laporan</h2>
                    <p class="text-sm text-slate-500">Menampilkan {{ $loans->count() }} data pada halaman ini.</p>
                </div>

                @if($dateFrom || $dateTo || $status || $search)
                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">Filter aktif</span>
                @endif
            </div>
        </div>

        <div class="hidden overflow-x-auto md:block">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">#</th>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Siswa</th>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Buku</th>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Tanggal</th>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Jatuh Tempo</th>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Denda</th>
                        <th class="px-5 py-3 text-right text-xs font-bold uppercase tracking-wider text-slate-500">Detail</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($loans as $loan)
                        <tr class="transition hover:bg-slate-50">
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-500">
                                {{ $loans->firstItem() + $loop->index }}
                            </td>

                            <td class="px-5 py-4">
                                <div class="font-semibold text-slate-900">{{ $loan->user->name }}</div>
                                <div class="text-xs text-slate-500">{{ $loan->user->identifier }}</div>
                            </td>

                            <td class="px-5 py-4">
                                @foreach($loan->details as $detail)
                                    <div class="mb-1 last:mb-0">
                                        <div class="font-medium text-slate-800">{{ $detail->book->title }}</div>
                                        <div class="text-xs text-slate-500">
                                            {{ $detail->book->code }} · Qty {{ $detail->quantity }}
                                        </div>
                                    </div>
                                @endforeach
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-700">
                                {{ $loan->loan_date?->format('d/m/Y') }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-700">
                                {{ $loan->due_date?->format('d/m/Y') }}
                            </td>

                            <td class="px-5 py-4">
                                @php
                                    $statuses = [
                                        'pending' => ['Pending', 'bg-amber-100 text-amber-700'],
                                        'approved' => ['Disetujui', 'bg-blue-100 text-blue-700'],
                                        'borrowed' => ['Sedang Dipinjam', 'bg-indigo-100 text-indigo-700'],
                                        'return_pending' => ['Menunggu Pengembalian', 'bg-orange-100 text-orange-700'],
                                        'returned' => ['Dikembalikan', 'bg-emerald-100 text-emerald-700'],
                                        'rejected' => ['Ditolak', 'bg-red-100 text-red-700'],
                                    ];
                                    $loanStatus = $statuses[$loan->status] ?? [$loan->status, 'bg-slate-100 text-slate-700'];
                                @endphp

                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $loanStatus[1] }}">
                                    {{ $loanStatus[0] }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm font-semibold text-slate-800">
                                Rp {{ number_format($loan->fine ?? 0, 0, ',', '.') }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-right">
                                <a href="{{ route('admin.loans.show', $loan) }}"
                                   class="inline-flex rounded-lg bg-slate-900 px-3 py-2 text-xs font-semibold text-white transition hover:bg-slate-800">
                                    Lihat
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center">
                                <svg class="mx-auto h-10 w-10 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 19.5V5a2 2 0 0 1 2-2h13v16H6a2 2 0 0 0-2 2.5Z"/><path d="M4 19.5A2 2 0 0 1 6 18h13"/></svg>
                                <p class="mt-3 font-semibold text-slate-700">Tidak ada data laporan.</p>
                                <p class="mt-1 text-sm text-slate-500">Coba ubah filter atau kata pencarian.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="divide-y divide-slate-100 md:hidden">
            @forelse($loans as $loan)
                <div class="p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="font-bold text-slate-900">{{ $loan->user->name }}</h3>
                            <p class="text-xs text-slate-500">{{ $loan->user->identifier }}</p>
                        </div>

                        @php
                            $statuses = [
                                'pending' => ['Pending', 'bg-amber-100 text-amber-700'],
                                'approved' => ['Disetujui', 'bg-blue-100 text-blue-700'],
                                'borrowed' => ['Dipinjam', 'bg-indigo-100 text-indigo-700'],
                                'return_pending' => ['Menunggu Pengembalian', 'bg-orange-100 text-orange-700'],
                                'returned' => ['Dikembalikan', 'bg-emerald-100 text-emerald-700'],
                                'rejected' => ['Ditolak', 'bg-red-100 text-red-700'],
                            ];
                            $loanStatus = $statuses[$loan->status] ?? [$loan->status, 'bg-slate-100 text-slate-700'];
                        @endphp

                        <span class="rounded-full px-2.5 py-1 text-[11px] font-bold {{ $loanStatus[1] }}">
                            {{ $loanStatus[0] }}
                        </span>
                    </div>

                    <div class="mt-4 space-y-2">
                        @foreach($loan->details as $detail)
                            <div class="rounded-xl bg-slate-50 p-3">
                                <p class="font-semibold text-slate-800">{{ $detail->book->title }}</p>
                                <p class="text-xs text-slate-500">
                                    {{ $detail->book->code }} · {{ $detail->quantity }} buku
                                </p>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <p class="text-xs text-slate-500">Tanggal Pinjam</p>
                            <p class="font-semibold text-slate-700">{{ $loan->loan_date?->format('d/m/Y') }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-500">Jatuh Tempo</p>
                            <p class="font-semibold text-slate-700">{{ $loan->due_date?->format('d/m/Y') }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-500">Denda</p>
                            <p class="font-semibold text-orange-700">
                                Rp {{ number_format($loan->fine ?? 0, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="flex items-end justify-end">
                            <a href="{{ route('admin.loans.show', $loan) }}"
                               class="rounded-lg bg-slate-900 px-3 py-2 text-xs font-semibold text-white">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-5 py-12 text-center">
                    <svg class="mx-auto h-10 w-10 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 19.5V5a2 2 0 0 1 2-2h13v16H6a2 2 0 0 0-2 2.5Z"/><path d="M4 19.5A2 2 0 0 1 6 18h13"/></svg>
                    <p class="mt-3 font-semibold text-slate-700">Tidak ada data laporan.</p>
                </div>
            @endforelse
        </div>
    </div>

    @if($loans->hasPages())
        <div class="mt-6">
            {{ $loans->links() }}
        </div>
    @endif

</div>

<style>
    @media print {
        nav,
        header,
        footer {
            display: none !important;
        }

        body {
            background: white !important;
        }

        .min-h-screen {
            min-height: auto !important;
        }

        button,
        a {
            text-decoration: none !important;
        }

        @page {
            size: landscape;
            margin: 10mm;
        }
    }
</style>

@endsection