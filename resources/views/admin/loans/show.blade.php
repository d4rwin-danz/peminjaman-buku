@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
<div class="min-h-screen bg-slate-50 py-6 sm:py-8">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6">

            <a
                href="{{ route('admin.loans.index') }}"
                class="inline-flex items-center text-sm font-semibold text-slate-500 transition hover:text-indigo-600"
            >
                ← Kembali ke Peminjaman
            </a>

            @php
                $statusConfig = [
                    'pending' => [
                        'label' => 'Menunggu Persetujuan',
                        'class' => 'bg-amber-100 text-amber-700',
                    ],

                    'approved' => [
                        'label' => 'Disetujui',
                        'class' => 'bg-blue-100 text-blue-700',
                    ],

                    'borrowed' => [
                        'label' => 'Sedang Dipinjam',
                        'class' => 'bg-indigo-100 text-indigo-700',
                    ],

                    'return_pending' => [
                        'label' => 'Menunggu Pengembalian',
                        'class' => 'bg-orange-100 text-orange-700',
                    ],

                    'returned' => [
                        'label' => 'Dikembalikan',
                        'class' => 'bg-emerald-100 text-emerald-700',
                    ],

                    'rejected' => [
                        'label' => 'Ditolak',
                        'class' => 'bg-red-100 text-red-700',
                    ],
                ];

                $currentStatus = $statusConfig[$loan->status] ?? [
                    'label' => ucfirst($loan->status),
                    'class' => 'bg-slate-100 text-slate-700',
                ];
            @endphp

            <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">

                <div>
                    <p class="text-sm font-semibold text-indigo-600">
                        PEMINJAMAN #{{ $loan->id }}
                    </p>

                    <h1 class="mt-1 text-2xl font-bold text-slate-900 sm:text-3xl">
                        Detail Peminjaman
                    </h1>
                </div>

                <span class="w-fit rounded-full px-4 py-2 text-sm font-bold {{ $currentStatus['class'] }}">
                    {{ $currentStatus['label'] }}
                </span>

            </div>
        </div>

        {{-- Validation --}}
        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4">

                <ul class="list-inside list-disc space-y-1 text-sm text-red-700">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>
        @endif

        {{-- Siswa --}}
        <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

            <h2 class="text-lg font-bold text-slate-900">
                Informasi Siswa
            </h2>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">

                <div>
                    <p class="text-xs text-slate-400">
                        Nama
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $loan->user->name }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-slate-400">
                        Email
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $loan->user->email }}
                    </p>
                </div>

            </div>

        </div>

        {{-- Informasi transaksi --}}
        <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

            <h2 class="text-lg font-bold text-slate-900">
                Informasi Peminjaman
            </h2>

            <div class="mt-5 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">

                <div>
                    <p class="text-xs text-slate-400">
                        Tanggal Pinjam
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $loan->loan_date?->format('d M Y') ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-slate-400">
                        Jatuh Tempo
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $loan->due_date?->format('d M Y') ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-slate-400">
                        Tanggal Kembali
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $loan->return_date?->format('d M Y') ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-slate-400">
                        Terlambat
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $loan->late_days }} hari
                    </p>
                </div>

            </div>

            @if ($loan->status === 'returned')
                <div class="mt-5 rounded-xl bg-amber-50 p-4">

                    <p class="text-xs font-semibold text-amber-700">
                        Total Denda
                    </p>

                    <p class="mt-1 text-2xl font-bold text-amber-900">
                        Rp {{ number_format($loan->fine, 0, ',', '.') }}
                    </p>

                </div>
            @endif

        </div>

        {{-- Buku --}}
        <div class="mb-6 rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 p-5 sm:p-6">
                <h2 class="text-lg font-bold text-slate-900">
                    Buku
                </h2>
            </div>

            <div class="divide-y divide-slate-100">

                @foreach ($loan->details as $detail)

                    <div class="flex gap-4 p-5 sm:p-6">

                        <div class="h-28 w-20 shrink-0 overflow-hidden rounded-xl bg-slate-100">

                            @if ($detail->book->cover)

                                <img
                                    src="{{ asset('storage/' . $detail->book->cover) }}"
                                    alt="{{ $detail->book->title }}"
                                    class="h-full w-full object-cover"
                                >

                            @else

                                <div class="flex h-full items-center justify-center text-2xl">
                                    📚
                                </div>

                            @endif

                        </div>

                        <div class="min-w-0 flex-1">

                            <h3 class="text-base font-bold text-slate-900">
                                {{ $detail->book->title }}
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                Kode: {{ $detail->book->code }}
                            </p>

                            <p class="text-sm text-slate-500">
                                Penulis: {{ $detail->book->author }}
                            </p>

                            <div class="mt-3 flex flex-wrap gap-2">

                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                    {{ $detail->book->category->name ?? 'Tanpa Kategori' }}
                                </span>

                                <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-600">
                                    Jumlah {{ $detail->quantity }}
                                </span>

                                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
                                    Stok saat ini {{ $detail->book->stock }}
                                </span>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

        {{-- Bukti --}}
        <div class="mb-6 grid gap-6 md:grid-cols-2">

            {{-- Borrowing --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

                <h2 class="font-bold text-slate-900">
                    Bukti Peminjaman
                </h2>

                @if ($loan->borrowing_proof)

                    <a
                        href="{{ asset('storage/' . $loan->borrowing_proof) }}"
                        target="_blank"
                        class="mt-4 block overflow-hidden rounded-xl"
                    >
                        <img
                            src="{{ asset('storage/' . $loan->borrowing_proof) }}"
                            alt="Bukti Peminjaman"
                            class="max-h-80 w-full object-cover transition hover:scale-[1.01]"
                        >
                    </a>

                @else

                    <p class="mt-4 text-sm text-slate-500">
                        Tidak ada bukti peminjaman.
                    </p>

                @endif

            </div>

            {{-- Return --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

                <h2 class="font-bold text-slate-900">
                    Bukti Pengembalian
                </h2>

                @if ($loan->return_proof)

                    <a
                        href="{{ asset('storage/' . $loan->return_proof) }}"
                        target="_blank"
                        class="mt-4 block overflow-hidden rounded-xl"
                    >
                        <img
                            src="{{ asset('storage/' . $loan->return_proof) }}"
                            alt="Bukti Pengembalian"
                            class="max-h-80 w-full object-cover transition hover:scale-[1.01]"
                        >
                    </a>

                @else

                    <div class="mt-4 rounded-xl bg-slate-50 p-6 text-center">

                        <div class="text-3xl">
                            📷
                        </div>

                        <p class="mt-2 text-sm text-slate-500">
                            Bukti pengembalian belum tersedia.
                        </p>

                    </div>

                @endif

            </div>

        </div>

        {{-- Catatan --}}
        @if ($loan->notes)

            <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

                <h2 class="font-bold text-slate-900">
                    Catatan
                </h2>

                <p class="mt-3 whitespace-pre-line text-sm leading-6 text-slate-600">
                    {{ $loan->notes }}
                </p>

            </div>

        @endif

        {{-- Admin Actions --}}
        <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

            <h2 class="text-lg font-bold text-slate-900">
                Tindakan Admin
            </h2>

            <div class="mt-4">

                {{-- Pending --}}
                @if ($loan->status === 'pending')

                    <div class="flex flex-wrap gap-3">

                        <form
                            action="{{ route('admin.loans.approve', $loan) }}"
                            method="POST"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-emerald-700"
                                onclick="return confirm('Yakin ingin menyetujui peminjaman ini?')"
                            >
                                ✓ Setujui Peminjaman
                            </button>

                        </form>

                        <button
                            type="button"
                            onclick="document.getElementById('rejectModal').classList.remove('hidden')"
                            class="rounded-xl bg-red-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-red-700"
                        >
                            ✕ Tolak Peminjaman
                        </button>

                    </div>

                {{-- Approved --}}
                @elseif ($loan->status === 'approved')

                    <form
                        action="{{ route('admin.loans.borrow', $loan) }}"
                        method="POST"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-indigo-700"
                            onclick="return confirm('Tandai buku sudah diserahkan kepada siswa?')"
                        >
                            📚 Tandai Sedang Dipinjam
                        </button>

                    </form>

                {{-- Borrowed --}}
                @elseif ($loan->status === 'borrowed')

                    <div class="rounded-xl bg-indigo-50 px-5 py-4 text-sm font-semibold text-indigo-700">
                        📚 Buku sedang dipinjam oleh siswa.
                    </div>

                {{-- Return pending --}}
                @elseif ($loan->status === 'return_pending')

                    <div class="space-y-4">

                        <div class="rounded-xl bg-orange-50 px-5 py-4">

                            <p class="font-bold text-orange-800">
                                ⏳ Menunggu Konfirmasi Pengembalian
                            </p>

                            <p class="mt-1 text-sm text-orange-700">
                                Siswa telah mengajukan pengembalian dan mengunggah bukti.
                            </p>

                        </div>

                        <form
                            action="{{ route('admin.loans.confirm-return', $loan) }}"
                            method="POST"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-emerald-700"
                                onclick="return confirm('Yakin pengembalian buku sudah diterima? Stok akan otomatis ditambahkan dan denda dihitung.')"
                            >
                                ✓ Konfirmasi Pengembalian
                            </button>

                        </form>

                    </div>

                {{-- Returned --}}
                @elseif ($loan->status === 'returned')

                    <div class="rounded-xl bg-emerald-50 px-5 py-4">

                        <p class="font-bold text-emerald-800">
                            ✓ Peminjaman Selesai
                        </p>

                        <p class="mt-1 text-sm text-emerald-700">
                            Buku telah dikembalikan dan stok telah diperbarui.
                        </p>

                    </div>

                {{-- Rejected --}}
                @elseif ($loan->status === 'rejected')

                    <div class="rounded-xl bg-red-50 px-5 py-4">

                        <p class="font-bold text-red-800">
                            ✕ Peminjaman Ditolak
                        </p>

                        <p class="mt-1 text-sm text-red-700">
                            Peminjaman ini telah ditolak dan tidak dapat diproses kembali.
                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>
</div>

{{-- Modal Reject --}}
@if ($loan->status === 'pending')

    <div
        id="rejectModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4"
    >

        <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl">

            <div class="flex items-center justify-between">

                <div>
                    <h2 class="text-lg font-bold text-slate-900">
                        Tolak Peminjaman
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Masukkan alasan penolakan.
                    </p>
                </div>

                <button
                    type="button"
                    onclick="document.getElementById('rejectModal').classList.add('hidden')"
                    class="text-2xl text-slate-400 hover:text-slate-700"
                >
                    ×
                </button>

            </div>

            <form
                action="{{ route('admin.loans.reject', $loan) }}"
                method="POST"
                class="mt-5"
            >
                @csrf

                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Alasan Penolakan
                </label>

                <textarea
                    name="notes"
                    rows="5"
                    required
                    maxlength="1000"
                    placeholder="Contoh: Stok buku tidak mencukupi..."
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-100"
                >{{ old('notes') }}</textarea>

                <div class="mt-4 flex justify-end gap-3">

                    <button
                        type="button"
                        onclick="document.getElementById('rejectModal').classList.add('hidden')"
                        class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700"
                    >
                        Tolak Peminjaman
                    </button>

                </div>

            </form>

        </div>

    </div>

@endif
@endsection