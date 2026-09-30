@extends('layouts.app')

@section('title', 'Kelola Peminjaman')

@section('content')
<div class="min-h-screen bg-slate-50 py-6 sm:py-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

                <div>
                    <p class="text-sm font-semibold text-indigo-600">
                        ADMIN E-LIBRARY
                    </p>

                    <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                        Kelola Peminjaman
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Pantau, verifikasi, dan kelola seluruh transaksi peminjaman.
                    </p>
                </div>

            </div>
        </div>

        {{-- Statistik --}}
        <div class="mb-6 grid grid-cols-2 gap-3 md:grid-cols-3 lg:grid-cols-5">

            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-medium text-slate-500">
                    Total
                </p>

                <p class="mt-2 text-2xl font-bold text-slate-900">
                    {{ $totalLoans }}
                </p>
            </div>

            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 shadow-sm">
                <p class="text-xs font-medium text-amber-700">
                    Pending
                </p>

                <p class="mt-2 text-2xl font-bold text-amber-800">
                    {{ $pendingLoans }}
                </p>
            </div>

            <div class="rounded-2xl border border-indigo-200 bg-indigo-50 p-4 shadow-sm">
                <p class="text-xs font-medium text-indigo-700">
                    Aktif
                </p>

                <p class="mt-2 text-2xl font-bold text-indigo-800">
                    {{ $activeLoans }}
                </p>
            </div>

            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 shadow-sm">
                <p class="text-xs font-medium text-emerald-700">
                    Dikembalikan
                </p>

                <p class="mt-2 text-2xl font-bold text-emerald-800">
                    {{ $returnedLoans }}
                </p>
            </div>

            <div class="rounded-2xl border border-red-200 bg-red-50 p-4 shadow-sm">
                <p class="text-xs font-medium text-red-700">
                    Ditolak
                </p>

                <p class="mt-2 text-2xl font-bold text-red-800">
                    {{ $rejectedLoans }}
                </p>
            </div>

        </div>

        {{-- Filter --}}
        <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">

            <form
                action="{{ route('admin.loans.index') }}"
                method="GET"
                class="grid gap-3 md:grid-cols-[1fr_230px_auto_auto]"
            >

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">
                        Pencarian
                    </label>

                    <input
                        type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Nama siswa, email, judul, atau kode buku..."
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    >
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">
                        Status
                    </label>

                    <select
                        name="status"
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    >
                        <option value="">Semua Status</option>

                        <option value="pending"
                            {{ ($status ?? '') === 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>

                        <option value="approved"
                            {{ ($status ?? '') === 'approved' ? 'selected' : '' }}>
                            Disetujui
                        </option>

                        <option value="borrowed"
                            {{ ($status ?? '') === 'borrowed' ? 'selected' : '' }}>
                            Sedang Dipinjam
                        </option>

                        <option value="return_pending"
                            {{ ($status ?? '') === 'return_pending' ? 'selected' : '' }}>
                            Menunggu Pengembalian
                        </option>

                        <option value="returned"
                            {{ ($status ?? '') === 'returned' ? 'selected' : '' }}>
                            Dikembalikan
                        </option>

                        <option value="rejected"
                            {{ ($status ?? '') === 'rejected' ? 'selected' : '' }}>
                            Ditolak
                        </option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button
                        type="submit"
                        class="w-full rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 md:w-auto"
                    >
                        Cari
                    </button>
                </div>

                <div class="flex items-end">
                    <a
                        href="{{ route('admin.loans.index') }}"
                        class="w-full rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50 md:w-auto"
                    >
                        Reset
                    </a>
                </div>

            </form>
        </div>

        {{-- Desktop --}}
        <div class="hidden overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm lg:block">

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Peminjaman
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Siswa
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Tanggal
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                Status
                            </th>

                            <th class="px-5 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @forelse ($loans as $loan)

                            @php
                                $statusConfig = [
                                    'pending' => [
                                        'label' => 'Pending',
                                        'class' => 'bg-amber-100 text-amber-700',
                                    ],

                                    'approved' => [
                                        'label' => 'Disetujui',
                                        'class' => 'bg-blue-100 text-blue-700',
                                    ],

                                    'borrowed' => [
                                        'label' => 'Sedang Dipinjam',
                                        'class' => 'bg-indigo-100 text-indigo-700',
                                    ],

                                    'return_pending' => [
                                        'label' => 'Menunggu Pengembalian',
                                        'class' => 'bg-orange-100 text-orange-700',
                                    ],

                                    'returned' => [
                                        'label' => 'Dikembalikan',
                                        'class' => 'bg-emerald-100 text-emerald-700',
                                    ],

                                    'rejected' => [
                                        'label' => 'Ditolak',
                                        'class' => 'bg-red-100 text-red-700',
                                    ],
                                ];

                                $currentStatus = $statusConfig[$loan->status] ?? [
                                    'label' => ucfirst($loan->status),
                                    'class' => 'bg-slate-100 text-slate-700',
                                ];
                            @endphp

                            <tr class="transition hover:bg-slate-50">

                                <td class="px-5 py-4">
                                    <div class="max-w-xs">

                                        <p class="font-bold text-slate-900">
                                            #{{ $loan->id }}
                                        </p>

                                        @foreach ($loan->details as $detail)
                                            <p class="mt-1 truncate text-sm text-slate-600">
                                                {{ $detail->book->title }}
                                                <span class="text-slate-400">
                                                    × {{ $detail->quantity }}
                                                </span>
                                            </p>
                                        @endforeach

                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-800">
                                        {{ $loan->user->name }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $loan->user->email }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <p class="text-sm font-semibold text-slate-700">
                                        {{ $loan->loan_date?->format('d M Y') }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-500">
                                        Jatuh tempo:
                                        {{ $loan->due_date?->format('d M Y') }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $currentStatus['class'] }}">
                                        {{ $currentStatus['label'] }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <a
                                        href="{{ route('admin.loans.show', $loan) }}"
                                        class="inline-flex rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700"
                                    >
                                        Detail
                                    </a>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">

                                    <div class="text-4xl">
                                        📚
                                    </div>

                                    <h3 class="mt-3 font-bold text-slate-900">
                                        Tidak Ada Data
                                    </h3>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Tidak ditemukan peminjaman yang sesuai.
                                    </p>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>
        </div>

        {{-- Mobile --}}
        <div class="space-y-4 lg:hidden">

            @forelse ($loans as $loan)

                @php
                    $statusConfig = [
                        'pending' => [
                            'label' => 'Pending',
                            'class' => 'bg-amber-100 text-amber-700',
                        ],

                        'approved' => [
                            'label' => 'Disetujui',
                            'class' => 'bg-blue-100 text-blue-700',
                        ],

                        'borrowed' => [
                            'label' => 'Sedang Dipinjam',
                            'class' => 'bg-indigo-100 text-indigo-700',
                        ],

                        'return_pending' => [
                            'label' => 'Menunggu Pengembalian',
                            'class' => 'bg-orange-100 text-orange-700',
                        ],

                        'returned' => [
                            'label' => 'Dikembalikan',
                            'class' => 'bg-emerald-100 text-emerald-700',
                        ],

                        'rejected' => [
                            'label' => 'Ditolak',
                            'class' => 'bg-red-100 text-red-700',
                        ],
                    ];

                    $currentStatus = $statusConfig[$loan->status] ?? [
                        'label' => ucfirst($loan->status),
                        'class' => 'bg-slate-100 text-slate-700',
                    ];
                @endphp

                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">

                    <div class="flex items-start justify-between gap-3">

                        <div>
                            <p class="text-xs font-semibold text-slate-400">
                                PEMINJAMAN #{{ $loan->id }}
                            </p>

                            <h2 class="mt-1 font-bold text-slate-900">
                                {{ $loan->user->name }}
                            </h2>

                            <p class="mt-0.5 text-xs text-slate-500">
                                {{ $loan->user->email }}
                            </p>
                        </div>

                        <span class="shrink-0 rounded-full px-3 py-1 text-xs font-bold {{ $currentStatus['class'] }}">
                            {{ $currentStatus['label'] }}
                        </span>

                    </div>

                    <div class="mt-4 space-y-2">

                        @foreach ($loan->details as $detail)

                            <div class="rounded-xl bg-slate-50 p-3">

                                <p class="text-sm font-semibold text-slate-800">
                                    {{ $detail->book->title }}
                                </p>

                                <p class="mt-1 text-xs text-slate-500">
                                    {{ $detail->book->code }}
                                    ·
                                    Jumlah {{ $detail->quantity }}
                                </p>

                            </div>

                        @endforeach

                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-3 text-xs">

                        <div>
                            <p class="text-slate-400">
                                Tanggal Pinjam
                            </p>

                            <p class="mt-1 font-semibold text-slate-700">
                                {{ $loan->loan_date?->format('d M Y') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-slate-400">
                                Jatuh Tempo
                            </p>

                            <p class="mt-1 font-semibold text-slate-700">
                                {{ $loan->due_date?->format('d M Y') }}
                            </p>
                        </div>

                    </div>

                    <a
                        href="{{ route('admin.loans.show', $loan) }}"
                        class="mt-4 block rounded-xl bg-indigo-600 px-4 py-2.5 text-center text-sm font-semibold text-white transition hover:bg-indigo-700"
                    >
                        Lihat Detail
                    </a>

                </div>

            @empty

                <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">

                    <div class="text-4xl">
                        📚
                    </div>

                    <h3 class="mt-3 font-bold text-slate-900">
                        Tidak Ada Data
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Tidak ditemukan peminjaman yang sesuai.
                    </p>

                </div>

            @endforelse

        </div>

        {{-- Pagination --}}
        @if ($loans->hasPages())
            <div class="mt-6">
                {{ $loans->links() }}
            </div>
        @endif

    </div>
</div>
@endsection