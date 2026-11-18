{{--
    Pop-up Panduan E-Library (dipakai di semua halaman lewat layouts/app.blade.php)
    - Otomatis tampil sekali per sesi browser untuk pengunjung (belum login) di halaman awal.
    - Bisa dibuka lagi kapan saja lewat tombol "Panduan" (openStudentGuide()).
--}}
@php
    $guideAutoOpen = auth()->guest() && request()->routeIs('home');
    $guideMaxBooks = config('library.max_books_per_loan', 3);
    $guideDays = config('library.loan_duration_days', 7);
    $guideFine = number_format(config('library.fine_per_book_per_day', 1000), 0, ',', '.');

    $guideSteps = [
        [
                        'title' => 'Lihat Dashboard & Katalog',
            'items' => [
                'Dashboard menampilkan ringkasan koleksi dan buku terbaru, tanpa perlu login.',
                'Buka menu Katalog Buku untuk mencari buku berdasarkan judul, kode, penulis, atau kategori.',
            ],
        ],
        [
                        'title' => 'Cek Detail & Lokasi Rak',
            'items' => [
                'Pilih buku untuk melihat deskripsi, stok, dan lokasi rak pada peta perpustakaan.',
                'Pastikan stok tersedia sebelum mengajukan peminjaman.',
            ],
        ],
        [
                        'title' => 'Login Dulu untuk Meminjam',
            'items' => [
                'Melihat buku bebas, tetapi meminjam buku wajib login.',
                'Saat menekan tombol pinjam, kamu akan diarahkan ke halaman login.',
                'Belum punya akun? Pilih Daftar sebagai siswa. Setelah login kamu langsung kembali ke buku yang ingin dipinjam.',
            ],
        ],
        [
                        'title' => 'Ajukan Peminjaman',
            'items' => [
                "Isi jumlah buku (maksimal {$guideMaxBooks} per pengajuan) dan unggah foto bukti peminjaman.",
                'Pantau statusnya di menu Peminjaman Saya, lalu tunggu persetujuan admin.',
                "Lama peminjaman {$guideDays} hari sejak pengajuan.",
            ],
        ],
        [
                        'title' => 'Ajukan Pengembalian',
            'items' => [
                'Buka Peminjaman Saya, pilih buku yang sedang dipinjam, lalu ajukan pengembalian dengan foto bukti.',
                'Admin akan memverifikasi pengembalianmu.',
                "Keterlambatan dikenakan denda Rp {$guideFine} per buku per hari.",
            ],
        ],
    ];
    $guideTotal = count($guideSteps);
@endphp

<div
    id="student-guide-modal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-ink-950/60 px-4 py-6"
    role="dialog"
    aria-modal="true"
    aria-labelledby="student-guide-title"
    aria-hidden="true"
    data-auto-open="{{ $guideAutoOpen ? '1' : '0' }}"
    data-total="{{ $guideTotal }}"
    onclick="if (event.target === this) closeStudentGuide()"
>
    <div class="bg-white w-full max-w-xl max-h-full rounded-2xl shadow-2xl flex flex-col overflow-hidden">

        {{-- Header --}}
        <div class="flex items-start justify-between gap-4 bg-ink-900 p-5 text-white sm:p-6">
            <div>
                <h2 id="student-guide-title" class="font-display text-2xl font-semibold text-white">
                    Cara memakai E-Library
                </h2>
                <p class="text-sm text-ink-200 mt-1">
                    Lima langkah singkat sebelum kamu mulai menjelajah.
                </p>
            </div>

            <button
                type="button"
                onclick="closeStudentGuide()"
                class="shrink-0 rounded-lg p-2 text-ink-200 hover:bg-white/10 hover:text-white transition"
                aria-label="Tutup panduan"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                </svg>
            </button>
        </div>

        {{-- Langkah-langkah --}}
        <div class="overflow-y-auto p-5 sm:p-6 min-h-[270px]">
            @foreach ($guideSteps as $i => $step)
                <div class="guide-step {{ $i === 0 ? '' : 'hidden' }}" data-step="{{ $i }}">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-ink-700 text-lg font-semibold text-white">
                            {{ $i + 1 }}
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">
                                Langkah {{ $i + 1 }} dari {{ $guideTotal }}
                            </p>
                            <h3 class="font-display text-xl font-semibold text-ink-900">{{ $step['title'] }}</h3>
                        </div>
                    </div>

                    <ul class="text-sm text-slate-600 leading-relaxed space-y-2 list-disc pl-5">
                        @foreach ($step['items'] as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>

                </div>
            @endforeach
        </div>

        {{-- Indikator + tombol --}}
        <div class="border-t border-slate-200 p-4 sm:px-6">
            <div class="flex items-center justify-center gap-2 mb-4" id="guide-dots">
                @for ($i = 0; $i < $guideTotal; $i++)
                    <span class="guide-dot h-2 rounded-full transition-all {{ $i === 0 ? 'w-6 bg-blue-600' : 'w-2 bg-slate-300' }}"></span>
                @endfor
            </div>

            <div class="flex items-center justify-between gap-3">
                <button
                    type="button"
                    id="guide-prev"
                    onclick="guideMove(-1)"
                    class="invisible px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 font-semibold hover:bg-slate-50 transition"
                >
                    Sebelumnya
                </button>

                <button
                    type="button"
                    id="guide-next"
                    onclick="guideMove(1)"
                    class="px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold transition"
                >
                    Lanjut
                </button>
            </div>
        </div>

    </div>
</div>

<script>
    (function () {
        const SEEN_KEY = 'elibrary_guide_seen';
        let current = 0;
        let previousFocus = null;

        const modal = () => document.getElementById('student-guide-modal');

        function render() {
            const m = modal();
            const total = parseInt(m.dataset.total, 10);
            const isAuto = m.dataset.autoOpen === '1';

            m.querySelectorAll('.guide-step').forEach((el, i) => {
                el.classList.toggle('hidden', i !== current);
            });

            m.querySelectorAll('.guide-dot').forEach((el, i) => {
                el.className = 'guide-dot h-2 rounded-full transition-all ' +
                    (i === current ? 'w-6 bg-blue-600' : 'w-2 bg-slate-300');
            });

            document.getElementById('guide-prev')
                .classList.toggle('invisible', current === 0);

            const next = document.getElementById('guide-next');
            const last = current === total - 1;

            next.textContent = last
                ? (isAuto ? 'Mengerti, Masuk ke Dashboard' : 'Mengerti')
                : 'Lanjut';
        }

        window.guideMove = function (dir) {
            const total = parseInt(modal().dataset.total, 10);

            if (dir > 0 && current === total - 1) {
                closeStudentGuide();
                return;
            }

            current = Math.min(Math.max(current + dir, 0), total - 1);
            render();
        };

        window.openStudentGuide = function () {
            const m = modal();

            previousFocus = document.activeElement;
            current = 0;
            render();

            m.classList.remove('hidden');
            m.classList.add('flex');
            m.setAttribute('aria-hidden', 'false');
            document.body.classList.add('overflow-hidden');

            document.getElementById('guide-next').focus();
        };

        window.closeStudentGuide = function () {
            const m = modal();

            if (m.classList.contains('hidden')) return;

            m.classList.add('hidden');
            m.classList.remove('flex');
            m.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('overflow-hidden');

            try { sessionStorage.setItem(SEEN_KEY, '1'); } catch (e) {}

            if (previousFocus && typeof previousFocus.focus === 'function') {
                previousFocus.focus();
            }
        };

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeStudentGuide();
        });

        document.addEventListener('DOMContentLoaded', function () {
            if (modal().dataset.autoOpen !== '1') return;

            let seen = false;
            try { seen = sessionStorage.getItem(SEEN_KEY) === '1'; } catch (e) {}

            if (!seen) openStudentGuide();
        });
    })();
</script>
