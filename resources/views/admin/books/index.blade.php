@extends('layouts.app')

@section('title', 'Data Buku')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">

        <div>
            <h1 class="text-3xl font-bold text-slate-900">
                Data Buku
            </h1>

            <p class="text-slate-500 mt-1">
                Kelola koleksi buku perpustakaan.
            </p>
        </div>

        <a
            href="{{ route('admin.books.create') }}"
            class="inline-flex justify-center bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl font-semibold"
        >
            + Tambah Buku
        </a>

    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow mb-6 p-5">

        <form
            action="{{ route('admin.books.index') }}"
            method="GET"
            class="grid grid-cols-1 md:grid-cols-3 gap-4"
        >

            <div class="md:col-span-2">

                <label class="block text-sm font-semibold mb-2">
                    Cari Buku
                </label>

                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Judul, kode, atau penulis..."
                    class="w-full border border-slate-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500"
                >

            </div>

            <div>

                <label class="block text-sm font-semibold mb-2">
                    Kategori
                </label>

                <select
                    name="category"
                    class="w-full border border-slate-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500"
                >

                    <option value="">
                        Semua Kategori
                    </option>

                    @foreach($categories as $category)

                        <option
                            value="{{ $category->id }}"
                            @selected($categoryId == $category->id)
                        >
                            {{ $category->name }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div class="md:col-span-3 flex gap-3">

                <button
                    type="submit"
                    class="bg-slate-900 hover:bg-slate-800 text-white px-5 py-3 rounded-xl font-semibold"
                >
                    Cari
                </button>

                <a
                    href="{{ route('admin.books.index') }}"
                    class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-5 py-3 rounded-xl font-semibold"
                >
                    Reset
                </a>

            </div>

        </form>

    </div>

    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-left">

                <thead class="bg-slate-50 border-b">

                    <tr>
                        <th class="px-6 py-4">Cover</th>
                        <th class="px-6 py-4">Kode</th>
                        <th class="px-6 py-4">Judul</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Penulis</th>
                        <th class="px-6 py-4">Stok</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>

                </thead>

                <tbody class="divide-y">

                    @forelse($books as $book)

                        <tr class="hover:bg-slate-50">

                            <td class="px-6 py-4">

                                @if($book->cover)

                                    <img
                                        src="{{ asset('storage/' . $book->cover) }}"
                                        alt="{{ $book->title }}"
                                        class="w-14 h-20 object-cover rounded-lg"
                                    >

                                @else

                                    <div class="w-14 h-20 bg-slate-100 rounded-lg flex items-center justify-center text-xs text-slate-400">
                                        No Cover
                                    </div>

                                @endif

                            </td>

                            <td class="px-6 py-4 font-mono text-sm">
                                {{ $book->code }}
                            </td>

                            <td class="px-6 py-4 font-semibold">
                                {{ $book->title }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $book->category->name }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $book->author }}
                            </td>

                            <td class="px-6 py-4">

                                @if($book->stock > 0)

                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">
                                        {{ $book->stock }}
                                    </span>

                                @else

                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-semibold">
                                        Habis
                                    </span>

                                @endif

                            </td>

                            <td class="px-6 py-4">

                                <div class="flex justify-end gap-2">

                                    <a
                                        href="{{ route('admin.books.show', $book) }}"
                                        class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-2 rounded-lg text-sm font-semibold"
                                    >
                                        Detail
                                    </a>

                                    <a
                                        href="{{ route('admin.books.edit', $book) }}"
                                        class="bg-yellow-100 hover:bg-yellow-200 text-yellow-700 px-3 py-2 rounded-lg text-sm font-semibold"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('admin.books.destroy', $book) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus buku ini?')"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="bg-red-100 hover:bg-red-200 text-red-700 px-3 py-2 rounded-lg text-sm font-semibold"
                                        >
                                            Hapus
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="px-6 py-12 text-center text-slate-500"
                            >
                                Tidak ada buku ditemukan.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($books->hasPages())

            <div class="px-6 py-4 border-t">
                {{ $books->links() }}
            </div>

        @endif

    </div>

</div>

@endsection