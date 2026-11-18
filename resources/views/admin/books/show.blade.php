@extends('layouts.app')

@section('title', 'Detail Buku')

@section('content')

@php
    $rackLocations = [
        'utara_1' => 'Rak Utara 1',
        'utara_2' => 'Rak Utara 2',
        'utara_3' => 'Rak Utara 3',
        'utara_4' => 'Rak Utara 4',
        'utara_5' => 'Rak Utara 5',
        'utara_6' => 'Rak Utara 6',
        'barat_1' => 'Rak Barat 1',
        'barat_2' => 'Rak Barat 2',
        'barat_3' => 'Rak Barat 3',
        'barat_4' => 'Rak Barat 4',
        'barat_5' => 'Rak Barat 5',
        'barat_6' => 'Rak Barat 6',
        'selatan_1' => 'Rak Selatan 1',
        'selatan_2' => 'Rak Selatan 2',
    ];

    $currentRack = $book->rack_location;
    $currentRackName = $rackLocations[$currentRack] ?? null;
@endphp

<div class="max-w-5xl mx-auto">

    <div class="mb-6">
        <a
            href="{{ route('admin.books.index') }}"
            class="text-blue-600 hover:underline"
        >
            ← Kembali ke Data Buku
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <div class="grid grid-cols-1 md:grid-cols-3">

            <div class="bg-slate-50 p-6 sm:p-8 md:border-r md:border-slate-100">

                <div class="flex justify-center">
                    @if($book->cover)
                        <img
                            src="{{ asset(str_starts_with($book->cover, 'uploads/') ? $book->cover : 'storage/' . $book->cover) }}"
                            alt="{{ $book->title }}"
                            class="w-48 sm:w-56 aspect-[7/10] object-cover rounded-xl shadow-lg"
                        >
                    @else
                        <div class="w-48 sm:w-56 aspect-[7/10] bg-slate-200 rounded-xl flex items-center justify-center text-slate-400">
                            Tidak ada cover
                        </div>
                    @endif
                </div>

                {{-- Statistik peminjaman --}}
                <div class="mt-6 grid grid-cols-3 gap-2 text-center">
                    <div class="rounded-xl bg-white p-3 ring-1 ring-slate-200">
                        <p class="font-display text-xl font-semibold text-ink-900">{{ $loanStats['total'] }}</p>
                        <p class="mt-0.5 text-[11px] font-medium text-slate-500">Transaksi</p>
                    </div>
                    <div class="rounded-xl bg-white p-3 ring-1 ring-slate-200">
                        <p class="font-display text-xl font-semibold text-ink-900">{{ $loanStats['copies'] }}</p>
                        <p class="mt-0.5 text-[11px] font-medium text-slate-500">Eksemplar</p>
                    </div>
                    <div class="rounded-xl bg-white p-3 ring-1 ring-slate-200">
                        <p class="font-display text-xl font-semibold {{ $loanStats['active'] ? 'text-amber-600' : 'text-ink-900' }}">{{ $loanStats['active'] }}</p>
                        <p class="mt-0.5 text-[11px] font-medium text-slate-500">Aktif</p>
                    </div>
                </div>

                {{-- Info singkat --}}
                <dl class="mt-4 divide-y divide-slate-200 rounded-xl bg-white px-4 text-sm ring-1 ring-slate-200">
                    <div class="flex items-center justify-between gap-3 py-2.5">
                        <dt class="text-slate-500">Ketersediaan</dt>
                        <dd>
                            @if($book->stock > 0)
                                <span class="rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-700">Tersedia</span>
                            @else
                                <span class="rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-semibold text-red-700">Habis</span>
                            @endif
                        </dd>
                    </div>
                    <div class="flex items-center justify-between gap-3 py-2.5">
                        <dt class="text-slate-500">Lokasi</dt>
                        <dd class="font-semibold text-ink-800">{{ $currentRackName ?? 'Belum ditentukan' }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-3 py-2.5">
                        <dt class="text-slate-500">Ditambahkan</dt>
                        <dd class="font-semibold text-slate-700">{{ $book->created_at?->translatedFormat('d M Y') ?? '-' }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-3 py-2.5">
                        <dt class="text-slate-500">Diperbarui</dt>
                        <dd class="font-semibold text-slate-700">{{ $book->updated_at?->diffForHumans() ?? '-' }}</dd>
                    </div>
                </dl>

                {{-- Peminjam terbaru --}}
                <div class="mt-4 rounded-xl bg-white p-4 ring-1 ring-slate-200">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Peminjam Terbaru</p>

                    @forelse($recentLoans as $d)
                        @php
                            $st = [
                                'pending' => ['Menunggu', 'bg-amber-100 text-amber-700'],
                                'approved' => ['Disetujui', 'bg-blue-100 text-blue-700'],
                                'borrowed' => ['Dipinjam', 'bg-indigo-100 text-indigo-700'],
                                'return_pending' => ['Verifikasi', 'bg-orange-100 text-orange-700'],
                                'returned' => ['Kembali', 'bg-green-100 text-green-700'],
                            ][$d->loan->status] ?? [ucfirst($d->loan->status), 'bg-slate-100 text-slate-700'];
                        @endphp
                        <a href="{{ route('admin.loans.show', $d->loan) }}" class="mt-3 flex items-center justify-between gap-3 rounded-lg hover:bg-slate-50">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-slate-800">{{ $d->loan->user->name ?? 'Siswa' }}</p>
                                <p class="text-xs text-slate-400">{{ $d->loan->loan_date?->translatedFormat('d M Y') ?? '-' }} &middot; {{ $d->quantity }} buku</p>
                            </div>
                            <span class="shrink-0 rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $st[1] }}">{{ $st[0] }}</span>
                        </a>
                    @empty
                        <p class="mt-3 text-sm text-slate-400">Belum pernah dipinjam.</p>
                    @endforelse
                </div>

            </div>

            <div class="md:col-span-2 p-8">

                <div class="mb-6">

                    <span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold mb-3">
                        {{ $book->category->name ?? 'Tanpa Kategori' }}
                    </span>

                    <h1 class="text-3xl font-bold text-slate-900">
                        {{ $book->title }}
                    </h1>

                    <p class="text-slate-500 mt-2">
                        {{ $book->author }}
                    </p>

                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    <div>
                        <p class="text-sm text-slate-500">Kode Buku</p>
                        <p class="font-semibold mt-1">{{ $book->code }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500">ISBN</p>
                        <p class="font-semibold mt-1">{{ $book->isbn ?: '-' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500">Penerbit</p>
                        <p class="font-semibold mt-1">{{ $book->publisher ?: '-' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500">Tahun Terbit</p>
                        <p class="font-semibold mt-1">{{ $book->publication_year ?: '-' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500">Stok</p>

                        @if($book->stock > 0)
                            <p class="font-semibold text-green-600 mt-1">
                                {{ $book->stock }} buku tersedia
                            </p>
                        @else
                            <p class="font-semibold text-red-600 mt-1">
                                Stok habis
                            </p>
                        @endif
                    </div>

                    <div>
                        <p class="text-sm text-slate-500">Lokasi Rak</p>

                        @if($currentRackName)
                            <p class="font-semibold text-blue-700 mt-1">
                                {{ $currentRackName }}
                            </p>
                        @else
                            <p class="font-semibold text-slate-400 mt-1">
                                Belum ditentukan
                            </p>
                        @endif
                    </div>

                </div>

                <div class="border-t mt-8 pt-6">

                    <p class="text-sm text-slate-500 mb-2">
                        Deskripsi
                    </p>

                    <p class="text-slate-700 leading-relaxed">
                        {{ $book->description ?: 'Tidak ada deskripsi.' }}
                    </p>

                </div>

                <div class="mt-8 flex flex-wrap gap-3">

                    <a
                        href="{{ route('admin.books.edit', $book) }}"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-3 rounded-xl font-semibold"
                    >
                        Edit Buku
                    </a>

                    <a
                        href="{{ route('admin.books.index') }}"
                        class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-5 py-3 rounded-xl font-semibold"
                    >
                        Kembali
                    </a>

                </div>

            </div>

        </div>

    </div>

    <div class="mt-8">
        @include('components.library-map')
    </div>

</div>

@endsection