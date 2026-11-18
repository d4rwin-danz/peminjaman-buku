<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/x-icon" sizes="any" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <meta name="theme-color" content="#172641">
    <title>@yield('title', 'E-Library')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Literata:opsz,wght@7..72,500;7..72,600;7..72,700&family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Satu skala warna "tinta" dipakai untuk semua yang sebelumnya biru/indigo,
        // supaya seluruh halaman (siswa & admin) berbicara dengan satu warna.
        const tinta = {
            50: '#F1F4F9', 100: '#E1E7F1', 200: '#C3CFE3', 300: '#9AAECF',
            400: '#6B87B5', 500: '#456497', 600: '#2F4D80', 700: '#253F6B',
            800: '#1E3256', 900: '#172641', 950: '#0F1829',
        };

        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Public Sans"', 'system-ui', 'sans-serif'],
                        serif: ['Literata', 'Georgia', 'serif'],
                    },
                    colors: {
                        ink: tinta,
                        blue: tinta,
                        indigo: tinta,
                        paper: '#F4F5F7',
                        marker: '#E2B13C',
                    },
                    // Radius lebih rapat dan konsisten (sebelumnya terlalu "bulat").
                    borderRadius: {
                        lg: '0.375rem',
                        xl: '0.5rem',
                        '2xl': '0.625rem',
                        '3xl': '0.75rem',
                    },
                    // Garis tipis menggantikan bayangan lembut yang seragam.
                    boxShadow: {
                        sm: '0 0 0 1px rgb(15 23 42 / 0.06)',
                        DEFAULT: '0 0 0 1px rgb(15 23 42 / 0.07)',
                        md: '0 2px 8px -2px rgb(15 23 42 / 0.12)',
                        lg: '0 6px 16px -6px rgb(15 23 42 / 0.2)',
                        xl: '0 10px 24px -8px rgb(15 23 42 / 0.25)',
                        '2xl': '0 20px 40px -12px rgb(15 23 42 / 0.35)',
                    },
                },
            },
        };
    </script>

    <style>
        body { -webkit-font-smoothing: antialiased; }

        /* Judul halaman memakai serif bergaya buku; isi tetap sans-serif. */
        h1 { font-family: Literata, Georgia, serif; letter-spacing: -0.01em; }
        .font-display { font-family: Literata, Georgia, serif; }

        :focus-visible { outline: 2px solid #2F4D80; outline-offset: 2px; }

        /* Kartu katalog: motif khas perpustakaan untuk area pencarian. */
        .catalog-card {
            background-color: #fff;
            background-image: repeating-linear-gradient(
                to bottom, transparent 0, transparent 31px, #E1E7F1 31px, #E1E7F1 32px
            );
            background-position: 0 14px;
        }
        .catalog-card::after {
            content: "";
            position: absolute;
            left: 50%;
            bottom: 10px;
            width: 14px;
            height: 14px;
            margin-left: -7px;
            border-radius: 9999px;
            background: var(--hole, #F4F5F7);
            box-shadow: inset 0 1px 2px rgb(15 23 42 / 0.25);
        }

        /* Rak buku di beranda: punggung buku jatuh ke rak satu per satu (sekali saat dibuka). */
        .spine {
            display: block;
            flex: none;
            position: relative;
            border-radius: 3px 3px 1px 1px;
            padding: 14px 0;
            transition: transform .2s ease, box-shadow .2s ease;
            animation: spine-in .55s cubic-bezier(.2,.8,.2,1) backwards;
            animation-delay: calc(var(--i) * 55ms);
            box-shadow: inset -3px 0 0 rgb(0 0 0 / .12), inset 2px 0 0 rgb(255 255 255 / .18);
        }
        .spine::before, .spine::after {
            content: "";
            position: absolute;
            left: 0; right: 0;
            height: 3px;
            background: rgb(0 0 0 / .18);
        }
        .spine::before { top: 8px; }
        .spine::after { bottom: 8px; }
        .spine:hover, .spine:focus-visible {
            transform: translateY(-14px);
            box-shadow: inset -3px 0 0 rgb(0 0 0 / .12), inset 2px 0 0 rgb(255 255 255 / .18), 0 14px 18px -10px rgb(0 0 0 / .6);
        }
        .spine-title {
            writing-mode: vertical-rl;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-height: 100%;
            margin: 0 auto;
            font-family: Literata, Georgia, serif;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .01em;
        }
        @keyframes spine-in {
            from { transform: translateY(-40px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* Sampul buku: tepi punggung di sisi kiri supaya terasa seperti buku sungguhan. */
        .cover { position: relative; }
        .cover::after {
            content: "";
            position: absolute;
            inset: 0 auto 0 0;
            width: 9px;
            background: linear-gradient(to right, rgb(0 0 0 / .28), rgb(255 255 255 / .16) 45%, transparent);
            pointer-events: none;
        }


        /* Tabel admin -> kartu bertumpuk di layar kecil (tanpa geser horizontal). */
        @media (max-width: 767px) {
            .stack-table, .stack-table tbody, .stack-table tr, .stack-table td { display: block; width: 100%; }
            .stack-table thead { display: none; }
            .stack-table tr { padding: 14px 16px; border-bottom: 1px solid #E1E7F1; }
            .stack-table tr:last-child { border-bottom: 0; }
            .stack-table td {
                display: flex; align-items: flex-start; justify-content: space-between;
                gap: 16px; padding: 5px 0 !important; text-align: right; border: 0;
            }
            .stack-table td::before {
                content: attr(data-label); flex: none; padding-top: 2px; text-align: left;
                font-size: 11px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: #94a3b8;
            }
            .stack-table td[data-label=""]::before { display: none; }
            .stack-table td[data-label=""] { justify-content: flex-end; }
            .stack-table td[colspan] { display: block; text-align: center; }
            .stack-table td[colspan]::before { display: none; }
        }

        @media (prefers-reduced-motion: reduce) {
            .spine { animation: none; }
            * { transition: none !important; animation: none !important; }
        }
    </style>
</head>

<body class="flex min-h-screen flex-col bg-paper font-sans text-slate-800">

@php
    $authUser = auth()->user();

    // [label, route, pola route aktif]
    if ($authUser && $authUser->role === 'admin') {
        $navLinks = [
            ['Dashboard', 'admin.dashboard', 'admin.dashboard'],
            ['Peminjaman', 'admin.loans.index', 'admin.loans.*'],
            ['Aktivasi Siswa', 'admin.students.index', 'admin.students.*'],
            ['Aktivasi Pegawai', 'admin.staff.index', 'admin.staff.*'],
            ['Buku', 'admin.books.index', 'admin.books.*'],
            ['Kategori', 'admin.categories.index', 'admin.categories.*'],
            ['Laporan', 'admin.reports.loans', 'admin.reports.*'],
        ];
        $brandHref = route('admin.dashboard');
    } elseif ($authUser) {
        $navLinks = [
            ['Dashboard', 'siswa.dashboard', 'siswa.dashboard'],
            ['Katalog', 'siswa.books.index', 'siswa.books.*'],
            ['Peminjaman Saya', 'siswa.loans.index', 'siswa.loans.*'],
        ];
        $brandHref = route('siswa.dashboard');
    } else {
        $navLinks = [
            ['Dashboard', 'home', 'home'],
            ['Katalog', 'siswa.books.index', 'siswa.books.*'],
        ];
        $brandHref = route('home');
    }

    $showGuide = ! $authUser || $authUser->role !== 'admin';

    $pendingBadge = ($authUser && $authUser->role === 'admin')
        ? [
            'admin.students.index' => \App\Models\Student::where('activation_status', 'pending')->count(),
            'admin.staff.index' => \App\Models\Staff::where('activation_status', 'pending')->count(),
        ]
        : [];
@endphp

    {{-- ================= NAVBAR ================= --}}
    <header class="sticky top-0 z-40 border-b border-slate-200 bg-white">

        <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-4 sm:px-6">

            {{-- Brand: logo buku terbuka --}}
            <a href="{{ $brandHref }}" class="flex items-center gap-2.5 py-4">
                <img src="{{ asset('logo/logo-mark.svg') }}" width="26" height="26" alt="" class="rounded-md">
                <span class="font-display text-lg font-semibold text-ink-900">E-Library</span>
            </a>

            {{-- Menu desktop --}}
            <nav class="hidden flex-1 items-stretch gap-1 self-stretch md:flex" aria-label="Menu utama">
                @foreach ($navLinks as [$label, $routeName, $pattern])
                    <a
                        href="{{ route($routeName) }}"
                        @class([
                            'flex items-center border-b-2 px-3 text-sm transition-colors',
                            'border-marker font-semibold text-ink-900' => request()->routeIs($pattern),
                            'border-transparent text-slate-600 hover:text-ink-900' => ! request()->routeIs($pattern),
                        ])
                        @if (request()->routeIs($pattern)) aria-current="page" @endif
                    >
                        {{ $label }}
                        @if (($pendingBadge[$routeName] ?? 0) > 0)
                            <span class="ml-1.5 rounded-full bg-amber-200 px-2 py-0.5 text-xs font-bold text-amber-800">{{ $pendingBadge[$routeName] }}</span>
                        @endif
                    </a>
                @endforeach
            </nav>

            {{-- Sisi kanan --}}
            <div class="flex items-center gap-2 sm:gap-3">

                @if ($showGuide)
                    <button
                        type="button"
                        onclick="openStudentGuide()"
                        class="hidden items-center gap-1.5 rounded-lg px-3 py-2 text-sm text-slate-600 transition-colors hover:bg-slate-100 hover:text-ink-900 sm:inline-flex"
                    >
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="10"/><path d="M9.1 9a3 3 0 0 1 5.8 1c0 2-3 2.5-3 4.5"/><path d="M12 17.5h.01"/>
                        </svg>
                        Panduan
                    </button>
                @endif

                @auth
                    <div class="hidden items-center gap-3 border-l border-slate-200 pl-3 sm:flex">
                        <div class="text-right leading-tight">
                            <p class="max-w-[10rem] truncate text-sm font-medium text-slate-800">{{ $authUser->name }}</p>
                            <p class="text-xs text-slate-500">{{ $authUser->role_label }}</p>
                        </div>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button
                                type="submit"
                                class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50"
                            >
                                Keluar
                            </button>
                        </form>
                    </div>
                @endauth

                @guest
                    <a
                        href="{{ route('login') }}"
                        class="hidden rounded-lg px-3 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-100 sm:inline-block"
                    >
                        Masuk
                    </a>

                    <a
                        href="{{ route('activation.show') }}"
                        class="hidden rounded-lg bg-ink-700 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-ink-800 sm:inline-block"
                    >
                        Aktivasi Akun
                    </a>
                @endguest

                {{-- Tombol menu mobile --}}
                <button
                    type="button"
                    id="nav-toggle"
                    class="rounded-lg p-2 text-slate-600 hover:bg-slate-100 md:hidden"
                    aria-label="Buka menu"
                    aria-expanded="false"
                    aria-controls="nav-mobile"
                >
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                        <path d="M4 7h16M4 12h16M4 17h16"/>
                    </svg>
                </button>

            </div>
        </div>

        {{-- Menu mobile --}}
        <div id="nav-mobile" class="hidden border-t border-slate-200 bg-white md:hidden">
            <div class="mx-auto max-w-7xl space-y-1 px-4 py-3 sm:px-6">

                @foreach ($navLinks as [$label, $routeName, $pattern])
                    <a
                        href="{{ route($routeName) }}"
                        @class([
                            'block rounded-lg px-3 py-2.5 text-sm',
                            'bg-ink-50 font-semibold text-ink-900' => request()->routeIs($pattern),
                            'text-slate-700 hover:bg-slate-50' => ! request()->routeIs($pattern),
                        ])
                    >
                        {{ $label }}
                        @if (($pendingBadge[$routeName] ?? 0) > 0)
                            <span class="ml-1.5 rounded-full bg-amber-200 px-2 py-0.5 text-xs font-bold text-amber-800">{{ $pendingBadge[$routeName] }}</span>
                        @endif
                    </a>
                @endforeach

                @if ($showGuide)
                    <button
                        type="button"
                        onclick="openStudentGuide()"
                        class="block w-full rounded-lg px-3 py-2.5 text-left text-sm text-slate-700 hover:bg-slate-50"
                    >
                        Panduan
                    </button>
                @endif

                <div class="border-t border-slate-200 pt-3">
                    @auth
                        <p class="px-3 pb-2 text-sm text-slate-500">
                            {{ $authUser->name }}, {{ strtolower($authUser->role_label) }}
                        </p>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm font-medium text-slate-700">
                                Keluar
                            </button>
                        </form>
                    @else
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('login') }}" class="rounded-lg border border-slate-300 px-3 py-2.5 text-center text-sm font-medium text-slate-700">
                                Masuk
                            </a>
                            <a href="{{ route('activation.show') }}" class="rounded-lg bg-ink-700 px-3 py-2.5 text-center text-sm font-medium text-white">
                                Aktivasi Akun
                            </a>
                        </div>
                    @endauth
                </div>

            </div>
        </div>

    </header>


    {{-- ================= CONTENT ================= --}}
    <main class="mx-auto w-full max-w-7xl flex-1 px-4 py-6 sm:px-6 sm:py-10">

        @if (session('info'))
            <div class="mb-6 rounded-lg border border-ink-200 bg-ink-50 px-4 py-3 text-sm text-ink-800" role="status">
                {{ session('info') }}
            </div>
        @endif

        @if (session('success'))
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
                <p class="mb-2 font-semibold">Periksa kembali isian berikut:</p>

                <ul class="list-inside list-disc space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')

    </main>

    {{-- ================= FOOTER ================= --}}
    <footer class="mt-8 border-t border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-6 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div class="flex items-center gap-2.5">
                <img src="{{ asset('logo/logo-mark.svg') }}" width="22" height="22" alt="" class="rounded-md">
                <span><span class="font-display font-semibold text-ink-900">E-Library</span> &middot; Perpustakaan digital sekolah</span>
            </div>
            <p>&copy; {{ now()->year }} E-Library. Pinjam, baca, kembalikan.</p>
        </div>
    </footer>

    @include('components.guide-modal')

    <script>
        (function () {
            const toggle = document.getElementById('nav-toggle');
            const panel = document.getElementById('nav-mobile');

            toggle.addEventListener('click', function () {
                const open = panel.classList.toggle('hidden') === false;
                toggle.setAttribute('aria-expanded', String(open));
            });
        })();
    </script>

    @stack('scripts')

</body>

</html>
