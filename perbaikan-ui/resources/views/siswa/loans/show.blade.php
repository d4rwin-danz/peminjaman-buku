@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')

<div class="max-w-5xl mx-auto">

{{-- BACK --}}
<a
    href="{{ route('siswa.loans.index') }}"
    class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-indigo-600"
>
    ← Kembali ke Peminjaman Saya
</a>


{{-- HEADER --}}
<div class="mt-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

    <div>


        <h1 class="mt-1 text-2xl sm:text-3xl font-bold text-slate-900">
            Peminjaman #{{ $loan->id }}
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            Informasi lengkap mengenai transaksi peminjaman buku kamu.
        </p>

    </div>


    {{-- STATUS --}}
    <div>

        @if ($loan->status === 'pending')

            <span class="inline-flex items-center rounded-full border border-amber-200 bg-amber-100 px-4 py-2 text-sm font-bold text-amber-700">
                <span class="mr-2 inline-block h-2 w-2 rounded-full bg-amber-500"></span>Menunggu Persetujuan
            </span>

        @elseif ($loan->status === 'approved')

            <span class="inline-flex items-center rounded-full border border-indigo-200 bg-indigo-100 px-4 py-2 text-sm font-bold text-indigo-700">
                <span class="mr-2 inline-block h-2 w-2 rounded-full bg-ink-500"></span>Disetujui
            </span>

        @elseif ($loan->status === 'borrowed')

            <span class="inline-flex items-center rounded-full border border-blue-200 bg-blue-100 px-4 py-2 text-sm font-bold text-blue-700">
                <span class="mr-2 inline-block h-2 w-2 rounded-full bg-ink-700"></span>Sedang Dipinjam
            </span>

        @elseif ($loan->status === 'return_pending')

            <span class="inline-flex items-center rounded-full border border-orange-200 bg-orange-100 px-4 py-2 text-sm font-bold text-orange-700">
                <span class="mr-2 inline-block h-2 w-2 rounded-full bg-orange-500"></span>Menunggu Verifikasi Pengembalian
            </span>

        @elseif ($loan->status === 'returned')

            <span class="inline-flex items-center rounded-full border border-green-200 bg-green-100 px-4 py-2 text-sm font-bold text-green-700">
                <span class="mr-2 inline-block h-2 w-2 rounded-full bg-green-600"></span>Dikembalikan
            </span>

        @elseif ($loan->status === 'rejected')

            <span class="inline-flex items-center rounded-full border border-red-200 bg-red-100 px-4 py-2 text-sm font-bold text-red-700">
                <span class="mr-2 inline-block h-2 w-2 rounded-full bg-red-600"></span>Ditolak
            </span>

        @else

            <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-100 px-4 py-2 text-sm font-bold text-slate-700">
                {{ ucfirst(str_replace('_', ' ', $loan->status)) }}
            </span>

        @endif

    </div>

</div>


{{-- INVOICE / KODE PEMINJAMAN --}}
@if ($loan->status !== 'rejected' && $loan->loan_code)

    <div class="mt-8">
        @include('components.loan-invoice', ['loan' => $loan])
    </div>

@endif


{{-- LOAN INFORMATION --}}
@php
    $totalBuku = $loan->details->sum('quantity');
    $terlambat = $loan->status === 'borrowed' && $loan->due_date && now()->startOfDay()->gt($loan->due_date);
    $sisaHari = $loan->due_date ? now()->startOfDay()->diffInDays($loan->due_date, false) : null;
@endphp

<div class="mt-6 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">

    <div class="grid grid-cols-1 divide-y divide-slate-100 sm:grid-cols-3 sm:divide-x sm:divide-y-0">

        @foreach ([
            ['Tanggal Pinjam', $loan->loan_date, 'M4 7h16M7 3v4M17 3v4M5 7h14v13H5z'],
            ['Batas Pengembalian', $loan->due_date, 'M12 8v5l3 2M12 3a9 9 0 1 0 0 18 9 9 0 0 0 0-18z'],
            ['Tanggal Dikembalikan', $loan->return_date, 'M5 13l4 4L19 7'],
        ] as [$label, $date, $icon])
            <div class="flex items-center gap-4 px-5 py-4 sm:block sm:py-5">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-ink-50 text-ink-600 sm:mb-3">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $icon }}"/></svg>
                </span>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">{{ $label }}</p>
                    <p class="mt-0.5 font-display text-lg font-semibold text-ink-900">{{ $date?->translatedFormat('d F Y') ?? '-' }}</p>
                </div>
            </div>
        @endforeach

    </div>

    {{-- Strip info cepat --}}
    <div class="flex flex-wrap items-center gap-x-6 gap-y-2 border-t border-slate-100 bg-slate-50 px-5 py-3 text-sm">
        <span class="text-slate-600"><span class="font-semibold text-ink-900">{{ $loan->details->count() }}</span> judul &middot; <span class="font-semibold text-ink-900">{{ $totalBuku }}</span> eksemplar</span>

        @if ($loan->loan_code)
            <span class="text-slate-600">Kode <span class="rounded bg-white px-1.5 py-0.5 font-mono text-xs font-semibold text-ink-800 ring-1 ring-slate-200">{{ $loan->loan_code }}</span></span>
        @endif

        @if ($loan->status === 'borrowed' && $sisaHari !== null)
            @if ($terlambat)
                <span class="font-semibold text-red-600">Terlambat {{ abs($sisaHari) }} hari</span>
            @else
                <span class="font-semibold text-ink-700">Sisa {{ $sisaHari }} hari</span>
            @endif
        @endif
    </div>

