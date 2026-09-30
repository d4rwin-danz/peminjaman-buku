@extends('layouts.app')

@section('title', $book->title)

@section('content')

<div class="space-y-6">

{{-- ================= BACK ================= --}}
<a
    href="{{ route('siswa.books.index') }}"
    class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 transition hover:text-indigo-600"
>
    ← Kembali ke katalog
</a>


{{-- ================= MAIN CARD ================= --}}
<div class="overflow-hidden rounded-2xl bg-white shadow-sm">

    <div class="grid grid-cols-1 lg:grid-cols-3">


        {{-- ================= COVER ================= --}}
        <div class="flex min-h-[450px] items-center justify-center bg-gray-100">

            @if ($book->cover)

                <img
                    src="{{ asset('storage/' . $book->cover) }}"
                    alt="{{ $book->title }}"
                    class="h-full max-h-[500px] w-full object-contain p-8"
                >

            @else

                <div class="text-center text-gray-400">

                    <div class="text-7xl">
                        📚
                    </div>

                    <p class="mt-3">
                        Tidak ada cover
                    </p>

                </div>

            @endif

        </div>


        {{-- ================= DETAIL ================= --}}
        <div class="p-8 lg:col-span-2">


            {{-- ================= CATEGORY ================= --}}
            <span
                class="inline-block rounded-full bg-indigo-100 px-3 py-1 text-sm font-semibold text-indigo-700"
            >
                {{ $book->category->name }}
            </span>


            {{-- ================= TITLE ================= --}}
            <h1 class="mt-4 text-3xl font-bold text-gray-900">
                {{ $book->title }}
            </h1>


            {{-- ================= AUTHOR ================= --}}
            <p class="mt-2 text-lg text-gray-500">
                {{ $book->author }}
            </p>


            {{-- ================= BOOK INFORMATION ================= --}}
            <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2">


                {{-- CODE --}}
                <div class="rounded-xl bg-gray-50 p-4">

                    <p class="text-xs text-gray-400">
                        Kode Buku
                    </p>

                    <p class="mt-1 font-semibold text-gray-800">
                        {{ $book->code }}
                    </p>

                </div>


                {{-- ISBN --}}
                <div class="rounded-xl bg-gray-50 p-4">

                    <p class="text-xs text-gray-400">
                        ISBN
                    </p>

                    <p class="mt-1 font-semibold text-gray-800">
                        {{ $book->isbn ?: '-' }}
                    </p>

                </div>


                {{-- PUBLISHER --}}
                <div class="rounded-xl bg-gray-50 p-4">

                    <p class="text-xs text-gray-400">
                        Penerbit
                    </p>

                    <p class="mt-1 font-semibold text-gray-800">
                        {{ $book->publisher ?: '-' }}
                    </p>

                </div>


                {{-- YEAR --}}
                <div class="rounded-xl bg-gray-50 p-4">

                    <p class="text-xs text-gray-400">
                        Tahun Terbit
                    </p>

                    <p class="mt-1 font-semibold text-gray-800">
                        {{ $book->publication_year ?: '-' }}
                    </p>

                </div>

            </div>


            {{-- ================= STOCK ================= --}}
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


                    <div class="text-4xl">

                        @if ($book->stock > 0)
                            ✅
                        @else
                            ❌
                        @endif

                    </div>

                </div>

            </div>


            {{-- ================= DESCRIPTION ================= --}}
            <div class="mt-6">

                <h2 class="text-lg font-bold text-gray-800">
                    Deskripsi
                </h2>

                <p class="mt-2 leading-relaxed text-gray-600">
                    {{ $book->description ?: 'Tidak ada deskripsi untuk buku ini.' }}
                </p>

            </div>


            {{-- ================= ACTION ================= --}}
            <div class="mt-8">

                {{-- TOMBOL SELALU AKTIF --}}
                <a
                    href="{{ route('siswa.loans.create', ['book' => $book->id]) }}"
                    class="block w-full rounded-lg bg-blue-600 px-5 py-3 text-center font-bold text-white shadow-sm transition duration-200 hover:bg-blue-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    📖 Ajukan Peminjaman
                </a>

                <p class="mt-2 text-center text-xs text-gray-400">
                    Ketersediaan buku akan diperiksa kembali saat pengajuan.
                </p>

            </div>


        </div>

    </div>

</div>

</div>

@endsection
