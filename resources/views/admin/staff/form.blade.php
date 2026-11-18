@extends('layouts.app')

@section('title', $staff->exists ? 'Edit Pegawai' : 'Tambah Pegawai')

@section('content')
@php
    $field = 'w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100';
@endphp

<div class="min-h-screen bg-slate-50 py-6 sm:py-8">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">

        <a href="{{ route('admin.staff.index') }}" class="text-sm font-semibold text-slate-500 hover:text-indigo-600">
            &larr; Kembali ke Aktivasi Pegawai
        </a>

        <h1 class="mt-4 text-2xl font-bold text-slate-900 sm:text-3xl">
            {{ $staff->exists ? 'Edit Data Pegawai' : 'Tambah Pegawai' }}
        </h1>

        @if ($errors->any())
            <div class="mt-5 rounded-2xl border border-red-200 bg-red-50 p-4">
                <ul class="list-inside list-disc space-y-1 text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            action="{{ $staff->exists ? route('admin.staff.update', $staff) : route('admin.staff.store') }}"
            method="POST"
            class="mt-6 space-y-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6"
        >
            @csrf
            @if ($staff->exists)
                @method('PUT')
            @endif

            <div class="grid gap-5 sm:grid-cols-2">

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">NIP (18 digit) / NIK (16 digit)</label>
                    <input
                        type="text"
                        name="staff_number"
                        value="{{ old('staff_number', $staff->staff_number) }}"
                        inputmode="numeric"
                        maxlength="18"
                        required
                        @readonly($staff->exists)
                        class="{{ $field }} {{ $staff->exists ? 'bg-slate-100 text-slate-500' : '' }}"
                    >
                    @if ($staff->exists)
                        <p class="mt-1 text-xs text-slate-400">Nomor induk adalah kunci data dan tidak dapat diubah.</p>
                    @endif
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $staff->name) }}" required class="{{ $field }}">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Jenis Kelamin</label>
                    <select name="gender" class="{{ $field }} bg-white">
                        <option value="">-</option>
                        <option value="L" @selected(old('gender', $staff->gender) === 'L')>L (Laki-laki)</option>
                        <option value="P" @selected(old('gender', $staff->gender) === 'P')>P (Perempuan)</option>
                    </select>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Agama</label>
                    <input type="text" name="religion" value="{{ old('religion', $staff->religion) }}" class="{{ $field }}" placeholder="ISLAM">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Jabatan</label>
                    <input type="text" name="position" value="{{ old('position', $staff->position) }}" required class="{{ $field }}" placeholder="Guru / Tata Usaha / Kepala Sekolah">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Status Kepegawaian</label>
                    <input type="text" name="employment_status" value="{{ old('employment_status', $staff->employment_status) }}" class="{{ $field }}" placeholder="PNS / PPPK / GTT / PTT">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">NIP berformat (bila ada)</label>
                    <input type="text" name="nip" value="{{ old('nip', $staff->nip) }}" class="{{ $field }}" placeholder="19751211 200501 1 005">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Pangkat</label>
                    <input type="text" name="rank_name" value="{{ old('rank_name', $staff->rank_name) }}" class="{{ $field }}">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Golongan</label>
                    <input type="text" name="grade" value="{{ old('grade', $staff->grade) }}" class="{{ $field }}" placeholder="IV - c">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Mapel / Unit Kerja</label>
                    <input type="text" name="unit" value="{{ old('unit', $staff->unit) }}" class="{{ $field }}">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">No. HP</label>
                    <input type="text" name="phone" value="{{ old('phone', $staff->phone) }}" class="{{ $field }}">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Keterangan</label>
                    <input type="text" name="note" value="{{ old('note', $staff->note) }}" class="{{ $field }}">
                </div>

            </div>

            <div class="flex flex-wrap justify-end gap-3 border-t border-slate-100 pt-5">
                <a href="{{ route('admin.staff.index') }}" class="rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Batal
                </a>

                <button type="submit" class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-indigo-700">
                    Simpan
                </button>
            </div>
        </form>

        @if ($staff->exists && ! $staff->user_id)
            <form
                action="{{ route('admin.staff.destroy', $staff) }}"
                method="POST"
                class="mt-4 text-right"
                onsubmit="return confirm('Hapus data pegawai ini dari daftar?')"
            >
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-800">
                    Hapus data pegawai
                </button>
            </form>
        @endif

    </div>
</div>
@endsection
