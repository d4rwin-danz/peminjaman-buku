@extends('layouts.app')

@section('title', $book->title)

@section('content')

@php
    $rackLocations = [
        'utara_1' => 'Rak Utara 1',
        'utara_2' => 'Rak Utara 2',
        'utara_3' => 'Rak Utara 3',
        'utara_4' => 'Rak Utara 4',
        'utara_5' => 'Rak Utara 5',
        'utara_6' => 'Rak Utara 6',
        'barat_1' => 'Rak Barat 1',
        'barat_2' => 'Rak Barat 2',
        'barat_3' => 'Rak Barat 3',
        'barat_4' => 'Rak Barat 4',
        'barat_5' => 'Rak Barat 5',
        'barat_6' => 'Rak Barat 6',
        'selatan_1' => 'Rak Selatan 1',
        'selatan_2' => 'Rak Selatan 2',
    ];

    $currentRack = $book->rack_location;
    $currentRackName = $rackLocations[$currentRack] ?? null;
@endphp

<div class="space-y-6">

    <a
        href="{{ route('siswa.books.index') }}"
        class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 transition hover:text-indigo-600"
    >
        ← Kembali ke katalog
    </a>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm">

        <div class="grid grid-cols-1 lg:grid-cols-3">

            <div class="bg-gray-100 p-6 sm:p-8 lg:border-r lg:border-gray-200">

                <div class="space-y-4 lg:sticky lg:top-24">

                    <div class="flex justify-center">
                        @if ($book->cover)
                            <img
                                src="{{ asset(str_starts_with($book->cover, 'uploads/') ? $book->cover : 'storage/' . $book->cover) }}"
                                alt="{{ $book->title }}"
                                class="aspect-[7/10] w-48 rounded-xl object-cover shadow-lg sm:w-56"
                            >
                        @else
                            <div class="flex aspect-[7/10] w-48 items-center justify-center rounded-xl bg-gray-200 text-center text-gray-400 sm:w-56">
                                <div>
                                    <svg class="mx-auto h-10 w-10 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 19.5V5a2 2 0 0 1 2-2h13v16H6a2 2 0 0 0-2 2.5Z"/><path d="M4 19.5A2 2 0 0 1 6 18h13"/></svg>
                                    <p class="mt-3 text-sm">Tidak ada cover</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Info singkat --}}
                    <dl class="divide-y divide-gray-200 rounded-xl bg-white px-4 text-sm ring-1 ring-gray-200">
                        <div class="flex items-center justify-between gap-3 py-2.5">
                            <dt class="text-gray-500">Status</dt>
                            <dd>
                                @if ($book->stock > 0)
                                    <span class="rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-700">Tersedia &middot; {{ $book->stock }}</span>
                                @else
                                    <span class="rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-semibold text-red-700">Habis</span>
                                @endif
                            </dd>
                        </div>
                        <div class="flex items-center justify-between gap-3 py-2.5">
                            <dt class="text-gray-500">Lokasi</dt>
                            <dd class="font-semibold text-gray-800">{{ $currentRackName ?? 'Tanya petugas' }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3 py-2.5">
                            <dt class="text-gray-500">Sudah dipinjam</dt>
                            <dd class="font-semibold text-gray-800">{{ $borrowCount }}&times;</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3 py-2.5">
                            <dt class="text-gray-500">Lama pinjam</dt>
                            <dd class="font-semibold text-gray-800">{{ config('library.loan_duration_days') }} hari</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3 py-2.5">
                            <dt class="text-gray-500">Maks. per transaksi</dt>
                            <dd class="font-semibold text-gray-800">{{ config('library.max_books_per_loan') }} buku</dd>
                        </div>
                    </dl>

                    {{-- Buku serupa --}}
                    @if ($relatedBooks->isNotEmpty())
                        <div class="rounded-xl bg-white p-4 ring-1 ring-gray-200">
                            <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Buku Serupa</p>

                            <div class="mt-3 space-y-3">
                                @foreach ($relatedBooks as $rb)
                                    <a href="{{ route('siswa.books.show', $rb) }}" class="flex items-center gap-3 rounded-lg transition hover:bg-gray-50">
                                        <div class="h-14 w-10 shrink-0 overflow-hidden rounded bg-gray-200">
                                            @if ($rb->cover)
                                                <img src="{{ asset(str_starts_with($rb->cover, 'uploads/') ? $rb->cover : 'storage/' . $rb->cover) }}" alt="" loading="lazy" class="h-full w-full object-cover">
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="line-clamp-2 text-sm font-semibold leading-snug text-gray-800">{{ $rb->title }}</p>
                                            <p class="mt-0.5 text-xs {{ $rb->stock > 0 ? 'text-green-600' : 'text-red-500' }}">{{ $rb->stock > 0 ? 'Tersedia' : 'Habis' }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

            </div>

            <div class="p-6 sm:p-8 lg:col-span-2">

                <span class="inline-block rounded-full bg-indigo-100 px-3 py-1 text-sm font-semibold text-indigo-700">
                    {{ $book->category->name ?? 'Tanpa Kategori' }}
                </span>

                <h1 class="mt-4 text-3xl font-bold text-gray-900">
                    {{ $book->title }}
                </h1>

                <p class="mt-2 text-lg text-gray-500">
                    {{ $book->author }}
                </p>

                <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2">

                    <div class="rounded-xl bg-gray-50 p-4">
                        <p class="text-xs text-gray-400">Kode Buku</p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $book->code }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-gray-50 p-4">
                        <p class="text-xs text-gray-400">ISBN</p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $book->isbn ?: '-' }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-gray-50 p-4">
                        <p class="text-xs text-gray-400">Penerbit</p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $book->publisher ?: '-' }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-gray-50 p-4">
                        <p class="text-xs text-gray-400">Tahun Terbit</p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $book->publication_year ?: '-' }}
                        </p>
                    </div>

                </div>

                <div class="mt-6 rounded-xl border border-gray-200 p-5">

                    <div class="flex items-center justify-between gap-4">

                        <div>
                            <p class="text-sm text-gray-500">
                                Ketersediaan
                            </p>

                            @if ($book->stock > 0)
                                <p class="mt-1 text-xl font-bold text-green-600">
                                    {{ $book->stock }} buku tersedia
                                </p>
                            @else
                                <p class="mt-1 text-xl font-bold text-red-600">
                                    Stok buku habis
                                </p>
                            @endif
                        </div>

                    </div>

                </div>

                <div class="mt-6 rounded-xl border border-indigo-100 bg-indigo-50 p-5">

                    <p class="text-sm font-medium text-indigo-600">
                        Lokasi Rak Buku
                    </p>

                    @if ($currentRackName)
                        <p class="mt-1 text-xl font-bold text-indigo-800">
                            {{ $currentRackName }}
                        </p>

                        <p class="mt-2 text-sm text-indigo-700">
                            Cari rak yang disorot pada peta di bawah.
                        </p>
                    @else
                        <p class="mt-1 font-semibold text-gray-600">
                            Lokasi belum ditentukan
                        </p>

                        <p class="mt-2 text-sm text-gray-500">
                            Silakan tanyakan lokasi buku kepada petugas perpustakaan.
                        </p>
                    @endif

                </div>

                <div class="mt-6">

                    <h2 class="text-lg font-bold text-gray-800">
                        Deskripsi
                    </h2>

                    <p class="mt-2 leading-relaxed text-gray-600">
                        {{ $book->description ?: 'Tidak ada deskripsi untuk buku ini.' }}
                    </p>

                </div>

                <div class="mt-8">

                    @guest
                        {{-- Belum login: tombol pinjam mengarahkan ke login, lalu kembali ke form peminjaman --}}
                        <a
                            href="{{ route('siswa.loans.create', ['book' => $book->id]) }}"
                            class="block w-full rounded-lg bg-blue-600 px-5 py-3 text-center font-bold text-white shadow-sm transition duration-200 hover:bg-blue-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        >
                            Login untuk Meminjam
                        </a>

                        <p class="mt-2 text-center text-xs text-gray-400">
                            Kamu perlu login terlebih dahulu. Setelah login, kamu akan kembali ke buku ini.
                        </p>
                    @else
                        @if (in_array(auth()->user()->role, ['siswa', 'pegawai'], true))
                            <a
                                href="{{ route('siswa.loans.create', ['book' => $book->id]) }}"
                                class="block w-full rounded-lg bg-blue-600 px-5 py-3 text-center font-bold text-white shadow-sm transition duration-200 hover:bg-blue-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            >
                                Ajukan Peminjaman
                            </a>

                            <p class="mt-2 text-center text-xs text-gray-400">
                                Ketersediaan buku akan diperiksa kembali saat pengajuan.
                            </p>
                        @else
                            <p class="rounded-lg bg-gray-100 px-5 py-3 text-center text-sm text-gray-500">
                                Akun admin tidak dapat mengajukan peminjaman.
                            </p>
                        @endif
                    @endguest

                </div>

            </div>

        </div>

    </div>

    <div>
        @include('components.library-map')
    </div>

</div>

@endsection