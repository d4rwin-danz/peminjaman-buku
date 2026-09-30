@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')

<div class="max-w-3xl mx-auto px-4 sm:px-6 py-8">

    <div class="mb-8">
        <h1 class="text-3xl font-bold">Edit Kategori</h1>
        <p class="text-slate-500 mt-1">
            Perbarui informasi kategori.
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
            action="{{ route('admin.categories.update', $category) }}"
            method="POST"
            class="space-y-6"
        >
            @csrf
            @method('PUT')

            <div>
                <label class="block font-semibold mb-2">
                    Nama Kategori
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $category->name) }}"
                    required
                    class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                >
            </div>

            <div>
                <label class="block font-semibold mb-2">
                    Deskripsi
                </label>

                <textarea
                    name="description"
                    rows="4"
                    class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                >{{ old('description', $category->description) }}</textarea>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl font-semibold"
                >
                    Simpan Perubahan
                </button>

                <a
                    href="{{ route('admin.categories.index') }}"
                    class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-5 py-3 rounded-xl font-semibold text-center"
                >
                    Batal
                </a>

            </div>

        </form>

    </div>

</div>

@endsection