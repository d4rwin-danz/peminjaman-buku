@extends('layouts.app')

@section('title', 'Edit Buku')

@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-6 py-8">

    <div class="mb-8">
        <h1 class="text-3xl font-bold">Edit Buku</h1>
        <p class="text-slate-500 mt-1">
            Perbarui informasi buku.
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
            action="{{ route('admin.books.update', $book) }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-6"
        >

            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>

                    <label class="block font-semibold mb-2">
                        Kode Buku *
                    </label>

                    <input
                        type="text"
                        name="code"
                        value="{{ old('code', $book->code) }}"
                        required
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

                        @foreach($categories as $category)

                            <option
                                value="{{ $category->id }}"
                                @selected(old('category_id', $book->category_id) == $category->id)
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
                        value="{{ old('title', $book->title) }}"
                        required
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
                        value="{{ old('author', $book->author) }}"
                        required
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
                        value="{{ old('publisher', $book->publisher) }}"
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
                        value="{{ old('publication_year', $book->publication_year) }}"
                        min="1000"
                        max="9999"
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
                        value="{{ old('isbn', $book->isbn) }}"
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
                        value="{{ old('stock', $book->stock) }}"
                        min="0"
                        required
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500"
                    >

                </div>

                <div>

                    <label class="block font-semibold mb-2">
                        Cover Baru
                    </label>

                    <input
                        type="file"
                        name="cover"
                        accept=".jpg,.jpeg,.png,.webp"
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 bg-white"
                    >

                    @if($book->cover)

                        <img
                            src="{{ asset('storage/' . $book->cover) }}"
                            class="mt-3 w-24 h-32 object-cover rounded-xl"
                            alt="{{ $book->title }}"
                        >

                    @endif

                    <p class="text-xs text-slate-500 mt-2">
                        Kosongkan jika tidak ingin mengganti cover.
                    </p>

                </div>

                <div class="md:col-span-2">

                    <label class="block font-semibold mb-2">
                        Deskripsi
                    </label>

                    <textarea
                        name="description"
                        rows="5"
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500"
                    >{{ old('description', $book->description) }}</textarea>

                </div>

            </div>

            <div class="flex flex-col sm:flex-row gap-3">

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-semibold"
                >
                    Simpan Perubahan
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