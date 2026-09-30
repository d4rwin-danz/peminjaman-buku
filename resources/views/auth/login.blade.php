@extends('layouts.app')

@section('title', 'Login - E-Library')

@section('content')

<div class="min-h-[calc(100vh-72px)] flex items-center justify-center px-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">

        <div class="text-center mb-8">

            <h1 class="text-3xl font-bold text-slate-900">
                E-Library
            </h1>

            <p class="text-slate-500 mt-2">
                Sistem Peminjaman Buku Perpustakaan
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

        <form action="{{ route('login.process') }}" method="POST" class="space-y-5">

            @csrf

            <div>
                <label class="block font-medium mb-2">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                    placeholder="Masukkan email"
                >
            </div>

            <div>
                <label class="block font-medium mb-2">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    required
                    class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                    placeholder="Masukkan password"
                >
            </div>

            <div class="flex items-center gap-2">

                <input
                    type="checkbox"
                    name="remember"
                    value="1"
                    class="rounded"
                >

                <label class="text-sm text-slate-600">
                    Ingat saya
                </label>

            </div>

            <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition"
            >
                Login
            </button>

        </form>

        <p class="text-center text-sm text-slate-500 mt-6">

            Belum memiliki akun?

            <a
                href="{{ route('register') }}"
                class="text-blue-600 font-semibold hover:underline"
            >
                Daftar sebagai siswa
            </a>

        </p>

    </div>

</div>

@endsection