@extends('layouts.app')

@section('title', 'Aktivasi Akun Pegawai')

@section('content')
<div class="min-h-screen bg-slate-50 py-6 sm:py-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold text-indigo-600">ADMIN E-LIBRARY</p>

                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                    Aktivasi Akun Pegawai
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Admin menambahkan data pegawai/staf lebih dulu. Pegawai mengajukan aktivasi dengan NIP/NIK,
                    admin menyetujui, lalu pegawai login dengan NIP/NIK dan password buatannya.
                </p>
            </div>

            <a
                href="{{ route('admin.staff.create') }}"
                class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50"
            >
                + Tambah Pegawai
            </a>
        </div>

        {{-- Statistik --}}
        <div class="mb-6 grid grid-cols-2 gap-3 lg:grid-cols-4">

            <a href="{{ route('admin.staff.index') }}" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-medium text-slate-500">Total Pegawai</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($counts['total']) }}</p>
            </a>

            <a href="{{ route('admin.staff.index', ['status' => 'pending']) }}" class="rounded-2xl border border-amber-200 bg-amber-50 p-4 shadow-sm">
                <p class="text-xs font-medium text-amber-700">Menunggu ACC</p>
                <p class="mt-2 text-2xl font-bold text-amber-800">{{ number_format($counts['pending']) }}</p>
            </a>

            <a href="{{ route('admin.staff.index', ['status' => 'active']) }}" class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 shadow-sm">
                <p class="text-xs font-medium text-emerald-700">Akun Aktif</p>
                <p class="mt-2 text-2xl font-bold text-emerald-800">{{ number_format($counts['active']) }}</p>
            </a>

            <a href="{{ route('admin.staff.index', ['status' => 'inactive']) }}" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-medium text-slate-500">Belum Aktivasi</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($counts['inactive']) }}</p>
            </a>

        </div>

        {{-- Filter --}}
        <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
            <form action="{{ route('admin.staff.index') }}" method="GET" class="grid gap-3 md:grid-cols-[1fr_180px_200px_auto_auto]">

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Pencarian</label>
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="NIP/NIK, nama, atau jabatan..."
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    >
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Status</label>
                    <select name="status" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                        <option value="">Semua</option>
                        <option value="pending" @selected($status === 'pending')>Menunggu ACC</option>
                        <option value="active" @selected($status === 'active')>Aktif</option>
                        <option value="inactive" @selected($status === 'inactive')>Belum Aktivasi</option>
                        <option value="rejected" @selected($status === 'rejected')>Ditolak</option>
                    </select>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Jabatan</label>
                    <select name="position" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                        <option value="">Semua Jabatan</option>
                        @foreach ($positions as $p)
                            <option value="{{ $p }}" @selected($position === $p)>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 md:w-auto">
                        Cari
                    </button>
                </div>

                <div class="flex items-end">
                    <a href="{{ route('admin.staff.index') }}" class="w-full rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50 md:w-auto">
                        Reset
                    </a>
                </div>

            </form>
        </div>

        {{-- Form kosong untuk ACC massal (checkbox memakai atribut form="bulk-form") --}}
        <form id="bulk-form" action="{{ route('admin.staff.bulk-approve') }}" method="POST">
            @csrf
        </form>

        {{-- Tabel --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <label class="inline-flex cursor-pointer items-center gap-2 text-sm font-medium text-slate-700">
                    <input type="checkbox" id="check-all" class="h-4 w-4 rounded border-slate-300 text-emerald-600">
                    Pilih semua yang menunggu ACC di halaman ini
                </label>

                <button
                    type="submit"
                    form="bulk-form"
                    id="bulk-submit"
                    onclick="return confirm('ACC semua akun yang dicentang?')"
                    class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-bold text-white transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-40"
                >
                    ACC yang Dicentang (<span id="bulk-count">0</span>)
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">
                        <tr>
                            <th class="w-10 px-5 py-4"></th>
                            <th class="px-3 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">NIP / NIK</th>
                            <th class="px-3 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Nama Pegawai</th>
                            <th class="px-3 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">JK / Agama</th>
                            <th class="px-3 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Jabatan / Status</th>
                            <th class="px-3 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Mapel / Pangkat</th>
                            <th class="px-3 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Status</th>
                            <th class="px-5 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @forelse ($staffs as $staff)

                            <tr @class(['transition hover:bg-slate-50', 'bg-amber-50/40' => $staff->activation_status === 'pending'])>

                                <td class="px-5 py-4">
                                    @if ($staff->activation_status === 'pending')
                                        <input
                                            type="checkbox"
                                            name="staff_number[]"
                                            value="{{ $staff->staff_number }}"
                                            form="bulk-form"
                                            class="row-check h-4 w-4 rounded border-slate-300 text-emerald-600"
                                            aria-label="Pilih {{ $staff->name }}"
                                        >
                                    @endif
                                </td>

                                <td class="px-3 py-4 font-mono text-sm font-semibold text-slate-800">
                                    {{ $staff->staff_number }}
                                </td>

                                <td class="px-3 py-4">
                                    <p class="font-semibold text-slate-900">{{ $staff->name }}</p>
                                </td>

                                <td class="px-3 py-4 text-sm text-slate-600">
                                    {{ $staff->gender ?: '-' }}
                                    <span class="text-slate-300">/</span>
                                    {{ $staff->religion ? ucfirst(strtolower($staff->religion)) : '-' }}
                                </td>

                                <td class="px-3 py-4">
                                    <p class="text-sm font-semibold text-slate-700">{{ $staff->position }}</p>
                                    <p class="text-xs text-slate-400">{{ $staff->employment_status ?: '-' }}</p>
                                </td>

                                <td class="px-3 py-4">
                                    <p class="text-sm text-slate-700">{{ $staff->unit ?: '-' }}</p>
                                    <p class="text-xs text-slate-400">{{ $staff->rank_name }} {{ $staff->grade }}</p>
                                </td>

                                <td class="px-3 py-4">
                                    <span class="inline-flex whitespace-nowrap rounded-full px-3 py-1 text-xs font-bold {{ $staff->status_class }}">
                                        {{ $staff->status_label }}
                                    </span>

                                    @if ($staff->activation_status === 'pending' && $staff->activation_requested_at)
                                        <p class="mt-1 text-xs text-slate-400">
                                            Diajukan {{ $staff->activation_requested_at->format('d M Y H:i') }}
                                        </p>
                                    @elseif ($staff->activation_status === 'active' && $staff->activated_at)
                                        <p class="mt-1 text-xs text-slate-400">
                                            Sejak {{ $staff->activated_at->format('d M Y') }}
                                        </p>
                                    @elseif ($staff->activation_status === 'rejected' && $staff->activation_note)
                                        <p class="mt-1 max-w-[12rem] text-xs text-red-500">
                                            {{ $staff->activation_note }}
                                        </p>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-2">

                                        @if ($staff->activation_status === 'pending')
                                            <form action="{{ route('admin.staff.approve', $staff) }}" method="POST">
                                                @csrf
                                                <button
                                                    type="submit"
                                                    class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-bold text-white transition hover:bg-emerald-700"
                                                >
                                                    ACC
                                                </button>
                                            </form>

                                            <button
                                                type="button"
                                                onclick="openReject('{{ route('admin.staff.reject', $staff) }}', @js($staff->name))"
                                                class="rounded-lg bg-red-600 px-3 py-1.5 text-xs font-bold text-white transition hover:bg-red-700"
                                            >
                                                Tolak
                                            </button>
                                        @elseif ($staff->activation_status === 'active')
                                            <form action="{{ route('admin.staff.deactivate', $staff) }}" method="POST"
                                                  onsubmit="return confirm('Nonaktifkan akun ini? Pegawai harus aktivasi ulang untuk bisa login.')">
                                                @csrf
                                                <button type="submit" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                                                    Nonaktifkan
                                                </button>
                                            </form>
                                        @endif

                                        <a
                                            href="{{ route('admin.staff.edit', $staff) }}"
                                            class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50"
                                        >
                                            Edit
                                        </a>

                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <h3 class="font-bold text-slate-900">Tidak Ada Data</h3>
                                    <p class="mt-1 text-sm text-slate-500">Tidak ditemukan pegawai yang sesuai.</p>
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>
        </div>

        @if ($staffs->hasPages())
            <div class="mt-6">
                {{ $staffs->links() }}
            </div>
        @endif

    </div>
</div>

{{-- Modal tolak --}}
<div id="rejectModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4">
    <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl">

        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Tolak Aktivasi</h2>
                <p class="mt-1 text-sm text-slate-500" id="rejectName"></p>
            </div>

            <button type="button" onclick="closeReject()" class="text-2xl text-slate-400 hover:text-slate-700" aria-label="Tutup">&times;</button>
        </div>

        <form id="rejectForm" method="POST" class="mt-5">
            @csrf

            <label class="mb-1.5 block text-sm font-medium text-slate-700">Alasan penolakan</label>
            <input
                type="text"
                name="note"
                maxlength="200"
                required
                placeholder="Contoh: nomor bukan milik pengaju"
                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100"
            >

            <div class="mt-5 flex justify-end gap-3">
                <button type="button" onclick="closeReject()" class="rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Batal
                </button>

                <button type="submit" class="rounded-xl bg-red-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-red-700">
                    Tolak
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    function openReject(action, name) {
        document.getElementById('rejectForm').action = action;
        document.getElementById('rejectName').textContent = name;
        const modal = document.getElementById('rejectModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeReject() {
        const modal = document.getElementById('rejectModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    (function () {
        const rows = Array.from(document.querySelectorAll('.row-check'));
        const all = document.getElementById('check-all');
        const submit = document.getElementById('bulk-submit');
        const count = document.getElementById('bulk-count');

        function sync() {
            const n = rows.filter(function (c) { return c.checked; }).length;
            count.textContent = n;
            submit.disabled = n === 0;
            all.checked = rows.length > 0 && n === rows.length;
        }

        all.addEventListener('change', function () {
            rows.forEach(function (c) { c.checked = all.checked; });
            sync();
        });

        rows.forEach(function (c) { c.addEventListener('change', sync); });
        sync();
    })();
</script>
@endsection
