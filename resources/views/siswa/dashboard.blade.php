@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- HEADER --}}
    <div class="mb-8">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <p class="text-sm font-semibold text-indigo-600">
                    E-LIBRARY
                </p>

                <h1 class="mt-1 text-3xl font-bold text-slate-900">
                    Dashboard Siswa
                </h1>

                <p class="mt-2 text-slate-500">
                    Selamat datang kembali,
                    <span class="font-semibold text-slate-700">
                        {{ $user->name }}
                    </span>
                    👋
                </p>

            </div>


            <a
                href="{{ route('siswa.books.index') }}"
                class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
            >
                📚 Cari Buku
            </a>

        </div>

    </div>


    {{-- ALERT PENDING --}}
    @if ($peminjamanPending > 0)

        <div class="mb-6 flex flex-col gap-4 rounded-2xl border border-amber-200 bg-amber-50 p-5 sm:flex-row sm:items-center sm:justify-between">

            <div class="flex items-start gap-4">

                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-xl">
                    ⏳
                </div>

                <div>

                    <h2 class="font-bold text-amber-800">
                        Ada pengajuan yang sedang diproses
                    </h2>

                    <p class="mt-1 text-sm text-amber-700">
                        Kamu memiliki {{ $peminjamanPending }}
                        pengajuan peminjaman yang menunggu persetujuan admin.
                    </p>

                </div>

            </div>


            <a
                href="{{ route('siswa.loans.index') }}"
                class="text-sm font-bold text-amber-800 hover:text-amber-900"
            >
                Lihat Peminjaman →
            </a>

        </div>

    @endif


    {{-- STATISTIK --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">

        {{-- BUKU TERSEDIA --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Buku Tersedia
                    </p>

                    <h2 class="mt-2 text-3xl font-bold text-indigo-600">
                        {{ number_format($bukuTersedia) }}
                    </h2>

                    <p class="mt-2 text-xs text-slate-400">
                        Eksemplar tersedia
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-100 text-2xl">
                    📚
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

                    <h2 class="mt-2 text-3xl font-bold text-orange-500">
                        {{ number_format($sedangDipinjam) }}
                    </h2>

                    <p class="mt-2 text-xs text-slate-400">
                        Buku yang kamu pegang
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-100 text-2xl">
                    📖
                </div>

            </div>

        </div>


        {{-- PENDING --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Menunggu
                    </p>

                    <h2 class="mt-2 text-3xl font-bold text-amber-500">
                        {{ number_format($peminjamanPending) }}
                    </h2>

                    <p class="mt-2 text-xs text-slate-400">
                        Pengajuan diproses
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 text-2xl">
                    ⏳
                </div>

            </div>

        </div>


        {{-- RIWAYAT --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Riwayat
                    </p>

                    <h2 class="mt-2 text-3xl font-bold text-green-600">
                        {{ number_format($riwayatPeminjaman) }}
                    </h2>

                    <p class="mt-2 text-xs text-slate-400">
                        Total transaksi
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 text-2xl">
                    📋
                </div>

            </div>

        </div>

    </div>


    {{-- KONTEN BAWAH --}}
    <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-3">


        {{-- PEMINJAMAN AKTIF --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 lg:col-span-2">

            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">

                <div>

                    <h2 class="font-bold text-slate-900">
                        Peminjaman Aktif
                    </h2>

                    <p class="mt-1 text-xs text-slate-400">
                        Buku yang sedang kamu pinjam
                    </p>

                </div>


                <a
                    href="{{ route('siswa.loans.index') }}"
                    class="text-sm font-semibold text-indigo-600 hover:text-indigo-800"
                >
                    Lihat semua →
                </a>

            </div>


            @if ($peminjamanAktif->count())

                <div class="divide-y divide-slate-100">

                    @foreach ($peminjamanAktif as $loan)

                        <div class="px-6 py-5">

                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                                <div class="space-y-3">

                                    @foreach ($loan->details as $detail)

                                        <div class="flex items-center gap-4">

                                            <div class="h-16 w-12 shrink-0 overflow-hidden rounded-lg bg-slate-100">

                                                @if ($detail->book->cover)

                                                    <img
                                                        src="{{ asset('storage/' . $detail->book->cover) }}"
                                                        alt="{{ $detail->book->title }}"
                                                        class="h-full w-full object-cover"
                                                    >

                                                @else

                                                    <div class="flex h-full items-center justify-center">
                                                        📚
                                                    </div>

                                                @endif

                                            </div>


                                            <div>

                                                <h3 class="font-semibold text-slate-800">
                                                    {{ $detail->book->title }}
                                                </h3>

                                                <p class="mt-1 text-xs text-slate-400">
                                                    {{ $detail->book->author }}
                                                    •
                                                    Jumlah {{ $detail->quantity }}
                                                </p>

                                            </div>

                                        </div>

                                    @endforeach

                                </div>


                                <div class="shrink-0 sm:text-right">

                                    <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                        📖 Sedang Dipinjam
                                    </span>

                                    <p class="mt-2 text-xs text-slate-400">
                                        Batas kembali
                                    </p>

                                    <p class="font-semibold text-slate-700">
                                        {{ $loan->due_date?->format('d M Y') ?? '-' }}
                                    </p>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="px-6 py-12 text-center">

                    <div class="text-5xl">
                        📖
                    </div>

                    <h3 class="mt-3 font-bold text-slate-700">
                        Belum ada buku yang dipinjam
                    </h3>

                    <p class="mt-1 text-sm text-slate-400">
                        Yuk cari buku menarik untuk dibaca.
                    </p>

                    <a
                        href="{{ route('siswa.books.index') }}"
                        class="mt-5 inline-flex rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700"
                    >
                        Lihat Katalog
                    </a>

                </div>

            @endif

        </div>


        {{-- MENU CEPAT --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

            <h2 class="font-bold text-slate-900">
                Menu Cepat
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Akses fitur perpustakaan.
            </p>


            <div class="mt-6 space-y-3">

                {{-- KATALOG --}}
                <a
                    href="{{ route('siswa.books.index') }}"
                    class="group flex items-center gap-4 rounded-xl border border-slate-200 p-4 transition hover:border-indigo-300 hover:bg-indigo-50"
                >

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-indigo-100 text-xl">
                        📚
                    </div>

                    <div>

                        <p class="font-semibold text-slate-800 group-hover:text-indigo-600">
                            Katalog Buku
                        </p>

                        <p class="text-xs text-slate-400">
                            Cari buku yang tersedia
                        </p>

                    </div>

                </a>


                {{-- PEMINJAMAN --}}
                <a
                    href="{{ route('siswa.loans.index') }}"
                    class="group flex items-center gap-4 rounded-xl border border-slate-200 p-4 transition hover:border-blue-300 hover:bg-blue-50"
                >

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-xl">
                        📋
                    </div>

                    <div>

                        <p class="font-semibold text-slate-800 group-hover:text-blue-600">
                            Peminjaman Saya
                        </p>

                        <p class="text-xs text-slate-400">
                            Lihat status peminjaman
                        </p>

                    </div>

                </a>


                {{-- PENDING --}}
                @if ($peminjamanPending > 0)

                    <a
                        href="{{ route('siswa.loans.index') }}"
                        class="flex items-center justify-between rounded-xl bg-amber-50 p-4"
                    >

                        <div class="flex items-center gap-3">

                            <span class="text-xl">
                                🔔
                            </span>

                            <div>

                                <p class="font-semibold text-amber-800">
                                    Pengajuan Diproses
                                </p>

                                <p class="text-xs text-amber-600">
                                    Menunggu persetujuan
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


    {{-- TRANSAKSI TERBARU --}}
    <div class="mt-8 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">

        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">

            <div>

                <h2 class="font-bold text-slate-900">
                    Aktivitas Terbaru
                </h2>

                <p class="mt-1 text-xs text-slate-400">
                    Riwayat aktivitas peminjaman kamu
                </p>

            </div>

            <a
                href="{{ route('siswa.loans.index') }}"
                class="text-sm font-semibold text-indigo-600 hover:text-indigo-800"
            >
                Lihat semua →
            </a>

        </div>


        @if ($peminjamanTerbaru->count())

            <div class="divide-y divide-slate-100">

                @foreach ($peminjamanTerbaru as $loan)

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
                            'pending' => 'Menunggu Persetujuan',
                            'approved' => 'Disetujui',
                            'borrowed' => 'Sedang Dipinjam',
                            'returned' => 'Dikembalikan',
                            'rejected' => 'Ditolak',
                            default => ucfirst($loan->status),
                        };

                    @endphp


                    <div class="flex flex-col gap-3 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">

                        <div>

                            <p class="font-semibold text-slate-800">
                                Peminjaman #{{ $loan->id }}
                            </p>

                            <div class="mt-1">

                                @foreach ($loan->details as $detail)

                                    <p class="text-sm text-slate-500">
                                        📖 {{ $detail->book->title }}
                                        <span class="text-slate-400">
                                            × {{ $detail->quantity }}
                                        </span>
                                    </p>

                                @endforeach

                            </div>

                        </div>


                        <div class="flex items-center gap-4">

                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>

                            <span class="text-xs text-slate-400">
                                {{ $loan->created_at?->format('d M Y') }}
                            </span>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="px-6 py-12 text-center">

                <div class="text-5xl">
                    📋
                </div>

                <p class="mt-3 font-semibold text-slate-700">
                    Belum ada aktivitas
                </p>

                <p class="mt-1 text-sm text-slate-400">
                    Aktivitas peminjaman akan muncul di sini.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection