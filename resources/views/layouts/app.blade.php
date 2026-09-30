<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'E-Library')
    </title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100 text-slate-800 min-h-screen">

    {{-- ================= NAVBAR ================= --}}
    <nav class="bg-slate-900 text-white shadow-lg">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4">

            <div class="flex items-center justify-between">

                {{-- Logo --}}
                <a
                    href="{{ auth()->check()
                        ? (auth()->user()->role === 'admin'
                            ? route('admin.dashboard')
                            : route('siswa.dashboard'))
                        : route('login') }}"
                    class="text-xl font-bold"
                >
                    📚 E-Library
                </a>


                @auth

                    <div class="flex items-center gap-6">

                        {{-- ================= ADMIN MENU ================= --}}
                        @if (auth()->user()->role === 'admin')

                            <div class="hidden md:flex items-center gap-5 text-sm">

                                <a
                                    href="{{ route('admin.dashboard') }}"
                                    class="hover:text-blue-300 transition"
                                >
                                    Dashboard
                                </a>

                                <a
                                    href="{{ route('admin.categories.index') }}"
                                    class="hover:text-blue-300 transition"
                                >
                                    Kategori
                                </a>

                                <a
                                    href="{{ route('admin.books.index') }}"
                                    class="hover:text-blue-300 transition"
                                >
                                    Buku
                                </a>

                                {{-- STAGE 9 --}}
                                <a
                                    href="{{ route('admin.reports.loans') }}"
                                    class="hover:text-blue-300 transition"
                                >
                                    📊 Laporan
                                </a>

                            </div>


                        {{-- ================= SISWA MENU ================= --}}
                        @elseif (auth()->user()->role === 'siswa')

                            <div class="hidden md:flex items-center gap-5 text-sm">

                                <a
                                    href="{{ route('siswa.dashboard') }}"
                                    class="hover:text-blue-300 transition"
                                >
                                    Dashboard
                                </a>

                                <a
                                    href="{{ route('siswa.books.index') }}"
                                    class="hover:text-blue-300 transition"
                                >
                                    📚 Katalog Buku
                                </a>

                                <a
                                    href="{{ route('siswa.loans.index') }}"
                                    class="hover:text-blue-300 transition"
                                >
                                    Peminjaman Saya
                                </a>

                            </div>

                        @endif


                        {{-- ================= USER + LOGOUT ================= --}}
                        <div class="flex items-center gap-4">

                            <span class="hidden sm:block text-sm text-slate-300">
                                {{ auth()->user()->name }}

                                <span class="text-slate-500">
                                    •
                                </span>

                                {{ ucfirst(auth()->user()->role) }}
                            </span>


                            <form
                                action="{{ route('logout') }}"
                                method="POST"
                            >
                                @csrf

                                <button
                                    type="submit"
                                    class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg text-sm font-medium transition"
                                >
                                    Logout
                                </button>
                            </form>

                        </div>

                    </div>

                @endauth

            </div>

        </div>

    </nav>


    {{-- ================= CONTENT ================= --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-8">

        {{-- Success Message --}}
        @if (session('success'))

            <div class="mb-6 rounded-lg bg-green-100 border border-green-200 px-4 py-3 text-green-700">
                {{ session('success') }}
            </div>

        @endif


        {{-- Error Message --}}
        @if (session('error'))

            <div class="mb-6 rounded-lg bg-red-100 border border-red-200 px-4 py-3 text-red-700">
                {{ session('error') }}
            </div>

        @endif


        {{-- Validation Errors --}}
        @if ($errors->any())

            <div class="mb-6 rounded-lg bg-red-100 border border-red-200 px-4 py-3 text-red-700">

                <p class="font-semibold mb-2">
                    Terdapat kesalahan:
                </p>

                <ul class="list-disc list-inside text-sm">

                    @foreach ($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        @yield('content')

    </main>

</body>

</html>