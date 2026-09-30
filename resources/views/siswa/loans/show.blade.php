@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

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

        <p class="text-sm font-semibold uppercase tracking-wider text-indigo-600">
            Detail Peminjaman
        </p>

        <h1 class="mt-1 text-3xl font-bold text-slate-900">
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
                🟡 Menunggu Persetujuan
            </span>

        @elseif ($loan->status === 'approved')

            <span class="inline-flex items-center rounded-full border border-indigo-200 bg-indigo-100 px-4 py-2 text-sm font-bold text-indigo-700">
                🟢 Disetujui
            </span>

        @elseif ($loan->status === 'borrowed')

            <span class="inline-flex items-center rounded-full border border-blue-200 bg-blue-100 px-4 py-2 text-sm font-bold text-blue-700">
                🔵 Sedang Dipinjam
            </span>

        @elseif ($loan->status === 'return_pending')

            <span class="inline-flex items-center rounded-full border border-orange-200 bg-orange-100 px-4 py-2 text-sm font-bold text-orange-700">
                🟠 Menunggu Verifikasi Pengembalian
            </span>

        @elseif ($loan->status === 'returned')

            <span class="inline-flex items-center rounded-full border border-green-200 bg-green-100 px-4 py-2 text-sm font-bold text-green-700">
                ✅ Dikembalikan
            </span>

        @elseif ($loan->status === 'rejected')

            <span class="inline-flex items-center rounded-full border border-red-200 bg-red-100 px-4 py-2 text-sm font-bold text-red-700">
                ❌ Ditolak
            </span>

        @else

            <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-100 px-4 py-2 text-sm font-bold text-slate-700">
                {{ ucfirst(str_replace('_', ' ', $loan->status)) }}
            </span>

        @endif

    </div>

</div>


{{-- LOAN INFORMATION --}}
<div class="mt-8 grid grid-cols-1 gap-6 md:grid-cols-3">

    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

        <div class="text-2xl">
            📅
        </div>

        <p class="mt-4 text-xs font-semibold uppercase tracking-wider text-slate-400">
            Tanggal Pinjam
        </p>

        <p class="mt-1 text-lg font-bold text-slate-800">
            {{ $loan->loan_date?->format('d F Y') ?? '-' }}
        </p>

    </div>


    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

        <div class="text-2xl">
            ⏰
        </div>

        <p class="mt-4 text-xs font-semibold uppercase tracking-wider text-slate-400">
            Batas Pengembalian
        </p>

        <p class="mt-1 text-lg font-bold text-slate-800">
            {{ $loan->due_date?->format('d F Y') ?? '-' }}
        </p>

    </div>


    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

        <div class="text-2xl">
            🔄
        </div>

        <p class="mt-4 text-xs font-semibold uppercase tracking-wider text-slate-400">
            Tanggal Dikembalikan
        </p>

        <p class="mt-1 text-lg font-bold text-slate-800">
            {{ $loan->return_date?->format('d F Y') ?? '-' }}
        </p>

    </div>

</div>


{{-- BOOKS --}}
<div class="mt-6 rounded-3xl bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">

    <div class="border-b border-slate-100 px-6 py-5">

        <h2 class="text-xl font-bold text-slate-900">
            📚 Buku yang Dipinjam
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Daftar buku yang termasuk dalam transaksi ini.
        </p>

    </div>


    <div class="divide-y divide-slate-100">

        @forelse ($loan->details as $detail)

            <div class="flex flex-col gap-5 p-6 sm:flex-row">

                {{-- COVER --}}
                <div class="h-36 w-24 shrink-0 overflow-hidden rounded-xl bg-slate-100">

                    @if ($detail->book && $detail->book->cover)

                        <img
                            src="{{ asset('storage/' . $detail->book->cover) }}"
                            alt="{{ $detail->book->title }}"
                            class="h-full w-full object-cover"
                        >

                    @else

                        <div class="flex h-full items-center justify-center text-4xl">
                            📚
                        </div>

                    @endif

                </div>


                {{-- BOOK DATA --}}
                <div class="flex-1">

                    <h3 class="text-xl font-bold text-slate-900">
                        {{ $detail->book->title ?? '-' }}
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        {{ $detail->book->author ?? '-' }}
                    </p>


                    <div class="mt-4 flex flex-wrap gap-2">

                        <span class="rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600">
                            Kode: {{ $detail->book->code ?? '-' }}
                        </span>

                        <span class="rounded-lg bg-indigo-100 px-3 py-2 text-xs font-semibold text-indigo-700">
                            Jumlah: {{ $detail->quantity }} buku
                        </span>

                        @if ($detail->book?->category)

                            <span class="rounded-lg bg-purple-100 px-3 py-2 text-xs font-semibold text-purple-700">
                                {{ $detail->book->category->name }}
                            </span>

                        @endif

                    </div>


                    @if ($detail->book?->description)

                        <p class="mt-4 text-sm leading-6 text-slate-500">
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


{{-- BORROWING PROOF --}}
<div class="mt-6 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

    <div>

        <h2 class="text-xl font-bold text-slate-900">
            📸 Bukti Peminjaman
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Foto yang kamu kirim saat mengajukan peminjaman.
        </p>

    </div>


    @if ($loan->borrowing_proof)

        <div class="mt-5 overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">

            <img
                src="{{ asset('storage/' . $loan->borrowing_proof) }}"
                alt="Bukti Peminjaman"
                class="mx-auto max-h-[600px] w-auto max-w-full object-contain"
            >

        </div>

    @else

        <div class="mt-5 rounded-2xl bg-slate-50 p-8 text-center">

            <div class="text-4xl">
                📷
            </div>

            <p class="mt-2 text-sm text-slate-500">
                Belum ada foto bukti peminjaman.
            </p>

        </div>

    @endif

</div>


{{-- RETURN PROOF --}}
@if ($loan->return_proof)

    <div class="mt-6 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

        <div>

            <h2 class="text-xl font-bold text-slate-900">
                🔄 Bukti Pengembalian
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

    <div class="mt-6 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

        <h2 class="text-xl font-bold text-slate-900">
            📝 Catatan
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

    <div class="mt-6 rounded-3xl border border-orange-200 bg-orange-50 p-6">

        <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <h2 class="text-lg font-bold text-orange-900">
                    🔄 Sudah Mengembalikan Buku?
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
                🔄 Ajukan Pengembalian
            </a>

        </div>

    </div>

@elseif ($loan->status === 'return_pending')

    <div class="mt-6 rounded-3xl border border-orange-200 bg-orange-50 p-6">

        <div class="flex items-start gap-4">

            <div class="text-3xl">
                ⏳
            </div>

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

    <div class="mt-6 rounded-3xl border border-green-200 bg-green-50 p-6">

        <div class="flex items-start gap-4">

            <div class="text-3xl">
                ✅
            </div>

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

    <div class="mt-6 rounded-3xl border border-red-200 bg-red-50 p-6">

        <div class="flex items-start gap-4">

            <div class="text-3xl">
                ❌
            </div>

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
