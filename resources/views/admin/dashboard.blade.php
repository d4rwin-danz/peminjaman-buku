@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- HEADER --}}
    <div class="mb-8">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <p class="text-sm font-semibold text-indigo-600">
                    ADMINISTRATOR
                </p>

                <h1 class="mt-1 text-3xl font-bold text-slate-900">
                    Dashboard Admin
                </h1>

                <p class="mt-2 text-slate-500">
                    Selamat datang kembali,
                    <span class="font-semibold text-slate-700">
                        {{ auth()->user()->name }}
                    </span>.
                </p>

            </div>

            <div class="rounded-xl bg-white px-5 py-3 shadow-sm ring-1 ring-slate-200">

                <p class="text-xs text-slate-400">
                    Status Sistem
                </p>

                <div class="mt-1 flex items-center gap-2">

                    <span class="h-2.5 w-2.5 rounded-full bg-green-500"></span>

                    <span class="text-sm font-semibold text-green-600">
                        Sistem Aktif
                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- STATISTIK UTAMA --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">

        {{-- TOTAL BUKU --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Total Judul Buku
                    </p>

                    <h2 class="mt-2 text-3xl font-bold text-slate-900">
                        {{ number_format($totalBuku) }}
                    </h2>

                    <p class="mt-2 text-xs text-slate-400">
                        {{ number_format($totalStok) }} eksemplar tersedia
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-100 text-2xl">
                    📚
                </div>

            </div>

        </div>


        {{-- PENDING --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Menunggu Persetujuan
                    </p>

                    <h2 class="mt-2 text-3xl font-bold text-amber-600">
                        {{ number_format($peminjamanPending) }}
                    </h2>

                    <a
                        href="{{ route('admin.loans.index', ['status' => 'pending']) }}"
                        class="mt-2 inline-block text-xs font-semibold text-indigo-600 hover:text-indigo-800"
                    >
                        Lihat pengajuan →
                    </a>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 text-2xl">
                    ⏳
                </div>

            </div>

        </div>


        {{-- SEDANG DIPINJAM --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Sedang Dipinjam
                    </p>

                    <h2 class="mt-2 text-3xl font-bold text-blue-600">
                        {{ number_format($jumlahBukuDipinjam) }}
                    </h2>

                    <p class="mt-2 text-xs text-slate-400">
                        {{ $sedangDipinjam }} transaksi aktif
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-2xl">
                    📖
                </div>

            </div>

        </div>


        {{-- SISWA --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Total Siswa
                    </p>

                    <h2 class="mt-2 text-3xl font-bold text-emerald-600">
                        {{ number_format($totalSiswa) }}
                    </h2>

                    <p class="mt-2 text-xs text-slate-400">
                        Pengguna terdaftar
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-2xl">
                    👨‍🎓
                </div>

            </div>

        </div>

    </div>


    {{-- STATISTIK TRANSAKSI --}}
    <div class="mt-8 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

        <div class="mb-6">

            <h2 class="text-lg font-bold text-slate-900">
                Ringkasan Peminjaman
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Kondisi transaksi peminjaman perpustakaan saat ini.
            </p>

        </div>


        <div class="grid grid-cols-2 gap-4 md:grid-cols-5">

            {{-- PENDING --}}
            <div class="rounded-xl bg-amber-50 p-4">

                <p class="text-xs font-medium text-amber-600">
                    Pending
                </p>

                <p class="mt-2 text-2xl font-bold text-amber-700">
                    {{ $peminjamanPending }}
                </p>

            </div>


            {{-- APPROVED --}}
            <div class="rounded-xl bg-indigo-50 p-4">

                <p class="text-xs font-medium text-indigo-600">
                    Disetujui
                </p>

                <p class="mt-2 text-2xl font-bold text-indigo-700">
                    {{ $peminjamanDisetujui }}
                </p>

            </div>


            {{-- BORROWED --}}
            <div class="rounded-xl bg-blue-50 p-4">

                <p class="text-xs font-medium text-blue-600">
                    Dipinjam
                </p>

                <p class="mt-2 text-2xl font-bold text-blue-700">
                    {{ $sedangDipinjam }}
                </p>

            </div>


            {{-- RETURNED --}}
            <div class="rounded-xl bg-green-50 p-4">

                <p class="text-xs font-medium text-green-600">
                    Dikembalikan
                </p>

                <p class="mt-2 text-2xl font-bold text-green-700">
                    {{ $sudahDikembalikan }}
                </p>

            </div>


            {{-- REJECTED --}}
            <div class="rounded-xl bg-red-50 p-4">

                <p class="text-xs font-medium text-red-600">
                    Ditolak
                </p>

                <p class="mt-2 text-2xl font-bold text-red-700">
                    {{ $ditolak }}
                </p>

            </div>

        </div>

    </div>


    {{-- GRID BAWAH --}}
    <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-3">


        {{-- PEMINJAMAN TERBARU --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 lg:col-span-2">

            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">

                <div>

                    <h2 class="font-bold text-slate-900">
                        Peminjaman Terbaru
                    </h2>

                    <p class="mt-1 text-xs text-slate-400">
                        5 transaksi terakhir
                    </p>

                </div>

                <a
                    href="{{ route('admin.loans.index') }}"
                    class="text-sm font-semibold text-indigo-600 hover:text-indigo-800"
                >
                    Lihat semua →
                </a>

            </div>


            @if ($peminjamanTerbaru->count())

                <div class="divide-y divide-slate-100">

                    @foreach ($peminjamanTerbaru as $loan)

                        <div class="px-6 py-5">

                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                                <div class="min-w-0">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-100 font-bold text-indigo-600">
                                            {{ strtoupper(substr($loan->user->name, 0, 1)) }}
                                        </div>

                                        <div class="min-w-0">

                                            <p class="truncate font-semibold text-slate-800">
                                                {{ $loan->user->name }}
                                            </p>

                                            <p class="truncate text-xs text-slate-400">
                                                {{ $loan->user->email }}
                                            </p>

                                        </div>

                                    </div>


                                    <div class="mt-3 space-y-1">

                                        @foreach ($loan->details as $detail)

                                            <p class="text-sm text-slate-600">

                                                📖

                                                {{ $detail->book->title }}

                                                <span class="text-slate-400">
                                                    × {{ $detail->quantity }}
                                                </span>

                                            </p>

                                        @endforeach

                                    </div>

                                </div>


                                <div class="flex shrink-0 flex-row items-center gap-3 sm:flex-col sm:items-end">

                                    @php

                                        $statusClass = match ($loan->status) {
                                            'pending' => 'bg-amber-100 text-amber-700',
                                            'approved' => 'bg-indigo-100 text-indigo-700',
                                            'borrowed' => 'bg-blue-100 text-blue-700',
                                            'returned' => 'bg-green-100 text-green-700',
                                            'rejected' => 'bg-red-100 text-red-700',
                                            default => 'bg-slate-100 text-slate-600',
                                        };

                                        $statusLabel = match ($loan->status) {
                                            'pending' => 'Menunggu',
                                            'approved' => 'Disetujui',
                                            'borrowed' => 'Dipinjam',
                                            'returned' => 'Dikembalikan',
                                            'rejected' => 'Ditolak',
                                            default => ucfirst($loan->status),
                                        };

                                    @endphp

                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>

                                    <a
                                        href="{{ route('admin.loans.show', $loan) }}"
                                        class="text-xs font-semibold text-indigo-600 hover:text-indigo-800"
                                    >
                                        Detail
                                    </a>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="px-6 py-12 text-center">

                    <div class="text-5xl">
                        📚
                    </div>

                    <p class="mt-3 font-semibold text-slate-700">
                        Belum ada transaksi peminjaman
                    </p>

                    <p class="mt-1 text-sm text-slate-400">
                        Data peminjaman akan muncul di sini.
                    </p>

                </div>

            @endif

        </div>


        {{-- AKSI CEPAT --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

            <h2 class="font-bold text-slate-900">
                Aksi Cepat
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Kelola data perpustakaan.
            </p>


            <div class="mt-6 space-y-3">

                <a
                    href="{{ route('admin.books.index') }}"
                    class="flex items-center gap-4 rounded-xl border border-slate-200 p-4 transition hover:border-indigo-300 hover:bg-indigo-50"
                >

                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-100 text-xl">
                        📚
                    </div>

                    <div>

                        <p class="font-semibold text-slate-800">
                            Kelola Buku
                        </p>

                        <p class="text-xs text-slate-400">
                            Tambah dan edit buku
                        </p>

                    </div>

                </a>


                <a
                    href="{{ route('admin.categories.index') }}"
                    class="flex items-center gap-4 rounded-xl border border-slate-200 p-4 transition hover:border-purple-300 hover:bg-purple-50"
                >

                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100 text-xl">
                        🗂️
                    </div>

                    <div>

                        <p class="font-semibold text-slate-800">
                            Kelola Kategori
                        </p>

                        <p class="text-xs text-slate-400">
                            Atur kategori buku
                        </p>

                    </div>

                </a>


                <a
                    href="{{ route('admin.loans.index') }}"
                    class="flex items-center gap-4 rounded-xl border border-slate-200 p-4 transition hover:border-blue-300 hover:bg-blue-50"
                >

                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-xl">
                        📋
                    </div>

                    <div>

                        <p class="font-semibold text-slate-800">
                            Data Peminjaman
                        </p>

                        <p class="text-xs text-slate-400">
                            Proses pengajuan siswa
                        </p>

                    </div>

                </a>


                @if ($peminjamanPending > 0)

                    <a
                        href="{{ route('admin.loans.index', ['status' => 'pending']) }}"
                        class="flex items-center justify-between rounded-xl bg-amber-50 p-4"
                    >

                        <div class="flex items-center gap-3">

                            <span class="text-xl">
                                🔔
                            </span>

                            <div>

                                <p class="font-semibold text-amber-800">
                                    Perlu Diproses
                                </p>

                                <p class="text-xs text-amber-600">
                                    Ada pengajuan baru
                                </p>

                            </div>

                        </div>

                        <span class="rounded-full bg-amber-200 px-2.5 py-1 text-xs font-bold text-amber-800">
                            {{ $peminjamanPending }}
                        </span>

                    </a>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection

