@extends('layouts.app')

@section('title', 'Aktivasi Akun - E-Library')

@section('content')

<div class="min-h-[calc(100vh-11rem)] flex items-center justify-center py-4">
    <div class="grid w-full max-w-4xl overflow-hidden rounded-2xl bg-white shadow ring-1 ring-slate-200 md:grid-cols-5">

    <x-auth-shelf
        title="Aktifkan akunmu dengan NIS"
        text="Masukkan NIS dan buat password. Setelah admin menyetujui, kamu tinggal login dengan NIS dan password itu."
    />

    <div class="p-8 sm:p-10 md:col-span-3">

        <div class="mb-8">
            <h1 class="text-3xl font-semibold text-ink-900">
                Aktivasi akun siswa
            </h1>

            <p class="text-slate-500 mt-2">
                Berlaku untuk siswa dan pegawai/staf sekolah yang datanya sudah terdaftar. Belum terdaftar? Minta admin perpustakaan menambahkan datamu dulu. Buat password sendiri, lalu tunggu persetujuan admin.
            </p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 text-red-700 border border-red-200 rounded-lg p-4 mb-5">
                <ul class="text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('activation.process') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="identity" class="block font-medium mb-2">
                    NIS (siswa) / NIP atau NIK (pegawai)
                </label>

                <input
                    type="text"
                    name="identity"
                    id="identity"
                    value="{{ old('identity') }}"
                    required
                    autofocus
                    inputmode="numeric"
                    pattern="[0-9]*"
                    autocomplete="username"
                    class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                    placeholder="NIS siswa, atau NIP/NIK pegawai (tanpa spasi)"
                >
            </div>

            <div>
                <label for="activate-password" class="block font-medium mb-2">
                    Password
                </label>

                <div class="relative">
                    <input
                        type="password"
                        name="password"
                        id="activate-password"
                        required
                        autocomplete="new-password"
                        class="w-full border border-slate-300 rounded-lg px-4 py-3 pr-12 focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="Minimal 8 karakter"
                    >

                    <button
                        type="button"
                        onclick="togglePassword('activate-password', this)"
                        class="absolute inset-y-0 right-3 flex items-center text-slate-500 hover:text-blue-600"
                        aria-label="Tampilkan password"
                        aria-pressed="false"
                    >
                        <svg class="eye-closed" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 3l18 18"/>
                            <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/>
                            <path d="M9.9 5.2A11 11 0 0 1 12 5c6.4 0 10 7 10 7a16 16 0 0 1-4 4.8"/>
                            <path d="M6.6 6.6C3.7 8.5 2 12 2 12s3.6 7 10 7a10 10 0 0 0 3-.5"/>
                        </svg>

                        <svg class="eye-open hidden" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div>
                <label for="activate-password-confirmation" class="block font-medium mb-2">
                    Konfirmasi Password
                </label>

                <div class="relative">
                    <input
                        type="password"
                        name="password_confirmation"
                        id="activate-password-confirmation"
                        required
                        autocomplete="new-password"
                        class="w-full border border-slate-300 rounded-lg px-4 py-3 pr-12 focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="Ulangi password"
                    >

                    <button
                        type="button"
                        onclick="togglePassword('activate-password-confirmation', this)"
                        class="absolute inset-y-0 right-3 flex items-center text-slate-500 hover:text-blue-600"
                        aria-label="Tampilkan password"
                        aria-pressed="false"
                    >
                        <svg class="eye-closed" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 3l18 18"/>
                            <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/>
                            <path d="M9.9 5.2A11 11 0 0 1 12 5c6.4 0 10 7 10 7a16 16 0 0 1-4 4.8"/>
                            <path d="M6.6 6.6C3.7 8.5 2 12 2 12s3.6 7 10 7a10 10 0 0 0 3-.5"/>
                        </svg>

                        <svg class="eye-open hidden" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
            </div>

            <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition"
            >
                Minta Aktivasi
            </button>
        </form>

        <p class="text-center text-sm text-slate-500 mt-6">

            Sudah disetujui admin?

            <a
                href="{{ route('login') }}"
                class="text-blue-600 font-semibold hover:underline"
            >
                Login dengan NIS/NIP/NIK
            </a>

        </p>

    </div>

    </div>

</div>

<script>
    function togglePassword(id, button) {
        const input = document.getElementById(id);
        const eyeClosed = button.querySelector('.eye-closed');
        const eyeOpen = button.querySelector('.eye-open');

        const showPassword = input.type === 'password';

        input.type = showPassword ? 'text' : 'password';

        eyeClosed.classList.toggle('hidden', showPassword);
        eyeOpen.classList.toggle('hidden', !showPassword);

        button.setAttribute('aria-pressed', String(showPassword));
        button.setAttribute(
            'aria-label',
            showPassword ? 'Sembunyikan password' : 'Tampilkan password'
        );
    }
</script>

@endsection
