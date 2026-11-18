{{-- Pop-up "Tata cara login" (hanya dipakai di halaman login) --}}
<div
    id="login-guide-modal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-ink-950/60 px-4 py-6"
    role="dialog"
    aria-modal="true"
    aria-labelledby="login-guide-title"
    aria-hidden="true"
    data-auto-open="{{ $errors->any() ? '0' : '1' }}"
    onclick="if (event.target === this) closeLoginGuide()"
>
    <div class="flex max-h-full w-full max-w-lg flex-col overflow-hidden rounded-2xl bg-white shadow-2xl">

        <div class="flex items-start justify-between gap-4 bg-ink-900 p-5 text-white sm:p-6">
            <div>
                <h2 id="login-guide-title" class="font-display text-2xl font-semibold">Tata cara login</h2>
                <p class="mt-1 text-sm text-ink-200">Empat langkah untuk masuk ke akunmu.</p>
            </div>

            <button
                type="button"
                onclick="closeLoginGuide()"
                class="shrink-0 rounded-lg p-2 text-ink-200 transition hover:bg-white/10 hover:text-white"
                aria-label="Tutup tata cara login"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                </svg>
            </button>
        </div>

        <div class="space-y-6 overflow-y-auto p-5 sm:p-6">

            <ol class="space-y-4">
                @foreach ([
                    ['Isi NIS/NIP/NIK', 'Siswa mengetik NIS, pegawai/staf mengetik NIP (18 digit) atau NIK (16 digit), tanpa spasi. Belum pernah aktivasi? Lakukan aktivasi akun dulu dan tunggu admin menyetujui.'],
                    ['Isi password', 'Ketik password akunmu. Tekan ikon mata di kolom password untuk memeriksa tulisannya.'],
                    ['Pilih "Ingat saya" bila perlu', 'Centang hanya di perangkat milikmu sendiri, agar tidak perlu login ulang. Jangan dicentang di komputer bersama.'],
                    ['Tekan tombol Login', 'Siswa masuk ke dashboard siswa. Bila tadi kamu mau meminjam buku, kamu langsung dibawa kembali ke buku itu.'],
                ] as $i => [$judul, $isi])
                    <li class="flex gap-4">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-ink-700 text-sm font-semibold text-white">
                            {{ $i + 1 }}
                        </span>
                        <div>
                            <p class="font-semibold text-slate-900">{{ $judul }}</p>
                            <p class="text-sm leading-relaxed text-slate-600">{{ $isi }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>

            <div class="border-t border-slate-200 pt-5">
                <h3 class="font-semibold text-slate-900">Kalau gagal masuk</h3>

                <ul class="mt-2 list-disc space-y-1.5 pl-5 text-sm leading-relaxed text-slate-600">
                    <li>Muncul "NIS/NIP/NIK atau password salah": periksa huruf besar dan kecil, serta pastikan Caps Lock mati.</li>
                    <li>Belum punya akun: pilih "Aktivasi akun dengan NIS" di bawah formulir, buat password, lalu tunggu admin menyetujui.</li>
                    <li>Lupa password: belum ada fitur pemulihan otomatis, hubungi petugas atau admin perpustakaan.</li>
                </ul>
            </div>

            <p class="rounded-lg bg-ink-50 px-4 py-3 text-sm text-ink-800">
                Selesai memakai komputer bersama? Tekan Keluar di pojok kanan atas agar akunmu aman.
            </p>

        </div>

        <div class="flex justify-end border-t border-slate-200 p-4 sm:px-6">
            <button
                type="button"
                onclick="closeLoginGuide()"
                class="w-full rounded-lg bg-ink-700 px-6 py-3 font-semibold text-white transition-colors hover:bg-ink-800 sm:w-auto"
            >
                Mengerti
            </button>
        </div>

    </div>
</div>

<script>
    (function () {
        const SEEN_KEY = 'elibrary_login_guide_seen';
        let previousFocus = null;
        const modal = () => document.getElementById('login-guide-modal');

        window.openLoginGuide = function () {
            const m = modal();
            previousFocus = document.activeElement;

            m.classList.remove('hidden');
            m.classList.add('flex');
            m.setAttribute('aria-hidden', 'false');
            document.body.classList.add('overflow-hidden');

            m.querySelector('button[aria-label="Tutup tata cara login"]').focus();
        };

        window.closeLoginGuide = function () {
            const m = modal();
            if (m.classList.contains('hidden')) return;

            m.classList.add('hidden');
            m.classList.remove('flex');
            m.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('overflow-hidden');

            try { sessionStorage.setItem(SEEN_KEY, '1'); } catch (e) {}

            if (previousFocus && typeof previousFocus.focus === 'function') previousFocus.focus();
        };

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeLoginGuide();
        });

        // Tampil otomatis sekali per sesi saat halaman login dibuka
        // (tidak muncul lagi setelah salah isi email/password).
        document.addEventListener('DOMContentLoaded', function () {
            if (modal().dataset.autoOpen !== '1') return;

            let seen = false;
            try { seen = sessionStorage.getItem(SEEN_KEY) === '1'; } catch (e) {}

            if (!seen) openLoginGuide();
        });
    })();
</script>
