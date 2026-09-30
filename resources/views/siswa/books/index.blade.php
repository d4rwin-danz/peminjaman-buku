@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-800">
            Katalog Buku
        </h1>
        <p class="text-gray-500">
            Temukan buku yang ingin kamu pinjam.
        </p>
    </div>

    {{-- Filter --}}
    <div class="rounded-xl bg-white p-5 shadow">
        <form method="GET" action="{{ route('siswa.books.index') }}"
              class="grid gap-4 md:grid-cols-3">

            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-medium text-gray-700">
                    Cari Buku
                </label>

                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari judul, kode, atau penulis..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-blue-500"
                >
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">
                    Kategori
                </label>

                <select
                    name="category"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5"
                >
                    <option value="">Semua Kategori</option>

                    @foreach ($categories as $category)
                        <option
                            value="{{ $category->id }}"
                            @selected($categoryId == $category->id)
                        >
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-3 flex gap-2">
                <button
                    type="submit"
                    class="rounded-lg bg-blue-600 px-5 py-2.5 font-semibold text-white hover:bg-blue-700"
                >
                    Cari Buku
                </button>

                <a
                    href="{{ route('siswa.books.index') }}"
                    class="rounded-lg bg-gray-200 px-5 py-2.5 font-semibold text-gray-700 hover:bg-gray-300"
                >
                    Reset
                </a>
            </div>

        </form>
    </div>

    {{-- Daftar Buku --}}
    @if ($books->count())
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

            @foreach ($books as $book)
                <div class="overflow-hidden rounded-2xl bg-white shadow transition hover:-translate-y-1 hover:shadow-lg">

                    {{-- Cover --}}
                    <div class="h-56 bg-gray-100">
                        @if ($book->cover)
                            <img
                                src="{{ asset('storage/' . $book->cover) }}"
                                alt="{{ $book->title }}"
                                class="h-full w-full object-cover"
                            >
                        @else
                            <div class="flex h-full items-center justify-center">
                                <div class="text-center text-gray-400">
                                    <div class="text-5xl">📚</div>
                                    <p class="mt-2 text-sm">Tidak ada cover</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="p-5">

                        <span class="inline-block rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                            {{ $book->category->name }}
                        </span>

                        <h2 class="mt-3 line-clamp-2 text-lg font-bold text-gray-800">
                            {{ $book->title }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            {{ $book->author }}
                        </p>

                        <div class="mt-4 flex items-center justify-between">

                            <span class="text-sm text-gray-500">
                                Stok:
                                <strong class="{{ $book->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $book->stock }}
                                </strong>
                            </span>

                            @if ($book->stock > 0)
                                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                    Tersedia
                                </span>
                            @else
                                <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                    Habis
                                </span>
                            @endif

                        </div>

                        <a
                            href="{{ route('siswa.books.show', $book) }}"
                            class="mt-5 block rounded-lg bg-blue-600 px-4 py-2.5 text-center font-semibold text-white hover:bg-blue-700"
                        >
                            Lihat Detail
                        </a>

                    </div>
                </div>
            @endforeach

        </div>

        {{-- Pagination --}}
        <div>
            {{ $books->links() }}
        </div>

    @else

        <div class="rounded-xl bg-white p-10 text-center shadow">
            <div class="text-5xl">📚</div>

            <h2 class="mt-4 text-xl font-bold text-gray-800">
                Buku tidak ditemukan
            </h2>

            <p class="mt-2 text-gray-500">
                Coba gunakan kata pencarian atau kategori yang berbeda.
            </p>
        </div>

    @endif

</div>
@endsection