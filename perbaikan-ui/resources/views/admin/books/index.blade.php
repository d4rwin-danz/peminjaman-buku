@extends('layouts.app')

@section('title', 'Data Buku')

@section('content')

<div class="max-w-7xl mx-auto">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">

        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">
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

    @php
        $totalBuku = method_exists($books, 'total') ? $books->total() : $books->count();
        $habisHalaman = $books->getCollection()->where('stock', '<=', 0)->count();
    @endphp

    <div class="mb-6 grid grid-cols-3 gap-3">
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Judul</p>
            <p class="mt-1 font-display text-2xl font-semibold text-ink-900">{{ $totalBuku }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Stok Halaman Ini</p>
            <p class="mt-1 font-display text-2xl font-semibold text-ink-900">{{ $books->getCollection()->sum('stock') }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Stok Habis</p>
            <p class="mt-1 font-display text-2xl font-semibold {{ $habisHalaman ? 'text-red-600' : 'text-ink-900' }}">{{ $habisHalaman }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200 mb-6 p-4 sm:p-5">

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

            <div class="md:col-span-3 grid grid-cols-2 sm:flex gap-3">

                <button
                    type="submit"
                    class="bg-slate-900 hover:bg-slate-800 text-white px-5 py-3 rounded-xl font-semibold text-center"
                >
                    Cari
                </button>

                <a
                    href="{{ route('admin.books.index') }}"
                    class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-5 py-3 rounded-xl font-semibold text-center"
                >
                    Reset
                </a>

            </div>

        </form>

    </div>

    {{-- ===== Daftar kartu (HP) ===== --}}
    <div class="space-y-3 md:hidden">
        @forelse($books as $book)
            @php
                $coverUrl = $book->cover ? asset(str_starts_with($book->cover, 'uploads/') ? $book->cover : 'storage/' . $book->cover) : null;
            @endphp
            <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
                <div class="flex gap-4">
                    <div class="cover h-28 w-20 shrink-0 overflow-hidden rounded-md bg-slate-100">
                        @if($coverUrl)
                            <img src="{{ $coverUrl }}" alt="{{ $book->title }}" loading="lazy" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full items-center justify-center bg-ink-700 p-2 text-center text-[10px] font-semibold text-white">No Cover</div>
                        @endif
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-2">
                            <span class="rounded bg-slate-100 px-1.5 py-0.5 font-mono text-[11px] font-semibold text-slate-600">{{ $book->code }}</span>
                            @if($book->stock > 0)
                                <span class="rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-700">Stok {{ $book->stock }}</span>
                            @else
                                <span class="rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-semibold text-red-700">Habis</span>
                            @endif
                        </div>
                        <h3 class="mt-2 line-clamp-2 font-semibold leading-snug text-slate-900">{{ $book->title }}</h3>
                        <p class="mt-1 truncate text-sm text-slate-500">{{ $book->author }}</p>
                        @if($book->category)
                            <span class="mt-2 inline-block rounded-md bg-ink-50 px-2 py-0.5 text-xs font-medium text-ink-700">{{ $book->category->name }}</span>
                        @endif
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-3 gap-2 border-t border-slate-100 pt-3">
                    <a href="{{ route('admin.books.show', $book) }}" class="rounded-lg bg-ink-50 py-2 text-center text-sm font-semibold text-ink-700">Detail</a>
                    <a href="{{ route('admin.books.edit', $book) }}" class="rounded-lg bg-amber-50 py-2 text-center text-sm font-semibold text-amber-700">Edit</a>
                    <form action="{{ route('admin.books.destroy', $book) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full rounded-lg bg-red-50 py-2 text-sm font-semibold text-red-700">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="rounded-2xl bg-white p-10 text-center text-slate-500 ring-1 ring-slate-200">Tidak ada buku ditemukan.</div>
        @endforelse

        @if($books->hasPages())
            <div class="pt-2">{{ $books->links() }}</div>
        @endif
    </div>

    {{-- ===== Tabel (tablet / desktop) ===== --}}
    <div class="hidden md:block bg-white rounded-2xl shadow-sm ring-1 ring-slate-200 overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-left">

                <thead class="bg-slate-50 border-b">

                    <tr>
                        <th class="px-4 lg:px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Cover</th>
                        <th class="px-4 lg:px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Kode</th>
                        <th class="px-4 lg:px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Judul</th>
                        <th class="px-4 lg:px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Kategori</th>
                        <th class="px-4 lg:px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Penulis</th>
                        <th class="px-4 lg:px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Stok</th>
                        <th class="px-4 lg:px-6 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500 text-right">Aksi</th>
                    </tr>

                </thead>

                <tbody class="divide-y">

                    @forelse($books as $book)

                        <tr class="hover:bg-slate-50">

                            <td class="px-4 lg:px-6 py-3.5">

                                @if($book->cover)

                                    <img
                                        src="{{ asset(str_starts_with($book->cover, 'uploads/') ? $book->cover : 'storage/' . $book->cover) }}"
                                        alt="{{ $book->title }}"
                                        class="w-14 h-20 object-cover rounded-lg"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                    >

                                    <div
                                        class="w-14 h-20 bg-slate-100 rounded-lg items-center justify-center text-xs text-slate-400"
                                        style="display: none;"
                                    >
                                        No Cover
                                    </div>

                                @else

                                    <div class="w-14 h-20 bg-slate-100 rounded-lg flex items-center justify-center text-xs text-slate-400">
                                        No Cover
                                    </div>

                                @endif

                            </td>

                            <td class="px-4 lg:px-6 py-3.5 font-mono text-sm">
                                {{ $book->code }}
                            </td>

                            <td class="px-4 lg:px-6 py-3.5 font-semibold">
                                {{ $book->title }}
                            </td>

                            <td class="px-4 lg:px-6 py-3.5">
                                {{ $book->category->name ?? '-' }}
                            </td>

                            <td class="px-4 lg:px-6 py-3.5">
                                {{ $book->author }}
                            </td>

                            <td class="px-4 lg:px-6 py-3.5">

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

                            <td class="px-4 lg:px-6 py-3.5">

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

            <div class="px-4 lg:px-6 py-3.5 border-t">
                {{ $books->links() }}
            </div>

        @endif

    </div>

</div>

@endsection