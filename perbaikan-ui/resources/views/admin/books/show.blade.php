@extends('layouts.app')

@section('title', 'Detail Buku')

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

<div class="max-w-5xl mx-auto">

    <div class="mb-6">
        <a
            href="{{ route('admin.books.index') }}"
            class="text-blue-600 hover:underline"
        >
            ← Kembali ke Data Buku
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <div class="grid grid-cols-1 md:grid-cols-3">

            <div class="bg-slate-50 p-8 flex justify-center">

                @if($book->cover)
                    <img
                        src="{{ asset(str_starts_with($book->cover, 'uploads/') ? $book->cover : 'storage/' . $book->cover) }}"
                        alt="{{ $book->title }}"
                        class="w-56 h-80 object-cover rounded-2xl shadow"
                    >
                @else
                    <div class="w-56 h-80 bg-slate-200 rounded-2xl flex items-center justify-center text-slate-400">
                        Tidak ada cover
                    </div>
                @endif

            </div>

            <div class="md:col-span-2 p-8">

                <div class="mb-6">

                    <span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold mb-3">
                        {{ $book->category->name ?? 'Tanpa Kategori' }}
                    </span>

                    <h1 class="text-3xl font-bold text-slate-900">
                        {{ $book->title }}
                    </h1>

                    <p class="text-slate-500 mt-2">
                        {{ $book->author }}
                    </p>

                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    <div>
                        <p class="text-sm text-slate-500">Kode Buku</p>
                        <p class="font-semibold mt-1">{{ $book->code }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500">ISBN</p>
                        <p class="font-semibold mt-1">{{ $book->isbn ?: '-' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500">Penerbit</p>
                        <p class="font-semibold mt-1">{{ $book->publisher ?: '-' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500">Tahun Terbit</p>
                        <p class="font-semibold mt-1">{{ $book->publication_year ?: '-' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500">Stok</p>

                        @if($book->stock > 0)
                            <p class="font-semibold text-green-600 mt-1">
                                {{ $book->stock }} buku tersedia
                            </p>
                        @else
                            <p class="font-semibold text-red-600 mt-1">
                                Stok habis
                            </p>
                        @endif
                    </div>

                    <div>
                        <p class="text-sm text-slate-500">Lokasi Rak</p>

                        @if($currentRackName)
                            <p class="font-semibold text-blue-700 mt-1">
                                {{ $currentRackName }}
                            </p>
                        @else
                            <p class="font-semibold text-slate-400 mt-1">
                                Belum ditentukan
                            </p>
                        @endif
                    </div>

                </div>

                <div class="border-t mt-8 pt-6">

                    <p class="text-sm text-slate-500 mb-2">
                        Deskripsi
                    </p>

                    <p class="text-slate-700 leading-relaxed">
                        {{ $book->description ?: 'Tidak ada deskripsi.' }}
                    </p>

                </div>

                <div class="mt-8 flex flex-wrap gap-3">

                    <a
                        href="{{ route('admin.books.edit', $book) }}"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-3 rounded-xl font-semibold"
                    >
                        Edit Buku
                    </a>

                    <a
                        href="{{ route('admin.books.index') }}"
                        class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-5 py-3 rounded-xl font-semibold"
                    >
                        Kembali
                    </a>

                </div>

            </div>

        </div>

    </div>

    <div class="mt-8">
        @include('components.library-map')
    </div>

</div>

@endsection