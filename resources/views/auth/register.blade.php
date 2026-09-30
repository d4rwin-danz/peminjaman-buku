@extends('layouts.app')

@section('title', 'Register - E-Library')

@section('content')

<div class="min-h-[calc(100vh-72px)] flex items-center justify-center px-4 py-10">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">

        <div class="text-center mb-8">

            <h1 class="text-3xl font-bold text-slate-900">
                Daftar Siswa
            </h1>

            <p class="text-slate-500 mt-2">
                Buat akun untuk meminjam buku
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

        <form
            action="{{ route('register.process') }}"
            method="POST"
            class="space-y-5"
        >

            @csrf

            <div>

                <label class="block font-medium mb-2">
                    Nama Lengkap
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                    placeholder="Nama lengkap"
                >

            </div>

            <div>

                <label class="block font-medium mb-2">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                    placeholder="contoh@email.com"
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
                    placeholder="Minimal 8 karakter"
                >

            </div>

            <div>

                <label class="block font-medium mb-2">
                    Konfirmasi Password
                </label>

                <input
                    type="password"
                    name="password_confirmation"
                    required
                    class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                    placeholder="Ulangi password"
                >

            </div>

            <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition"
            >
                Daftar
            </button>

        </form>

        <p class="text-center text-sm text-slate-500 mt-6">

            Sudah memiliki akun?

            <a
                href="{{ route('login') }}"
                class="text-blue-600 font-semibold hover:underline"
            >
                Login
            </a>

        </p>

    </div>

</div>

@endsection