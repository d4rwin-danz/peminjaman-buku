@extends('layouts.app')

@section('title', $student->exists ? 'Edit Siswa' : 'Tambah Siswa')

@section('content')
@php
    $field = 'w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100';
@endphp

<div class="min-h-screen bg-slate-50 py-6 sm:py-8">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">

        <a href="{{ route('admin.students.index') }}" class="text-sm font-semibold text-slate-500 hover:text-indigo-600">
            &larr; Kembali ke Aktivasi Siswa
        </a>

        <h1 class="mt-4 text-2xl font-bold text-slate-900 sm:text-3xl">
            {{ $student->exists ? 'Edit Data Siswa' : 'Tambah Siswa' }}
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
            action="{{ $student->exists ? route('admin.students.update', $student) : route('admin.students.store') }}"
            method="POST"
            class="mt-6 space-y-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6"
        >
            @csrf
            @if ($student->exists)
                @method('PUT')
            @endif

            <div class="grid gap-5 sm:grid-cols-2">

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">NIS</label>
                    <input
                        type="text"
                        name="nis"
                        value="{{ old('nis', $student->nis) }}"
                        inputmode="numeric"
                        required
                        @readonly($student->exists)
                        class="{{ $field }} {{ $student->exists ? 'bg-slate-100 text-slate-500' : '' }}"
                    >
                    @if ($student->exists)
                        <p class="mt-1 text-xs text-slate-400">NIS adalah kunci data dan tidak dapat diubah.</p>
                    @endif
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Nama Siswa</label>
                    <input type="text" name="name" value="{{ old('name', $student->name) }}" required class="{{ $field }}">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Jenis Kelamin</label>
                    <select name="gender" required class="{{ $field }} bg-white">
                        <option value="L" @selected(old('gender', $student->gender) === 'L')>L (Laki-laki)</option>
                        <option value="P" @selected(old('gender', $student->gender) === 'P')>P (Perempuan)</option>
                    </select>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Agama</label>
                    <input type="text" name="religion" value="{{ old('religion', $student->religion) }}" class="{{ $field }}" placeholder="ISLAM">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Kelas</label>
                    <input type="text" name="class_name" value="{{ old('class_name', $student->class_name) }}" required class="{{ $field }}" placeholder="XII RPL A">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Wali Kelas</label>
                    <input type="text" name="homeroom_teacher" value="{{ old('homeroom_teacher', $student->homeroom_teacher) }}" class="{{ $field }}">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Bidang Keahlian</label>
                    <input type="text" name="field_of_study" value="{{ old('field_of_study', $student->field_of_study) }}" class="{{ $field }}">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Program Keahlian</label>
                    <input type="text" name="study_program" value="{{ old('study_program', $student->study_program) }}" class="{{ $field }}">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Konsentrasi Keahlian</label>
                    <input type="text" name="concentration" value="{{ old('concentration', $student->concentration) }}" class="{{ $field }}">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Tahun Ajaran</label>
                    <input type="text" name="academic_year" value="{{ old('academic_year', $student->academic_year ?: '2026/2027') }}" required class="{{ $field }}">
                </div>

            </div>

            <div class="flex flex-wrap justify-end gap-3 border-t border-slate-100 pt-5">
                <a href="{{ route('admin.students.index') }}" class="rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Batal
                </a>

                <button type="submit" class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-indigo-700">
                    Simpan
                </button>
            </div>
        </form>

        @if ($student->exists && ! $student->user_id)
            <form
                action="{{ route('admin.students.destroy', $student) }}"
                method="POST"
                class="mt-4 text-right"
                onsubmit="return confirm('Hapus data siswa ini dari daftar?')"
            >
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-800">
                    Hapus data siswa
                </button>
            </form>
        @endif

    </div>
</div>
@endsection
