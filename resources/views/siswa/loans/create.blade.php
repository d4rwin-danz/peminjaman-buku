@extends('layouts.app')

@section('title', 'Ajukan Peminjaman')

@section('content')

<div class="mx-auto max-w-3xl space-y-6">

    {{-- Header --}}
    <div>
        <a
            href="{{ route('siswa.books.show', $book) }}"
            class="text-sm font-semibold text-blue-600 hover:text-blue-800"
        >
            ← Kembali ke Detail Buku
        </a>

        <h1 class="mt-4 text-3xl font-bold text-gray-800">
            Ajukan Peminjaman
        </h1>

        <p class="mt-2 text-gray-500">
            Lengkapi data berikut untuk mengajukan peminjaman buku.
        </p>
    </div>


    {{-- Informasi Buku --}}
    <div class="rounded-2xl bg-white p-6 shadow-sm border border-gray-100">

        <div class="flex flex-col gap-5 sm:flex-row">

            <div class="h-40 w-28 shrink-0 overflow-hidden rounded-lg bg-gray-100">

                @if ($book->cover)

                    <img
                        src="{{ asset('storage/' . $book->cover) }}"
                        alt="{{ $book->title }}"
                        class="h-full w-full object-cover"
                    >

                @else

                    <div class="flex h-full items-center justify-center text-4xl">
                        📚
                    </div>

                @endif

            </div>


            <div>

                <span class="inline-block rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                    {{ $book->category->name }}
                </span>

                <h2 class="mt-3 text-xl font-bold text-gray-800">
                    {{ $book->title }}
                </h2>

                <p class="mt-1 text-gray-500">
                    {{ $book->author }}
                </p>

                <p class="mt-3 text-sm text-gray-500">
                    Stok tersedia:
                    <strong class="text-green-600">
                        {{ $book->stock }}
                    </strong>
                </p>

            </div>

        </div>

    </div>


    {{-- Form --}}
    <div class="rounded-2xl bg-white p-6 shadow-sm border border-gray-100">

        <form
            action="{{ route('siswa.loans.store', $book) }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-6"
        >

            @csrf


            {{-- Jumlah --}}
            <div>

                <label
                    for="quantity"
                    class="mb-2 block text-sm font-semibold text-gray-700"
                >
                    Jumlah Buku
                </label>

                <input
                    type="number"
                    id="quantity"
                    name="quantity"
                    min="1"
                    max="{{ min($book->stock, config('library.max_books_per_loan')) }}"
                    value="{{ old('quantity', 1) }}"
                    required
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500"
                >

                <p class="mt-1 text-xs text-gray-500">
                    Maksimal {{ config('library.max_books_per_loan') }} buku per transaksi.
                </p>

                @error('quantity')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Bukti --}}
            <div>

                <label
                    for="borrowing_proof"
                    class="mb-2 block text-sm font-semibold text-gray-700"
                >
                    Foto Bukti Peminjaman
                </label>

                <input
                    type="file"
                    id="borrowing_proof"
                    name="borrowing_proof"
                    accept="image/jpeg,image/png,image/webp"
                    required
                    class="block w-full rounded-lg border border-gray-300 bg-white text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-blue-600 file:px-4 file:py-2.5 file:font-semibold file:text-white hover:file:bg-blue-700"
                >

                <p class="mt-2 text-xs text-gray-500">
                    Format: JPG, JPEG, PNG, atau WEBP. Maksimal 5 MB.
                </p>

                @error('borrowing_proof')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Catatan --}}
            <div>

                <label
                    for="notes"
                    class="mb-2 block text-sm font-semibold text-gray-700"
                >
                    Catatan
                    <span class="font-normal text-gray-400">
                        (opsional)
                    </span>
                </label>

                <textarea
                    id="notes"
                    name="notes"
                    rows="4"
                    placeholder="Tambahkan catatan jika diperlukan..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500"
                >{{ old('notes') }}</textarea>

                @error('notes')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Tombol --}}
            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                <a
                    href="{{ route('siswa.books.show', $book) }}"
                    class="rounded-lg bg-gray-200 px-6 py-3 text-center font-semibold text-gray-700 hover:bg-gray-300"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-blue-600 px-6 py-3 font-semibold text-white hover:bg-blue-700"
                >
                    Ajukan Peminjaman
                </button>

            </div>

        </form>

    </div>

</div>

@endsection