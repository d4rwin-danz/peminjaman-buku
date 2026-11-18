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
                        src="{{ asset(str_starts_with($book->cover, 'uploads/') ? $book->cover : 'storage/' . $book->cover) }}"
                        alt="{{ $book->title }}"
                        class="h-full w-full object-cover"
                    >

                @else

                    <svg class="mx-auto h-10 w-10 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 19.5V5a2 2 0 0 1 2-2h13v16H6a2 2 0 0 0-2 2.5Z"/><path d="M4 19.5A2 2 0 0 1 6 18h13"/></svg>

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


            {{-- Info invoice --}}
            <div class="flex gap-3 rounded-xl border border-indigo-100 bg-indigo-50 p-4">

                <svg class="mt-0.5 h-5 w-5 shrink-0 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><path d="M14 14h3v3h-3zM20 14v3M14 20h3M20 20h1"/></svg>

                <div class="text-sm text-indigo-900">
                    <p class="font-semibold">
                        Tidak perlu upload foto bukti
                    </p>

                    <p class="mt-1 text-indigo-700">
                        Setelah mengajukan, kamu otomatis mendapat
                        <strong>invoice berisi kode peminjaman &amp; QR code</strong>.
                        Cukup tunjukkan invoice tersebut ke petugas perpustakaan.
                    </p>
                </div>

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