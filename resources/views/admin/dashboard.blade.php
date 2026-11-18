@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

@php
    $statusClass = [
        'pending' => 'bg-amber-100 text-amber-700',
        'approved' => 'bg-indigo-100 text-indigo-700',
        'borrowed' => 'bg-blue-100 text-blue-700',
        'return_pending' => 'bg-orange-100 text-orange-700',
        'returned' => 'bg-green-100 text-green-700',
        'rejected' => 'bg-red-100 text-red-700',
    ];

    $statusLabel = [
        'pending' => 'Menunggu Persetujuan',
        'approved' => 'Disetujui',
        'borrowed' => 'Dipinjam',
        'return_pending' => 'Menunggu Konfirmasi Kembali',
        'returned' => 'Dikembalikan',
        'rejected' => 'Ditolak',
    ];

    $groups = [
        [
            'title' => 'Perlu Diurus',
            'hint' => 'Pengajuan, pengembalian, dan keterlambatan. Urus dari yang paling atas.',
            'list' => $perluDiurus,
            'head' => 'bg-amber-50 text-amber-800',
            'empty' => 'Tidak ada yang perlu diurus saat ini.',
        ],
        [
            'title' => 'Sedang Dipinjam',
            'hint' => 'Masih dalam masa pinjam.',
            'list' => $sedangBerjalan,
            'head' => 'bg-blue-50 text-blue-800',
            'empty' => null,
        ],
        [
            'title' => 'Selesai',
            'hint' => 'Sudah dikembalikan atau ditolak.',
            'list' => $selesai,
            'head' => 'bg-slate-100 text-slate-600',
            'empty' => null,
        ],
    ];
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- HEADER --}}
    <div class="mb-6">
        <h1 class="mt-1 text-3xl font-bold text-slate-900">
            Dashboard Admin
        </h1>

        <p class="mt-2 text-slate-500">
            Halo,
            <span class="font-semibold text-slate-700">{{ auth()->user()->name }}</span>.
        </p>
    </div>


    {{-- PERLU DIURUS (paling atas) --}}
    <div class="mb-8 grid grid-cols-2 gap-3 lg:grid-cols-4">

        <a
            href="{{ $siswaPending >= $pegawaiPending ? route('admin.students.index', ['status' => 'pending']) : route('admin.staff.index', ['status' => 'pending']) }}"
            @class([
                'rounded-2xl border p-4 shadow-sm transition hover:shadow',
                'border-amber-300 bg-amber-50' => $aktivasiPending > 0,
                'border-slate-200 bg-white' => $aktivasiPending === 0,
            ])
        >
            <p class="text-xs font-semibold text-slate-600">Aktivasi Akun Menunggu</p>
            <p class="mt-2 text-3xl font-bold {{ $aktivasiPending > 0 ? 'text-amber-700' : 'text-slate-400' }}">{{ $aktivasiPending }}</p>
            <p class="mt-1 text-xs text-slate-500">Siswa {{ $siswaPending }} &middot; Pegawai {{ $pegawaiPending }}</p>
        </a>

        <a
            href="{{ route('admin.loans.index', ['status' => 'pending']) }}"
            @class([
                'rounded-2xl border p-4 shadow-sm transition hover:shadow',
                'border-amber-300 bg-amber-50' => $peminjamanPending > 0,
                'border-slate-200 bg-white' => $peminjamanPending === 0,
            ])
        >
            <p class="text-xs font-semibold text-slate-600">Pengajuan Pinjam</p>
            <p class="mt-2 text-3xl font-bold {{ $peminjamanPending > 0 ? 'text-amber-700' : 'text-slate-400' }}">{{ $peminjamanPending }}</p>
            <p class="mt-1 text-xs text-slate-500">Menunggu persetujuan</p>
        </a>

        <a
            href="{{ route('admin.loans.index', ['status' => 'return_pending']) }}"
            @class([
                'rounded-2xl border p-4 shadow-sm transition hover:shadow',
                'border-orange-300 bg-orange-50' => $menungguKembali > 0,
                'border-slate-200 bg-white' => $menungguKembali === 0,
            ])
        >
            <p class="text-xs font-semibold text-slate-600">Pengembalian</p>
            <p class="mt-2 text-3xl font-bold {{ $menungguKembali > 0 ? 'text-orange-700' : 'text-slate-400' }}">{{ $menungguKembali }}</p>
            <p class="mt-1 text-xs text-slate-500">Menunggu konfirmasi</p>
        </a>

        <div
            @class([
                'rounded-2xl border p-4 shadow-sm',
                'border-red-300 bg-red-50' => $terlambat > 0,
                'border-slate-200 bg-white' => $terlambat === 0,
            ])
        >
            <p class="text-xs font-semibold text-slate-600">Terlambat</p>
            <p class="mt-2 text-3xl font-bold {{ $terlambat > 0 ? 'text-red-700' : 'text-slate-400' }}">{{ $terlambat }}</p>
            <p class="mt-1 text-xs text-slate-500">Lewat jatuh tempo</p>
        </div>

    </div>


    {{-- DAFTAR PEMINJAMAN: perlu diurus di atas, selesai di bawah --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 lg:col-span-2">

            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
                <div>
                    <h2 class="font-bold text-slate-900">Daftar Peminjaman</h2>
                    <p class="mt-1 text-xs text-slate-400">
                        {{ $jumlahPerluDiurus }} perlu diurus &middot; yang selesai ada di bagian bawah
                    </p>
                </div>

                <a href="{{ route('admin.loans.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                    Lihat semua
                </a>
            </div>

            @foreach ($groups as $group)

                @if ($group['list']->count() || $group['empty'])

                    <div class="flex items-center justify-between px-6 py-2 text-xs font-bold uppercase tracking-wider {{ $group['head'] }}">
                        <span>{{ $group['title'] }} ({{ $group['list']->count() }}{{ $group['title'] === 'Perlu Diurus' && $jumlahPerluDiurus > $group['list']->count() ? ' dari ' . $jumlahPerluDiurus : '' }})</span>
                        <span class="hidden font-medium normal-case tracking-normal sm:inline">{{ $group['hint'] }}</span>
                    </div>

                    @if ($group['list']->isEmpty())

                        <p class="px-6 py-6 text-sm text-slate-400">{{ $group['empty'] }}</p>

                    @else

                        <div class="divide-y divide-slate-100">

                            @foreach ($group['list'] as $loan)

                                @php
                                    $overdue = $loan->isOverdue();
                                    $lateDays = $overdue ? (int) $loan->due_date->diffInDays(now()->startOfDay()) : 0;
                                @endphp

                                <div class="px-6 py-5">

                                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

                                        <div class="min-w-0">

                                            <div class="flex items-center gap-3">

                                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-100 font-bold text-indigo-600">
                                                    {{ strtoupper(substr($loan->user->name, 0, 1)) }}
                                                </div>

                                                <div class="min-w-0">
                                                    <p class="truncate font-semibold text-slate-800">{{ $loan->user->name }}</p>

                                                    <p class="truncate text-xs text-slate-400">
                                                        {{ $loan->user->identifier }}
                                                        @if ($loan->user->unit_label)
                                                            &middot; {{ $loan->user->unit_label }}
                                                        @endif
                                                    </p>
                                                </div>

                                            </div>

                                            <div class="mt-3 space-y-1">
                                                @foreach ($loan->details as $detail)
                                                    <p class="text-sm text-slate-600">
                                                        {{ $detail->book->title }}
                                                        <span class="text-slate-400">&times; {{ $detail->quantity }}</span>
                                                    </p>
                                                @endforeach
                                            </div>

                                        </div>

                                        <div class="flex shrink-0 flex-col items-start gap-2 sm:items-end">

                                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass[$loan->status] ?? 'bg-slate-100 text-slate-600' }}">
                                                {{ $statusLabel[$loan->status] ?? ucfirst($loan->status) }}
                                            </span>

                                            @if ($overdue)
                                                <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                                    Terlambat {{ $lateDays }} hari
                                                </span>
                                            @endif

                                            <a
                                                href="{{ route('admin.loans.show', $loan) }}"
                                                class="text-xs font-semibold text-indigo-600 hover:text-indigo-800"
                                            >
                                                Detail
                                            </a>

                                        </div>

                                    </div>

                                    {{-- Pending: ceklis + tombol, langsung berstatus dipinjam --}}
                                    @if ($loan->status === 'pending')

                                        <form
                                            action="{{ route('admin.loans.approve', $loan) }}"
                                            method="POST"
                                            data-handover
                                            class="mt-4 flex flex-col gap-3 rounded-xl bg-amber-50 p-3 sm:flex-row sm:items-center sm:justify-between"
                                        >
                                            @csrf
                                            <input type="hidden" name="redirect_to" value="dashboard">

                                            <label class="flex cursor-pointer items-center gap-2 text-sm font-medium text-slate-700">
                                                <input
                                                    type="checkbox"
                                                    name="handed_over"
                                                    value="1"
                                                    class="h-5 w-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                                                >
                                                Buku sudah diserahkan
                                            </label>

                                            <button
                                                type="submit"
                                                class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-bold text-white transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-40"
                                            >
                                                Setujui &amp; Tandai Dipinjam
                                            </button>
                                        </form>

                                    @elseif ($loan->status === 'return_pending')

                                        <div class="mt-4 flex items-center justify-between rounded-xl bg-orange-50 p-3">
                                            <p class="text-sm text-orange-800">Siswa sudah mengunggah bukti pengembalian.</p>

                                            <a
                                                href="{{ route('admin.loans.show', $loan) }}"
                                                class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-bold text-white transition hover:bg-emerald-700"
                                            >
                                                Cek &amp; Konfirmasi
                                            </a>
                                        </div>

                                    @endif

                                </div>

                            @endforeach

                        </div>

                    @endif

                @endif

            @endforeach

            @if ($perluDiurus->isEmpty() && $sedangBerjalan->isEmpty() && $selesai->isEmpty())
                <div class="px-6 py-12 text-center">
                    <p class="font-semibold text-slate-700">Belum ada transaksi peminjaman</p>
                    <p class="mt-1 text-sm text-slate-400">Data peminjaman akan muncul di sini.</p>
                </div>
            @endif

        </div>


        {{-- KOLOM KANAN --}}
        <div class="space-y-6">

            {{-- Aktivasi akun --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

                <div class="flex items-center justify-between">
                    <h2 class="font-bold text-slate-900">Aktivasi Akun</h2>

                    @if ($aktivasiPending > 0)
                        <span class="rounded-full bg-amber-200 px-2.5 py-1 text-xs font-bold text-amber-800">{{ $aktivasiPending }}</span>
                    @endif
                </div>

                <p class="mt-1 text-xs text-slate-400">
                    Siswa {{ number_format($siswaAktif) }}/{{ number_format($totalSiswa) }} aktif &middot; Pegawai {{ number_format($pegawaiAktif) }}/{{ number_format($totalPegawai) }} aktif
                </p>

                @if ($aktivasiMenunggu->isEmpty())
                    <p class="mt-4 text-sm text-slate-400">Tidak ada permintaan aktivasi.</p>
                @else
                    <div class="mt-4 divide-y divide-slate-100">
                        @foreach ($aktivasiMenunggu as $item)
                            <div class="flex items-center justify-between gap-3 py-3">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-slate-800">{{ $item['name'] }}</p>
                                    <p class="truncate text-xs text-slate-400">{{ $item['id'] }} &middot; {{ $item['detail'] }}</p>
                                </div>

                                <form action="{{ $item['approve'] }}" method="POST">
                                    @csrf
                                    <button type="submit" class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-emerald-700">
                                        ACC
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="mt-4 flex flex-wrap gap-x-4 gap-y-1 text-sm font-semibold">
                    <a href="{{ route('admin.students.index') }}" class="text-indigo-600 hover:text-indigo-800">Kelola siswa &rarr;</a>
                    <a href="{{ route('admin.staff.index') }}" class="text-indigo-600 hover:text-indigo-800">Kelola pegawai &rarr;</a>
                </div>

            </div>

            {{-- Ringkasan --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

                <h2 class="font-bold text-slate-900">Ringkasan</h2>

                <dl class="mt-4 space-y-3 text-sm">
                    <div class="flex justify-between"><dt class="text-slate-500">Judul buku</dt><dd class="font-semibold text-slate-800">{{ number_format($totalBuku) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Eksemplar tersedia</dt><dd class="font-semibold text-slate-800">{{ number_format($totalStok) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Eksemplar dipinjam</dt><dd class="font-semibold text-slate-800">{{ number_format($jumlahBukuDipinjam) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Transaksi dipinjam</dt><dd class="font-semibold text-slate-800">{{ $sedangDipinjam }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Dikembalikan</dt><dd class="font-semibold text-slate-800">{{ $sudahDikembalikan }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Ditolak</dt><dd class="font-semibold text-slate-800">{{ $ditolak }}</dd></div>
                </dl>

            </div>

            {{-- Aksi cepat --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">

                <h2 class="font-bold text-slate-900">Aksi Cepat</h2>

                <div class="mt-4 space-y-3">

                    <a href="{{ route('admin.loans.index') }}" class="block rounded-xl border border-slate-200 p-4 transition hover:border-blue-300 hover:bg-blue-50">
                        <p class="font-semibold text-slate-800">Data Peminjaman</p>
                        <p class="text-xs text-slate-400">Proses pengajuan siswa</p>
                    </a>

                    <a href="{{ route('admin.books.index') }}" class="block rounded-xl border border-slate-200 p-4 transition hover:border-indigo-300 hover:bg-indigo-50">
                        <p class="font-semibold text-slate-800">Kelola Buku</p>
                        <p class="text-xs text-slate-400">Tambah dan edit buku</p>
                    </a>

                    <a href="{{ route('admin.categories.index') }}" class="block rounded-xl border border-slate-200 p-4 transition hover:border-purple-300 hover:bg-purple-50">
                        <p class="font-semibold text-slate-800">Kelola Kategori</p>
                        <p class="text-xs text-slate-400">Atur kategori buku</p>
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

<script>
    document.querySelectorAll('form[data-handover]').forEach(function (form) {
        const check = form.querySelector('input[type="checkbox"]');
        const submit = form.querySelector('button[type="submit"]');
        const sync = function () { submit.disabled = !check.checked; };
        check.addEventListener('change', sync);
        sync();
    });
</script>

@endsection
