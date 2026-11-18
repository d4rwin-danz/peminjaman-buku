@extends('layouts.app')

@section('title', 'Katalog Buku')

@section('content')
<div class="space-y-8">

    <div>
        <h1 class="text-3xl font-semibold text-ink-900">Katalog buku</h1>
        <p class="mt-2 text-slate-600">
            {{ number_format($books->total()) }} buku
            @if ($search || $categoryId)
                sesuai pencarianmu.
            @else
                di perpustakaan.
            @endif
        </p>
    </div>

    {{-- Filter --}}
    <form
        method="GET"
        action="{{ route('siswa.books.index') }}"
        class="grid gap-4 rounded-xl bg-white p-4 shadow-sm sm:p-5 md:grid-cols-6"
    >
        <div class="md:col-span-3">
            <label for="search" class="mb-1 block text-sm font-medium text-slate-700">Cari buku</label>
            <input
                id="search"
                type="search"
                name="search"
                value="{{ $search }}"
                placeholder="Judul, kode, atau penulis"
                class="w-full rounded-lg border border-slate-300 px-4 py-2.5 placeholder:text-slate-400 focus:border-ink-600 focus:outline-none focus:ring-2 focus:ring-ink-200"
            >
        </div>

        <div class="md:col-span-2">
            <label for="category" class="mb-1 block text-sm font-medium text-slate-700">Kategori</label>
            <select
                id="category"
                name="category"
                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 focus:border-ink-600 focus:outline-none focus:ring-2 focus:ring-ink-200"
            >
                <option value="">Semua kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected($categoryId == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 rounded-lg bg-ink-700 px-5 py-2.5 font-semibold text-white transition-colors hover:bg-ink-800">
                Cari
            </button>

            @if ($search || $categoryId)
                <a href="{{ route('siswa.books.index') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 font-medium text-slate-700 hover:bg-slate-50">
                    Reset
                </a>
            @endif
        </div>
    </form>

    {{-- Daftar buku --}}
    @if ($books->count())
        <div class="grid grid-cols-2 gap-x-5 gap-y-8 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
            @foreach ($books as $book)
                <x-book-tile :book="$book" />
            @endforeach
        </div>

        <div>
            {{ $books->links() }}
        </div>
    @else
        <div class="rounded-xl bg-white p-10 text-center shadow-sm">
            <h2 class="font-display text-xl font-semibold text-ink-900">Buku tidak ditemukan</h2>
            <p class="mt-2 text-slate-600">
                Coba kata kunci lain, atau pilih "Semua kategori".
            </p>
            <a href="{{ route('siswa.books.index') }}" class="mt-5 inline-block rounded-lg border border-slate-300 px-5 py-2.5 font-medium text-slate-700 hover:bg-slate-50">
                Tampilkan semua buku
            </a>
        </div>
    @endif

</div>
@endsection