</div>


{{-- BOOKS --}}
<div class="mt-6 rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">

    <div class="flex items-end justify-between gap-3 border-b border-slate-100 px-5 py-4 sm:px-6">

        <div>
            <h2 class="text-lg font-bold text-slate-900 sm:text-xl">Buku yang Dipinjam</h2>
            <p class="mt-0.5 text-sm text-slate-500">Daftar buku dalam transaksi ini.</p>
        </div>
        <span class="shrink-0 rounded-full bg-ink-50 px-3 py-1 text-xs font-semibold text-ink-700">{{ $loan->details->count() }} judul</span>

    </div>


    <div class="divide-y divide-slate-100">

        @forelse ($loan->details as $detail)

            <div class="flex gap-4 p-4 sm:gap-5 sm:p-6">

                {{-- COVER --}}
                <div class="cover h-32 w-24 shrink-0 overflow-hidden rounded-md bg-slate-100 shadow-md sm:h-40 sm:w-28">

                    @if ($detail->book && $detail->book->cover)

                        <img
                            src="{{ asset(str_starts_with($detail->book->cover, 'uploads/') ? $detail->book->cover : 'storage/' . $detail->book->cover) }}"
                            alt="{{ $detail->book->title }}"
                            class="h-full w-full object-cover"
                        >

                    @else

                        <svg class="mx-auto h-10 w-10 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 19.5V5a2 2 0 0 1 2-2h13v16H6a2 2 0 0 0-2 2.5Z"/><path d="M4 19.5A2 2 0 0 1 6 18h13"/></svg>

                    @endif

                </div>


                {{-- BOOK DATA --}}
                <div class="min-w-0 flex-1">

                    <h3 class="font-display text-base font-semibold leading-snug text-slate-900 sm:text-xl">
                        {{ $detail->book->title ?? '-' }}
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        {{ $detail->book->author ?? '-' }}
                    </p>


                    <div class="mt-3 flex flex-wrap gap-1.5">

                        <span class="rounded-md bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                            Kode: {{ $detail->book->code ?? '-' }}
                        </span>

                        <span class="rounded-md bg-ink-50 px-2.5 py-1 text-xs font-semibold text-indigo-700">
                            Jumlah: {{ $detail->quantity }} buku
                        </span>

                        @if ($detail->book?->category)

                            <span class="rounded-md bg-marker/20 px-2.5 py-1 text-xs font-semibold text-amber-800">
                                {{ $detail->book->category->name }}
                            </span>

                        @endif

                    </div>


                    @if ($detail->book?->description)

                        <p class="mt-3 line-clamp-3 text-sm leading-6 text-slate-500">
                            {{ $detail->book->description }}
                        </p>

                    @endif

                </div>

            </div>

        @empty

            <div class="p-10 text-center text-sm text-slate-500">
                Tidak ada detail buku pada peminjaman ini.
            </div>

        @endforelse

    </div>

</div>


{{-- FOTO BUKTI LAMA (peminjaman sebelum fitur invoice) --}}
@if ($loan->borrowing_proof)

    <div class="mt-6 rounded-2xl bg-white p-5 shadow-sm sm:p-6 ring-1 ring-slate-200">

        <div>

            <h2 class="text-xl font-bold text-slate-900">
                Foto Bukti Peminjaman (Lama)
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Foto yang kamu kirim saat mengajukan peminjaman sebelum fitur invoice tersedia.
            </p>

        </div>


        <div class="mt-5 overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">

            <img
                src="{{ asset('storage/' . $loan->borrowing_proof) }}"
                alt="Bukti Peminjaman"
                class="mx-auto max-h-[600px] w-auto max-w-full object-contain"
            >

        </div>

    </div>

@endif


