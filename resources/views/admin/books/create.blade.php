@extends('layouts.app')

@section('title', 'Tambah Buku')

@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-6 py-8">

    <div class="mb-8">
        <h1 class="text-3xl font-bold">Tambah Buku</h1>
        <p class="text-slate-500 mt-1">
            Masukkan data buku baru.
        </p>
    </div>

    @if ($errors->any())

        <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl mb-6">

            <ul class="list-disc list-inside space-y-1">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif

    <div class="bg-white rounded-2xl shadow p-6">

        <form
            action="{{ route('admin.books.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-6"
        >

            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="block font-semibold mb-2">
                        Kode Buku *
                    </label>

                    <input
                        type="text"
                        name="code"
                        value="{{ old('code') }}"
                        required
                        placeholder="BK-007"
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <div>
                    <label class="block font-semibold mb-2">
                        Kategori *
                    </label>

                    <select
                        name="category_id"
                        required
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500"
                    >

                        <option value="">
                            Pilih kategori
                        </option>

                        @foreach($categories as $category)

                            <option
                                value="{{ $category->id }}"
                                @selected(old('category_id') == $category->id)
                            >
                                {{ $category->name }}
                            </option>

                        @endforeach

                    </select>
                </div>

                <div class="md:col-span-2">

                    <label class="block font-semibold mb-2">
                        Judul Buku *
                    </label>

                    <input
                        type="text"
                        name="title"
                        value="{{ old('title') }}"
                        required
                        placeholder="Judul buku"
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500"
                    >

                </div>

                <div>

                    <label class="block font-semibold mb-2">
                        Penulis *
                    </label>

                    <input
                        type="text"
                        name="author"
                        value="{{ old('author') }}"
                        required
                        placeholder="Nama penulis"
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500"
                    >

                </div>

                <div>

                    <label class="block font-semibold mb-2">
                        Penerbit
                    </label>

                    <input
                        type="text"
                        name="publisher"
                        value="{{ old('publisher') }}"
                        placeholder="Nama penerbit"
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500"
                    >

                </div>

                <div>

                    <label class="block font-semibold mb-2">
                        Tahun Terbit
                    </label>

                    <input
                        type="number"
                        name="publication_year"
                        value="{{ old('publication_year') }}"
                        min="1000"
                        max="9999"
                        placeholder="2026"
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500"
                    >

                </div>

                <div>

                    <label class="block font-semibold mb-2">
                        ISBN
                    </label>

                    <input
                        type="text"
                        name="isbn"
                        value="{{ old('isbn') }}"
                        placeholder="ISBN"
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500"
                    >

                </div>

                <div>

                    <label class="block font-semibold mb-2">
                        Stok *
                    </label>

                    <input
                        type="number"
                        name="stock"
                        value="{{ old('stock', 0) }}"
                        min="0"
                        required
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500"
                    >

                </div>

                <div>

                    <label class="block font-semibold mb-2">
                        Cover Buku
                    </label>

                    <input
                        type="file"
                        name="cover"
                        accept=".jpg,.jpeg,.png,.webp"
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 bg-white"
                    >

                    <p class="text-xs text-slate-500 mt-2">
                        JPG, JPEG, PNG, WEBP. Maksimal 2 MB.
                    </p>

                </div>

                <div class="md:col-span-2">

                    <label class="block font-semibold mb-2">
                        Deskripsi
                    </label>

                    <textarea
                        name="description"
                        rows="5"
                        placeholder="Deskripsi buku..."
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500"
                    >{{ old('description') }}</textarea>

                </div>

            </div>

            <div class="flex flex-col sm:flex-row gap-3">

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-semibold"
                >
                    Simpan Buku
                </button>

                <a
                    href="{{ route('admin.books.index') }}"
                    class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-6 py-3 rounded-xl font-semibold text-center"
                >
                    Batal
                </a>

            </div>

        </form>

    </div>

</div>

@endsection