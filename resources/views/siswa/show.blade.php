@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
<div class="min-h-screen bg-slate-50 py-6 sm:py-8">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6">
            <a
                href="{{ route('siswa.loans.index') }}"
                class="inline-flex items-center text-sm font-semibold text-slate-500 transition hover:text-indigo-600"
            >
                ← Kembali ke Peminjaman Saya
            </a>

            <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-sm font-semibold text-indigo-600">
                        PEMINJAMAN #{{ $loan->id }}
                    </p>

                    <h1 class="mt-1 text-2xl font-bold text-slate-900 sm:text-3xl">
                        Detail Peminjaman
                    </h1>
                </div>

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

                <span class="w-fit rounded-full px-4 py-2 text-sm font-bold {{ $currentStatus['class'] }}">
                    {{ $currentStatus['label'] }}
                </span>
            </div>
        </div>

        {{-- Error --}}
        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4">
                <ul class="list-inside list-disc space-y-1 text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Timeline --}}
        <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

            <h2 class="text-lg font-bold text-slate-900">
                Status Peminjaman
            </h2>

            <div class="mt-6">

                @php
                    $steps = [
                        'pending' => [
                            'title' => 'Pengajuan Dibuat',
                            'description' => 'Pengajuan peminjaman telah dibuat oleh siswa.',
                        ],

                        'approved' => [
                            'title' => 'Disetujui Admin',
                            'description' => 'Pengajuan telah disetujui oleh admin.',
                        ],

                        'borrowed' => [
                            'title' => 'Buku Dipinjam',
                            'description' => 'Buku telah diserahkan dan sedang dipinjam.',
                        ],

                        'return_pending' => [
                            'title' => 'Pengembalian Diajukan',
                            'description' => 'Siswa telah mengajukan pengembalian buku.',
                        ],

                        'returned' => [
                            'title' => 'Buku Dikembalikan',
                            'description' => 'Pengembalian telah dikonfirmasi oleh admin.',
                        ],
                    ];

                    $stepOrder = [
                        'pending',
                        'approved',
                        'borrowed',
                        'return_pending',
                        'returned',
                    ];

                    $currentIndex = array_search($loan->status, $stepOrder);

                    if ($currentIndex === false) {
                        $currentIndex = 0;
                    }
                @endphp

                <div class="space-y-5">

                    @foreach ($stepOrder as $index => $step)

                        @php
                            $stepData = $steps[$step];

                            $completed = $index <= $currentIndex;

                            $isCurrent = $step === $loan->status;

                            if ($loan->status === 'rejected') {
                                $completed = $step === 'pending';
                                $isCurrent = false;
                            }
                        @endphp

                        <div class="flex gap-4">

                            <div class="flex flex-col items-center">
                                <div
                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-sm font-bold
                                    {{ $completed ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-400' }}"
                                >
                                    @if ($completed)
                                        ✓
                                    @else
                                        {{ $index + 1 }}
                                    @endif
                                </div>

                                @if (!$loop->last)
                                    <div
                                        class="mt-2 h-full min-h-8 w-0.5
                                        {{ $index < $currentIndex ? 'bg-indigo-600' : 'bg-slate-200' }}"
                                    ></div>
                                @endif
                            </div>

                            <div class="pb-2">
                                <h3 class="font-semibold {{ $completed ? 'text-slate-900' : 'text-slate-400' }}">
                                    {{ $stepData['title'] }}

                                    @if ($isCurrent)
                                        <span class="ml-2 rounded-full bg-indigo-50 px-2 py-1 text-xs font-semibold text-indigo-600">
                                            Saat ini
                                        </span>
                                    @endif
                                </h3>

                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $stepData['description'] }}
                                </p>
                            </div>

                        </div>

                    @endforeach

                    @if ($loan->status === 'rejected')
                        <div class="flex gap-4">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600">
                                ✕
                            </div>

                            <div>
                                <h3 class="font-semibold text-red-700">
                                    Pengajuan Ditolak
                                </h3>

                                <p class="mt-1 text-sm text-slate-500">
                                    Pengajuan peminjaman ditolak oleh admin.
                                </p>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- Informasi tanggal --}}
        <div class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-medium text-slate-400">
                    Tanggal Pinjam
                </p>

                <p class="mt-2 font-bold text-slate-900">
                    {{ $loan->loan_date?->format('d M Y') ?? '-' }}
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-medium text-slate-400">
                    Batas Pengembalian
                </p>

                <p class="mt-2 font-bold text-slate-900">
                    {{ $loan->due_date?->format('d M Y') ?? '-' }}
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-medium text-slate-400">
                    Tanggal Dikembalikan
                </p>

                <p class="mt-2 font-bold text-slate-900">
                    {{ $loan->return_date?->format('d M Y') ?? '-' }}
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-medium text-slate-400">
                    Keterlambatan
                </p>

                <p class="mt-2 font-bold text-slate-900">
                    {{ $loan->late_days }} hari
                </p>
            </div>

        </div>

        {{-- Denda --}}
        @if ($loan->status === 'returned')
            <div class="mb-6 rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold text-amber-700">
                            Total Denda
                        </p>

                        <p class="mt-1 text-2xl font-bold text-amber-900">
                            Rp {{ number_format($loan->fine, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="text-3xl">
                        💰
                    </div>
                </div>
            </div>
        @endif

        {{-- Buku --}}
        <div class="mb-6 rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 p-5 sm:p-6">
                <h2 class="text-lg font-bold text-slate-900">
                    Buku yang Dipinjam
                </h2>
            </div>

            <div class="divide-y divide-slate-100">

                @foreach ($loan->details as $detail)

                    <div class="flex gap-4 p-5 sm:p-6">

                        <div class="h-24 w-16 shrink-0 overflow-hidden rounded-xl bg-slate-100">
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
                            <h3 class="font-bold text-slate-900">
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
                            </div>
                        </div>

                    </div>

                @endforeach

            </div>
        </div>

        {{-- Bukti --}}
        <div class="mb-6 grid gap-6 md:grid-cols-2">

            {{-- Bukti pinjam --}}
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

            {{-- Bukti kembali --}}
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
                        <div class="text-3xl">📷</div>

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

        {{-- Action --}}
        <div class="flex flex-wrap gap-3">

            @if ($loan->status === 'borrowed')

                <a
                    href="{{ route('siswa.loans.return.create', $loan) }}"
                    class="rounded-xl bg-orange-500 px-5 py-3 text-sm font-bold text-white transition hover:bg-orange-600"
                >
                    Ajukan Pengembalian
                </a>

            @elseif ($loan->status === 'return_pending')

                <div class="rounded-xl bg-orange-50 px-5 py-3 text-sm font-semibold text-orange-700">
                    ⏳ Pengembalian sedang menunggu verifikasi admin.
                </div>

            @elseif ($loan->status === 'pending')

                <div class="rounded-xl bg-amber-50 px-5 py-3 text-sm font-semibold text-amber-700">
                    ⏳ Pengajuan sedang menunggu persetujuan admin.
                </div>

            @elseif ($loan->status === 'approved')

                <div class="rounded-xl bg-blue-50 px-5 py-3 text-sm font-semibold text-blue-700">
                    ✓ Peminjaman telah disetujui. Menunggu proses penyerahan buku.
                </div>

            @elseif ($loan->status === 'returned')

                <div class="rounded-xl bg-emerald-50 px-5 py-3 text-sm font-semibold text-emerald-700">
                    ✓ Buku telah berhasil dikembalikan.
                </div>

            @elseif ($loan->status === 'rejected')

                <div class="rounded-xl bg-red-50 px-5 py-3 text-sm font-semibold text-red-700">
                    ✕ Pengajuan peminjaman ditolak oleh admin.
                </div>

            @endif

            <a
                href="{{ route('siswa.loans.index') }}"
                class="rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50"
            >
                Kembali
            </a>

        </div>

    </div>
</div>
@endsection