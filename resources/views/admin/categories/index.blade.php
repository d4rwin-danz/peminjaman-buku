@extends('layouts.app')

@section('title', 'Kategori Buku')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">
                Kategori Buku
            </h1>

            <p class="text-slate-500 mt-1">
                Kelola kategori buku perpustakaan.
            </p>
        </div>

        <a
            href="{{ route('admin.categories.create') }}"
            class="inline-flex justify-center bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl font-semibold"
        >
            + Tambah Kategori
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

    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-left">

                <thead class="bg-slate-50 border-b">
                    <tr>
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">Nama Kategori</th>
                        <th class="px-6 py-4">Deskripsi</th>
                        <th class="px-6 py-4">Jumlah Buku</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @forelse($categories as $category)

                        <tr class="hover:bg-slate-50">

                            <td class="px-6 py-4">
                                {{ $categories->firstItem() + $loop->index }}
                            </td>

                            <td class="px-6 py-4 font-semibold">
                                {{ $category->name }}
                            </td>

                            <td class="px-6 py-4 text-slate-500">
                                {{ $category->description ?: '-' }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold">
                                    {{ $category->books_count }} buku
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-2">

                                    <a
                                        href="{{ route('admin.categories.edit', $category) }}"
                                        class="bg-yellow-100 hover:bg-yellow-200 text-yellow-700 px-3 py-2 rounded-lg text-sm font-semibold"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('admin.categories.destroy', $category) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus kategori ini?')"
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
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                Belum ada kategori.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($categories->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $categories->links() }}
            </div>
        @endif

    </div>

</div>

@endsection