@extends('layouts.app')

@section('title', 'Peminjaman Saya')

@section('content')
<div class="min-h-screen bg-slate-50 py-6 sm:py-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-sm font-semibold text-indigo-600">
                        E-LIBRARY
                    </p>

                    <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                        Peminjaman Saya
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Kelola dan pantau seluruh aktivitas peminjaman buku Anda.
                    </p>
                </div>

                <a
                    href="{{ route('siswa.books.index') }}"
                    class="inline-flex w-fit items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                >
                    ← Cari Buku
                </a>
            </div>
        </div>

        {{-- Statistik --}}
        <div class="mb-6 grid grid-cols-2 gap-3 lg:grid-cols-4">

            {{-- Total --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                <p class="text-xs font-medium text-slate-500 sm:text-sm">
                    Total Peminjaman
                </p>

                <p class="mt-2 text-2xl font-bold text-slate-900">
                    {{ $totalLoans }}
                </p>
            </div>

            {{-- Pending --}}
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 shadow-sm sm:p-5">
                <p class="text-xs font-medium text-amber-700 sm:text-sm">
                    Menunggu
                </p>

                <p class="mt-2 text-2xl font-bold text-amber-800">
                    {{ $pendingLoans }}
                </p>
            </div>

            {{-- Aktif --}}
            <div class="rounded-2xl border border-indigo-200 bg-indigo-50 p-4 shadow-sm sm:p-5">
                <p class="text-xs font-medium text-indigo-700 sm:text-sm">
                    Peminjaman Aktif
                </p>

                <p class="mt-2 text-2xl font-bold text-indigo-800">
                    {{ $activeLoans }}
                </p>
            </div>

            {{-- Returned --}}
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 shadow-sm sm:p-5">
                <p class="text-xs font-medium text-emerald-700 sm:text-sm">
                    Dikembalikan
                </p>

                <p class="mt-2 text-2xl font-bold text-emerald-800">
                    {{ $returnedLoans }}
                </p>
            </div>
        </div>

        {{-- Filter --}}
        <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
            <form
                action="{{ route('siswa.loans.index') }}"
                method="GET"
                class="grid gap-3 md:grid-cols-[1fr_220px_auto_auto]"
            >
                {{-- Search --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">
                        Cari Buku
                    </label>

                    <input
                        type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Cari judul atau kode buku..."
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    >
                </div>

                {{-- Status --}}
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
                            Menunggu Persetujuan
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

                {{-- Search --}}
                <div class="flex items-end">
                    <button
                        type="submit"
                        class="w-full rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 md:w-auto"
                    >
                        Cari
                    </button>
                </div>

                {{-- Reset --}}
                <div class="flex items-end">
                    <a
                        href="{{ route('siswa.loans.index') }}"
                        class="w-full rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50 md:w-auto"
                    >
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Daftar --}}
        <div class="space-y-4">

            @forelse ($loans as $loan)

                @php
                    $statusConfig = [
                        'pending' => [
                            'label' => 'Menunggu Persetujuan',
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

                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md">

                    <div class="p-4 sm:p-5">

                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                            {{-- Info buku --}}
                            <div class="min-w-0 flex-1">

                                <div class="mb-2 flex flex-wrap items-center gap-2">
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $currentStatus['class'] }}">
                                        {{ $currentStatus['label'] }}
                                    </span>

                                    <span class="text-xs text-slate-400">
                                        #{{ $loan->id }}
                                    </span>
                                </div>

                                @foreach ($loan->details as $detail)

                                    <div class="mb-3 flex items-start gap-3 last:mb-0">

                                        <div class="flex h-12 w-10 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-slate-100">
                                            @if ($detail->book->cover)
                                                <img
                                                    src="{{ asset('storage/' . $detail->book->cover) }}"
                                                    alt="{{ $detail->book->title }}"
                                                    class="h-full w-full object-cover"
                                                >
                                            @else
                                                <span class="text-lg">📚</span>
                                            @endif
                                        </div>

                                        <div class="min-w-0">
                                            <h2 class="truncate text-sm font-bold text-slate-900 sm:text-base">
                                                {{ $detail->book->title }}
                                            </h2>

                                            <p class="mt-0.5 text-xs text-slate-500 sm:text-sm">
                                                {{ $detail->book->code }}
                                                ·
                                                Jumlah {{ $detail->quantity }}
                                            </p>
                                        </div>
                                    </div>

                                @endforeach
                            </div>

                            {{-- Tanggal --}}
                            <div class="grid grid-cols-2 gap-4 text-sm lg:w-80">
                                <div>
                                    <p class="text-xs text-slate-400">
                                        Tanggal Pinjam
                                    </p>

                                    <p class="mt-1 font-semibold text-slate-700">
                                        {{ $loan->loan_date?->format('d M Y') }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs text-slate-400">
                                        Batas Pengembalian
                                    </p>

                                    <p class="mt-1 font-semibold text-slate-700">
                                        {{ $loan->due_date?->format('d M Y') }}
                                    </p>
                                </div>
                            </div>

                            {{-- Action --}}
                            <div class="flex flex-wrap gap-2 lg:w-auto lg:justify-end">

                                <a
                                    href="{{ route('siswa.loans.show', $loan) }}"
                                    class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                                >
                                    Detail
                                </a>

                                @if ($loan->status === 'borrowed')
                                    <a
                                        href="{{ route('siswa.loans.return.create', $loan) }}"
                                        class="rounded-xl bg-orange-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-orange-600"
                                    >
                                        Ajukan Pengembalian
                                    </a>
                                @endif

                            </div>

                        </div>

                        {{-- Catatan --}}
                        @if ($loan->notes)
                            <div class="mt-4 rounded-xl bg-slate-50 px-4 py-3">
                                <p class="text-xs font-semibold text-slate-500">
                                    Catatan
                                </p>

                                <p class="mt-1 text-sm text-slate-700">
                                    {{ $loan->notes }}
                                </p>
                            </div>
                        @endif

                    </div>
                </div>

            @empty

                <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
                    <div class="text-5xl">📚</div>

                    <h3 class="mt-4 text-lg font-bold text-slate-900">
                        Belum Ada Peminjaman
                    </h3>

                    <p class="mx-auto mt-2 max-w-md text-sm text-slate-500">
                        Belum ada data peminjaman yang sesuai dengan pencarian atau filter Anda.
                    </p>

                    <a
                        href="{{ route('siswa.books.index') }}"
                        class="mt-5 inline-flex rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700"
                    >
                        Cari Buku
                    </a>
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