{{-- RETURN PROOF --}}
@if ($loan->return_proof)

    <div class="mt-6 rounded-2xl bg-white p-5 shadow-sm sm:p-6 ring-1 ring-slate-200">

        <div>

            <h2 class="text-xl font-bold text-slate-900">
                Bukti Pengembalian
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Foto yang kamu kirim sebagai bukti pengembalian buku.
            </p>

        </div>


        <div class="mt-5 overflow-hidden rounded-2xl border border-orange-200 bg-orange-50">

            <img
                src="{{ asset('storage/' . $loan->return_proof) }}"
                alt="Bukti Pengembalian"
                class="mx-auto max-h-[600px] w-auto max-w-full object-contain"
            >

        </div>

    </div>

@endif


{{-- NOTES --}}
@if ($loan->notes)

    <div class="mt-6 rounded-2xl bg-white p-5 shadow-sm sm:p-6 ring-1 ring-slate-200">

        <h2 class="text-xl font-bold text-slate-900">
            Catatan
        </h2>

        <div class="mt-4 rounded-2xl bg-slate-50 p-5">

            <p class="whitespace-pre-line text-sm leading-6 text-slate-600">
                {{ $loan->notes }}
            </p>

        </div>

    </div>

@endif


{{-- RETURN ACTION --}}
@if ($loan->status === 'borrowed')

    <div class="mt-6 rounded-2xl border border-orange-200 bg-orange-50 p-6">

        <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <h2 class="text-lg font-bold text-orange-900">
                    Sudah Mengembalikan Buku?
                </h2>

                <p class="mt-1 text-sm leading-6 text-orange-700">
                    Jika buku sudah kamu kembalikan ke perpustakaan,
                    upload foto bukti pengembalian untuk diverifikasi admin.
                </p>

            </div>


            <a
                href="{{ route('siswa.loans.return.create', $loan) }}"
                class="inline-flex shrink-0 items-center justify-center rounded-xl bg-orange-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-orange-700"
            >
                Ajukan Pengembalian
            </a>

        </div>

    </div>

@elseif ($loan->status === 'return_pending')

    <div class="mt-6 rounded-2xl border border-orange-200 bg-orange-50 p-6">

        <div class="flex items-start gap-4">

            

            <div>

                <h2 class="font-bold text-orange-900">
                    Pengembalian Sedang Diverifikasi
                </h2>

                <p class="mt-1 text-sm leading-6 text-orange-700">
                    Foto bukti pengembalian sudah berhasil dikirim.
                    Silakan tunggu admin memverifikasi pengembalian kamu.
                </p>

            </div>

        </div>

    </div>

@elseif ($loan->status === 'returned')

    <div class="mt-6 rounded-2xl border border-green-200 bg-green-50 p-6">

        <div class="flex items-start gap-4">

            

            <div class="flex-1">

                <h2 class="font-bold text-green-900">
                    Buku Sudah Dikembalikan
                </h2>

                <p class="mt-1 text-sm leading-6 text-green-700">
                    Transaksi peminjaman ini telah selesai.
                </p>


                <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-3">

                    <div class="rounded-xl bg-white p-4 ring-1 ring-green-100">

                        <p class="text-xs font-semibold text-slate-400">
                            TANGGAL KEMBALI
                        </p>

                        <p class="mt-1 font-bold text-slate-800">
                            {{ $loan->return_date?->format('d F Y') ?? '-' }}
                        </p>

                    </div>


                    <div class="rounded-xl bg-white p-4 ring-1 ring-green-100">

                        <p class="text-xs font-semibold text-slate-400">
                            KETERLAMBATAN
                        </p>

                        <p class="mt-1 font-bold text-slate-800">
                            {{ $loan->late_days ?? 0 }} hari
                        </p>

                    </div>


                    <div class="rounded-xl bg-white p-4 ring-1 ring-green-100">

                        <p class="text-xs font-semibold text-slate-400">
                            DENDA
                        </p>

                        <p class="mt-1 font-bold text-red-600">
                            Rp {{ number_format($loan->fine ?? 0, 0, ',', '.') }}
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

@elseif ($loan->status === 'rejected')

    <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-6">

        <div class="flex items-start gap-4">

            

            <div>

                <h2 class="font-bold text-red-900">
                    Pengajuan Ditolak
                </h2>

                <p class="mt-1 text-sm leading-6 text-red-700">
                    Pengajuan peminjaman ini telah ditolak oleh admin.
                </p>

                @if ($loan->notes)

                    <div class="mt-4 rounded-xl bg-white p-4 ring-1 ring-red-100">

                        <p class="text-xs font-semibold uppercase tracking-wider text-red-400">
                            Alasan Penolakan
                        </p>

                        <p class="mt-1 text-sm text-red-700">
                            {{ $loan->notes }}
                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>

@endif


{{-- FOOTER --}}
<div class="mt-8 flex justify-center">

    <a
        href="{{ route('siswa.loans.index') }}"
        class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
    >
        ← Kembali ke Peminjaman Saya
    </a>

</div>

</div>

@endsection
