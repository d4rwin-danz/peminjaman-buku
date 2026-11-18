@extends('layouts.app')

@section('title', 'Scan Kode Peminjaman')

@section('content')
<div class="min-h-screen bg-slate-50 py-6 sm:py-8">
    <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6">

            <a
                href="{{ route('admin.loans.index') }}"
                class="inline-flex items-center text-sm font-semibold text-slate-500 transition hover:text-indigo-600"
            >
                ← Kembali ke Peminjaman
            </a>

            <p class="mt-4 text-sm font-semibold text-indigo-600">
                ADMIN E-LIBRARY
            </p>

            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                Scan Kode Peminjaman
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Scan QR dari invoice siswa, atau ketik kode peminjamannya.
            </p>

        </div>


        {{-- Kamera --}}
        <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

            <h2 class="font-bold text-slate-900">
                Scan dengan Kamera
            </h2>

            <div
                id="scan-reader"
                class="mt-4 hidden overflow-hidden rounded-xl bg-slate-900"
            ></div>

            <p id="scan-status" class="mt-3 hidden text-sm text-slate-500"></p>

            <div class="mt-4 flex flex-wrap gap-3">

                <button
                    type="button"
                    id="scan-start"
                    class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-indigo-700"
                >
                    Buka Kamera
                </button>

                <button
                    type="button"
                    id="scan-stop"
                    class="hidden rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                >
                    Tutup Kamera
                </button>

            </div>

            <p class="mt-3 text-xs text-slate-400">
                Kamera hanya bisa dipakai lewat HTTPS dan perlu izin dari browser.
            </p>

        </div>


        {{-- Input manual (juga untuk scanner barcode/QR USB) --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

            <h2 class="font-bold text-slate-900">
                Atau Ketik Kode
            </h2>

            <form
                action="{{ route('admin.loans.scan') }}"
                method="GET"
                class="mt-4 flex flex-col gap-3 sm:flex-row"
            >

                <input
                    type="text"
                    name="code"
                    value="{{ old('code') }}"
                    placeholder="PJM-20261102-A7K3QX"
                    autocomplete="off"
                    autofocus
                    required
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 font-mono text-sm uppercase outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                >

                <button
                    type="submit"
                    class="shrink-0 rounded-xl bg-slate-900 px-5 py-3 text-sm font-bold text-white transition hover:bg-slate-700"
                >
                    Cari
                </button>

            </form>

        </div>

    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        (function () {
            var lookupUrl = @json(route('admin.loans.scan'));
            var readerEl  = document.getElementById('scan-reader');
            var statusEl  = document.getElementById('scan-status');
            var startBtn  = document.getElementById('scan-start');
            var stopBtn   = document.getElementById('scan-stop');
            var scanner   = null;
            var handled   = false;

            function setStatus(text) {
                statusEl.textContent = text;
                statusEl.classList.toggle('hidden', !text);
            }

            function stopScanner() {
                if (!scanner) return Promise.resolve();

                var s = scanner;
                scanner = null;

                return s.stop().then(function () { s.clear(); }).catch(function () {});
            }

            function onScanned(text) {
                if (handled) return;
                handled = true;

                setStatus('Kode terbaca, membuka peminjaman...');

                stopScanner().then(function () {
                    window.location.href = lookupUrl + '?code=' + encodeURIComponent(text);
                });
            }

            startBtn.addEventListener('click', function () {
                if (typeof Html5Qrcode === 'undefined') {
                    setStatus('Pemindai kamera gagal dimuat. Gunakan input kode di bawah.');
                    return;
                }

                handled = false;
                readerEl.classList.remove('hidden');
                startBtn.classList.add('hidden');
                stopBtn.classList.remove('hidden');
                setStatus('Arahkan kamera ke QR code pada invoice siswa.');

                scanner = new Html5Qrcode('scan-reader');

                scanner.start(
                    { facingMode: 'environment' },
                    { fps: 10, qrbox: { width: 240, height: 240 } },
                    onScanned,
                    function () { /* abaikan frame tanpa QR */ }
                ).catch(function () {
                    scanner = null;
                    readerEl.classList.add('hidden');
                    stopBtn.classList.add('hidden');
                    startBtn.classList.remove('hidden');
                    setStatus('Kamera tidak bisa dibuka. Pastikan izin kamera aktif dan situs memakai HTTPS, atau ketik kodenya.');
                });
            });

            stopBtn.addEventListener('click', function () {
                stopScanner().then(function () {
                    readerEl.classList.add('hidden');
                    stopBtn.classList.add('hidden');
                    startBtn.classList.remove('hidden');
                    setStatus('');
                });
            });
        })();
    </script>
@endpush
