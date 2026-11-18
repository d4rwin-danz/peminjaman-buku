@extends('layouts.app')

@section('title', 'Aktivasi Akun Siswa')

@section('content')
<div class="min-h-screen bg-slate-50 py-6 sm:py-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold text-indigo-600">ADMIN E-LIBRARY</p>

                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                    Aktivasi Akun Siswa
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Data siswa mengikuti DADIR kelas XII. Siswa mengajukan aktivasi dengan NIS,
                    admin menyetujui, lalu siswa login dengan NIS dan password buatannya.
                </p>
            </div>

            <a
                href="{{ route('admin.students.create') }}"
                class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50"
            >
                + Tambah Siswa
            </a>
        </div>

        {{-- Statistik --}}
        <div class="mb-6 grid grid-cols-2 gap-3 lg:grid-cols-4">

            <a href="{{ route('admin.students.index') }}" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-medium text-slate-500">Total Siswa (DADIR)</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($counts['total']) }}</p>
            </a>

            <a href="{{ route('admin.students.index', ['status' => 'pending']) }}" class="rounded-2xl border border-amber-200 bg-amber-50 p-4 shadow-sm">
                <p class="text-xs font-medium text-amber-700">Menunggu ACC</p>
                <p class="mt-2 text-2xl font-bold text-amber-800">{{ number_format($counts['pending']) }}</p>
            </a>

            <a href="{{ route('admin.students.index', ['status' => 'active']) }}" class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 shadow-sm">
                <p class="text-xs font-medium text-emerald-700">Akun Aktif</p>
                <p class="mt-2 text-2xl font-bold text-emerald-800">{{ number_format($counts['active']) }}</p>
            </a>

            <a href="{{ route('admin.students.index', ['status' => 'inactive']) }}" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-medium text-slate-500">Belum Aktivasi</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($counts['inactive']) }}</p>
            </a>

        </div>

        {{-- Filter --}}
        <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
            <form action="{{ route('admin.students.index') }}" method="GET" class="grid gap-3 md:grid-cols-[1fr_180px_200px_auto_auto]">

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Pencarian</label>
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="NIS, nama, atau kelas..."
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
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Kelas</label>
                    <select name="class" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                        <option value="">Semua Kelas</option>
                        @foreach ($classes as $c)
                            <option value="{{ $c }}" @selected($class === $c)>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 md:w-auto">
                        Cari
                    </button>
                </div>

                <div class="flex items-end">
                    <a href="{{ route('admin.students.index') }}" class="w-full rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50 md:w-auto">
                        Reset
                    </a>
                </div>

            </form>
        </div>

        {{-- Form kosong untuk ACC massal (checkbox memakai atribut form="bulk-form") --}}
        <form id="bulk-form" action="{{ route('admin.students.bulk-approve') }}" method="POST">
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
                            <th class="px-3 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">NIS</th>
                            <th class="px-3 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Nama Siswa</th>
                            <th class="px-3 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">JK / Agama</th>
                            <th class="px-3 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Kelas / Wali Kelas</th>
                            <th class="px-3 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Program Keahlian</th>
                            <th class="px-3 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Status</th>
                            <th class="px-5 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @forelse ($students as $student)

                            <tr @class(['transition hover:bg-slate-50', 'bg-amber-50/40' => $student->activation_status === 'pending'])>

                                <td class="px-5 py-4">
                                    @if ($student->activation_status === 'pending')
                                        <input
                                            type="checkbox"
                                            name="nis[]"
                                            value="{{ $student->nis }}"
                                            form="bulk-form"
                                            class="row-check h-4 w-4 rounded border-slate-300 text-emerald-600"
                                            aria-label="Pilih {{ $student->name }}"
                                        >
                                    @endif
                                </td>

                                <td class="px-3 py-4 font-mono text-sm font-semibold text-slate-800">
                                    {{ $student->nis }}
                                </td>

                                <td class="px-3 py-4">
                                    <p class="font-semibold text-slate-900">{{ $student->name }}</p>
                                </td>

                                <td class="px-3 py-4 text-sm text-slate-600">
                                    {{ $student->gender ?: '-' }}
                                    <span class="text-slate-300">/</span>
                                    {{ $student->religion ? ucfirst(strtolower($student->religion)) : '-' }}
                                </td>

                                <td class="px-3 py-4">
                                    <p class="text-sm font-semibold text-slate-700">{{ $student->class_name }}</p>
                                    <p class="text-xs text-slate-400">{{ $student->homeroom_teacher ?: '-' }}</p>
                                </td>

                                <td class="px-3 py-4">
                                    <p class="text-sm text-slate-700">{{ $student->study_program ?: '-' }}</p>
                                    <p class="text-xs text-slate-400">{{ $student->concentration ?: '' }}</p>
                                </td>

                                <td class="px-3 py-4">
                                    <span class="inline-flex whitespace-nowrap rounded-full px-3 py-1 text-xs font-bold {{ $student->status_class }}">
                                        {{ $student->status_label }}
                                    </span>

                                    @if ($student->activation_status === 'pending' && $student->activation_requested_at)
                                        <p class="mt-1 text-xs text-slate-400">
                                            Diajukan {{ $student->activation_requested_at->format('d M Y H:i') }}
                                        </p>
                                    @elseif ($student->activation_status === 'active' && $student->activated_at)
                                        <p class="mt-1 text-xs text-slate-400">
                                            Sejak {{ $student->activated_at->format('d M Y') }}
                                        </p>
                                    @elseif ($student->activation_status === 'rejected' && $student->activation_note)
                                        <p class="mt-1 max-w-[12rem] text-xs text-red-500">
                                            {{ $student->activation_note }}
                                        </p>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-2">

                                        @if ($student->activation_status === 'pending')
                                            <form action="{{ route('admin.students.approve', $student) }}" method="POST">
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
                                                onclick="openReject('{{ route('admin.students.reject', $student) }}', @js($student->name))"
                                                class="rounded-lg bg-red-600 px-3 py-1.5 text-xs font-bold text-white transition hover:bg-red-700"
                                            >
                                                Tolak
                                            </button>
                                        @elseif ($student->activation_status === 'active')
                                            <form action="{{ route('admin.students.deactivate', $student) }}" method="POST"
                                                  onsubmit="return confirm('Nonaktifkan akun ini? Siswa harus aktivasi ulang untuk bisa login.')">
                                                @csrf
                                                <button type="submit" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                                                    Nonaktifkan
                                                </button>
                                            </form>
                                        @endif

                                        <a
                                            href="{{ route('admin.students.edit', $student) }}"
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
                                    <p class="mt-1 text-sm text-slate-500">Tidak ditemukan siswa yang sesuai.</p>
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>
        </div>

        @if ($students->hasPages())
            <div class="mt-6">
                {{ $students->links() }}
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
                placeholder="Contoh: NIS bukan milik pengaju"
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
