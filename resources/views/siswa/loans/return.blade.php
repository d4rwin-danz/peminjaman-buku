@extends('layouts.app')

@section('title', 'Ajukan Pengembalian')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">

    {{-- Header --}}
    <div class="mb-8">
        <a
            href="{{ route('siswa.loans.index') }}"
            class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-slate-900 mb-4"
        >
            ← Kembali ke Peminjaman Saya
        </a>

        <div>
            <p class="text-sm font-semibold text-blue-600 uppercase tracking-wider">
                Pengembalian Buku
            </p>

            <h1 class="mt-2 text-3xl font-bold text-slate-900">
                Ajukan Pengembalian
            </h1>

            <p class="mt-2 text-slate-500">
                Upload foto sebagai bukti bahwa buku telah dikembalikan.
            </p>
        </div>
    </div>

    {{-- Error --}}
    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5">
            <div class="flex gap-3">
                <div class="text-xl">⚠️</div>

                <div>
                    <h3 class="font-semibold text-red-800">
                        Ada kesalahan
                    </h3>

                    <ul class="mt-2 list-disc list-inside text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="grid lg:grid-cols-3 gap-6">

        {{-- Detail Peminjaman --}}
        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

                <div class="p-6 border-b border-slate-100">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                Peminjaman
                            </p>

                            <h2 class="mt-1 text-xl font-bold text-slate-900">
                                #{{ $loan->id }}
                            </h2>
                        </div>

                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                            Sedang Dipinjam
                        </span>
                    </div>
                </div>

                <div class="p-6 space-y-5">

                    @foreach ($loan->details as $detail)
                        <div class="flex gap-4 p-4 rounded-2xl bg-slate-50">

                            {{-- Cover --}}
                            <div class="w-20 h-28 rounded-xl overflow-hidden bg-slate-200 shrink-0">

                                @if ($detail->book->cover)
                                    <img
                                        src="{{ asset('storage/' . $detail->book->cover) }}"
                                        alt="{{ $detail->book->title }}"
                                        class="w-full h-full object-cover"
                                    >
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-3xl">
                                        📚
                                    </div>
                                @endif

                            </div>

                            {{-- Info --}}
                            <div class="min-w-0 flex-1">

                                <h3 class="font-bold text-slate-900">
                                    {{ $detail->book->title }}
                                </h3>

                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $detail->book->author }}
                                </p>

                                <div class="mt-3 flex flex-wrap gap-2">

                                    <span class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 text-xs text-slate-600">
                                        Kode: {{ $detail->book->code }}
                                    </span>

                                    <span class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 text-xs text-slate-600">
                                        Jumlah: {{ $detail->quantity }}
                                    </span>

                                </div>
                            </div>

                        </div>
                    @endforeach

                </div>
            </div>

            {{-- Form --}}
            <form
                action="{{ route('siswa.loans.return.store', $loan) }}"
                method="POST"
                enctype="multipart/form-data"
                class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden"
            >
                @csrf

                <div class="p-6 border-b border-slate-100">
                    <h2 class="text-xl font-bold text-slate-900">
                        Bukti Pengembalian
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Pastikan foto terlihat jelas dan buku dapat dikenali.
                    </p>
                </div>

                <div class="p-6 space-y-6">

                    {{-- Upload --}}
                    <div>
                        <label
                            for="return_proof"
                            class="block text-sm font-semibold text-slate-700 mb-2"
                        >
                            Foto Bukti Pengembalian
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="file"
                            id="return_proof"
                            name="return_proof"
                            accept="image/jpeg,image/png,image/webp"
                            required
                            class="block w-full text-sm text-slate-600
                                   file:mr-4 file:py-2.5 file:px-4
                                   file:rounded-xl file:border-0
                                   file:bg-slate-900 file:text-white
                                   file:font-semibold
                                   hover:file:bg-slate-800"
                        >

                        <p class="mt-2 text-xs text-slate-400">
                            JPG, JPEG, PNG, atau WEBP. Maksimal 5 MB.
                        </p>
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label
                            for="notes"
                            class="block text-sm font-semibold text-slate-700 mb-2"
                        >
                            Catatan
                            <span class="font-normal text-slate-400">(opsional)</span>
                        </label>

                        <textarea
                            id="notes"
                            name="notes"
                            rows="4"
                            maxlength="1000"
                            placeholder="Contoh: Buku dikembalikan dalam kondisi baik."
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500"
                        >{{ old('notes') }}</textarea>
                    </div>

                </div>

                <div class="px-6 py-5 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row gap-3 sm:justify-end">

                    <a
                        href="{{ route('siswa.loans.index') }}"
                        class="inline-flex items-center justify-center px-5 py-3 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 hover:bg-slate-100"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700"
                    >
                        🔄 Ajukan Pengembalian
                    </button>

                </div>

            </form>

        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">

            <div class="rounded-3xl bg-blue-50 border border-blue-100 p-6">

                <div class="text-3xl mb-4">
                    📸
                </div>

                <h3 class="font-bold text-slate-900">
                    Foto Bukti
                </h3>

                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Foto digunakan oleh admin untuk memverifikasi bahwa buku
                    benar-benar telah dikembalikan.
                </p>

            </div>

            <div class="rounded-3xl bg-amber-50 border border-amber-100 p-6">

                <div class="text-3xl mb-4">
                    ⏰
                </div>

                <h3 class="font-bold text-slate-900">
                    Perhatikan Batas Waktu
                </h3>

                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Batas pengembalian:
                </p>

                <p class="mt-1 font-bold text-amber-700">
                    {{ $loan->due_date?->format('d F Y') }}
                </p>

                <p class="mt-3 text-xs text-slate-500">
                    Jika terlambat, sistem akan menghitung denda secara otomatis
                    setelah admin mengonfirmasi pengembalian.
                </p>

            </div>

        </div>

    </div>

</div>
@endsection