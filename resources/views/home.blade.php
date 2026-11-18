@extends('layouts.app')

@section('title', 'E-Library - Perpustakaan Sekolah')

@section('content')

@php
    $langkah = [
        ['Pilih buku', 'Cek stok dan lokasi raknya di halaman detail.'],
        ['Masuk atau daftar', 'Dibutuhkan hanya saat kamu menekan tombol pinjam.'],
        ['Ajukan peminjaman', 'Isi jumlah buku (maksimal ' . config('library.max_books_per_loan', 3) . ') dan unggah foto bukti.'],
        ['Ambil di perpustakaan', 'Setelah admin menyetujui. Batas pinjam ' . config('library.loan_duration_days', 7) . ' hari.'],
    ];

    // Warna punggung buku [latar, teks]
    $palet = [
        ['#E2B13C', '#172641'],
        ['#F1F4F9', '#172641'],
        ['#6B87B5', '#0F1829'],
        ['#2F7A78', '#FFFFFF'],
        ['#8A4B6F', '#FFFFFF'],
        ['#C3CFE3', '#172641'],
        ['#4F8A5B', '#FFFFFF'],
    ];
@endphp

<div class="space-y-14">

    {{-- HERO: rak buku --}}
    <section class="overflow-hidden rounded-3xl bg-ink-900 text-white" style="--hole:#172641">

        <div class="grid items-center gap-10 px-6 pt-10 sm:px-10 sm:pt-14 lg:grid-cols-5">

            <div class="lg:col-span-2">
                <h1 class="text-4xl font-semibold leading-[1.1] sm:text-5xl">
                    Rak bukumu ada di sini, tinggal dicari
                </h1>

                <p class="mt-5 max-w-md leading-relaxed text-ink-200">
                    {{ number_format($totalJudul) }} judul dan {{ number_format($totalStok) }} eksemplar
                    siap dipinjam. Lihat-lihat tidak perlu akun. Login hanya saat kamu mau meminjam.
                </p>
            </div>

            <form
                method="GET"
                action="{{ route('siswa.books.index') }}"
                class="catalog-card relative rounded-xl p-6 pb-12 text-slate-800 sm:p-7 sm:pb-14 lg:col-span-3"
            >
                <label for="home-search" class="block text-sm font-semibold text-ink-900">
                    Judul, penulis, atau kode buku
                </label>

                <input
                    id="home-search"
                    type="search"
                    name="search"
                    placeholder="Contoh: Laskar Pelangi"
                    class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-base placeholder:text-slate-400 focus:border-ink-600 focus:outline-none focus:ring-2 focus:ring-ink-200"
                >

                <div class="mt-4 flex flex-col gap-3 sm:flex-row">
                    <div class="flex-1">
                        <label for="home-category" class="sr-only">Kategori</label>
                        <select
                            id="home-category"
                            name="category"
                            class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-700 focus:border-ink-600 focus:outline-none focus:ring-2 focus:ring-ink-200"
                        >
                            <option value="">Semua kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button
                        type="submit"
                        class="rounded-lg bg-ink-700 px-6 py-3 font-semibold text-white transition-colors hover:bg-ink-800"
                    >
                        Cari buku
                    </button>
                </div>
            </form>

        </div>

        {{-- Rak: punggung buku dari data buku terbaru --}}
        <div class="mt-10 sm:mt-14">

            <div class="flex items-end gap-1.5 overflow-x-auto px-6 pb-0 pt-6 sm:px-10" role="list" aria-label="Buku terbaru di rak">
                @forelse ($shelfBooks as $i => $book)
                    @php
                        $h = crc32($book->id . '|' . $book->title);
                        $tinggi = 150 + ($h % 80);
                        $lebar = 40 + (($h >> 4) % 24);
                        [$bg, $fg] = $palet[($h >> 8) % count($palet)];
                    @endphp

                    <a
                        href="{{ route('siswa.books.show', $book) }}"
                        class="spine"
                        role="listitem"
                        title="{{ $book->title }}, {{ $book->author }}"
                        aria-label="{{ $book->title }}, {{ $book->author }}"
                        style="--i: {{ $i }}; height: {{ $tinggi }}px; width: {{ $lebar }}px; background: {{ $bg }}; color: {{ $fg }};"
                    >
                        <span class="spine-title">{{ $book->title }}</span>
                    </a>
                @empty
                    <p class="pb-6 text-sm text-ink-200">Belum ada buku di rak.</p>
                @endforelse
            </div>

            {{-- papan rak --}}
            <div class="h-3 border-t-4 border-marker bg-ink-950"></div>

        </div>

    </section>


    {{-- PILIHAN DARI RAK --}}
    @if ($picks->count())
        <section>

            <div class="mb-6 flex items-end justify-between gap-4 border-b border-slate-200 pb-3">
                <h2 class="font-display text-2xl font-semibold text-ink-900">Belum tahu mau baca apa?</h2>

                <a href="{{ route('siswa.books.index') }}" class="shrink-0 text-sm font-semibold text-ink-700 hover:text-ink-900">
                    Lihat seluruh katalog
                </a>
            </div>

            <div class="grid grid-cols-2 gap-x-5 gap-y-8 sm:grid-cols-3 lg:grid-cols-4">
                @foreach ($picks as $book)
                    <x-book-tile :book="$book" />
                @endforeach
            </div>

        </section>
    @endif


    {{-- KATEGORI + CARA MEMINJAM --}}
    <section class="grid gap-12 lg:grid-cols-2">

        @if ($categories->count())
            <div>
                <h2 class="border-b border-slate-200 pb-3 font-display text-2xl font-semibold text-ink-900">
                    Telusuri per kategori
                </h2>

                <ul class="mt-2 grid sm:grid-cols-2 sm:gap-x-8">
                    @foreach ($categories as $category)
                        <li>
                            <a
                                href="{{ route('siswa.books.index', ['category' => $category->id]) }}"
                                class="flex items-baseline justify-between gap-3 border-b border-slate-200 py-3 text-slate-800 transition-colors hover:text-ink-600"
                            >
                                <span class="font-medium">{{ $category->name }}</span>
                                <span class="text-sm tabular-nums text-slate-400">{{ $category->books_count }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div>
            <h2 class="border-b border-slate-200 pb-3 font-display text-2xl font-semibold text-ink-900">
                Cara meminjam
            </h2>

            <ol class="mt-4 space-y-4">
                @foreach ($langkah as $i => [$judul, $isi])
                    <li class="flex gap-4">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-ink-700 text-sm font-semibold text-white">
                            {{ $i + 1 }}
                        </span>
                        <div>
                            <p class="font-semibold text-slate-900">{{ $judul }}</p>
                            <p class="text-sm leading-relaxed text-slate-600">{{ $isi }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>

    </section>

</div>

@endsection
