{{--
    Invoice / bukti peminjaman digital.
    Pemakaian: @include('components.loan-invoice', ['loan' => $loan])
    Syarat: relasi $loan->user dan $loan->details.book sudah di-load.
--}}

@php
    $invoiceStatus = [
        'pending' => [
            'label' => 'Menunggu Persetujuan',
            'class' => 'bg-amber-100 text-amber-700',
            'hint'  => 'Tunjukkan invoice ini ke petugas perpustakaan agar pengajuan segera diproses.',
        ],
        'approved' => [
            'label' => 'Disetujui',
            'class' => 'bg-blue-100 text-blue-700',
            'hint'  => 'Pengajuan disetujui. Tunjukkan invoice ini ke petugas untuk mengambil buku.',
        ],
        'borrowed' => [
            'label' => 'Sedang Dipinjam',
            'class' => 'bg-indigo-100 text-indigo-700',
            'hint'  => 'Buku sedang kamu pinjam. Kembalikan paling lambat pada batas pengembalian.',
        ],
        'return_pending' => [
            'label' => 'Menunggu Verifikasi Pengembalian',
            'class' => 'bg-orange-100 text-orange-700',
            'hint'  => 'Pengembalian sedang diverifikasi oleh petugas.',
        ],
        'returned' => [
            'label' => 'Selesai / Dikembalikan',
            'class' => 'bg-emerald-100 text-emerald-700',
            'hint'  => 'Transaksi ini sudah selesai.',
        ],
        'rejected' => [
            'label' => 'Ditolak',
            'class' => 'bg-red-100 text-red-700',
            'hint'  => 'Pengajuan ditolak. Invoice ini tidak berlaku.',
        ],
    ];

    $inv = $invoiceStatus[$loan->status] ?? [
        'label' => ucfirst(str_replace('_', ' ', $loan->status)),
        'class' => 'bg-slate-100 text-slate-700',
        'hint'  => '',
    ];

    $invoiceValid = in_array($loan->status, ['pending', 'approved', 'borrowed', 'return_pending'], true);

    // Isi QR = tautan pencarian untuk admin (berisi kode peminjaman).
    $qrText = route('admin.loans.code', $loan->loan_code);
    $qrId = 'loan-qr-' . $loan->id;
@endphp

<style>
    @media print {
        body * { visibility: hidden !important; }
        #loan-invoice, #loan-invoice * { visibility: visible !important; }
        #loan-invoice {
            position: absolute; left: 0; top: 0; width: 100%;
            box-shadow: none !important; border: 1px solid #cbd5e1 !important;
        }
        #loan-invoice .no-print { display: none !important; }
    }
</style>

<div id="loan-invoice" class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200">

    {{-- Header --}}
    <div class="flex flex-col gap-3 bg-indigo-600 px-6 py-5 text-white sm:flex-row sm:items-center sm:justify-between">

        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-indigo-200">
                E-Library
            </p>

            <h2 class="text-xl font-bold">
                Invoice Peminjaman Buku
            </h2>
        </div>

        <span class="w-fit rounded-full px-4 py-1.5 text-xs font-bold {{ $inv['class'] }}">
            {{ $inv['label'] }}
        </span>

    </div>


    <div class="grid gap-6 p-6 md:grid-cols-[1fr_auto]">

        {{-- Kiri: data peminjaman --}}
        <div class="min-w-0">

            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                Kode Peminjaman
            </p>

            <p class="mt-1 break-all font-mono text-2xl font-extrabold tracking-wider text-slate-900 sm:text-3xl">
                {{ $loan->loan_code }}
            </p>

            <dl class="mt-5 grid grid-cols-2 gap-x-6 gap-y-4 text-sm">

                <div>
                    <dt class="text-xs text-slate-400">Peminjam</dt>
                    <dd class="mt-0.5 font-semibold text-slate-800">{{ $loan->user->name ?? '-' }}</dd>
                </div>

                <div>
                    <dt class="text-xs text-slate-400">NIS / Email</dt>
                    <dd class="mt-0.5 break-all font-semibold text-slate-800">{{ $loan->user->identifier }}</dd>
                </div>

                <div>
                    <dt class="text-xs text-slate-400">Tanggal Pinjam</dt>
                    <dd class="mt-0.5 font-semibold text-slate-800">{{ $loan->loan_date?->format('d F Y') ?? '-' }}</dd>
                </div>

                <div>
                    <dt class="text-xs text-slate-400">Batas Pengembalian</dt>
                    <dd class="mt-0.5 font-semibold text-slate-800">{{ $loan->due_date?->format('d F Y') ?? '-' }}</dd>
                </div>

            </dl>

        </div>


        {{-- Kanan: QR --}}
        <div class="flex flex-col items-center justify-start md:items-end">

            <div class="rounded-2xl border border-slate-200 bg-white p-3 {{ $invoiceValid ? '' : 'opacity-40' }}">
                <div id="{{ $qrId }}" class="flex h-[176px] w-[176px] items-center justify-center"></div>
            </div>

            <p class="mt-2 text-center text-xs text-slate-400">
                Scan QR ini di perpustakaan
            </p>

        </div>

    </div>


    {{-- Daftar buku --}}
    <div class="border-t border-dashed border-slate-300 px-6 py-5">

        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
            Buku yang Dipinjam
        </p>

        <div class="mt-3 divide-y divide-slate-100">

            @foreach ($loan->details as $detail)

                <div class="flex items-start justify-between gap-4 py-3">

                    <div class="min-w-0">
                        <p class="font-semibold text-slate-900">
                            {{ $detail->book->title ?? '-' }}
                        </p>

                        <p class="mt-0.5 text-xs text-slate-500">
                            Kode buku: {{ $detail->book->code ?? '-' }}
                            @if (!empty($detail->book?->rack_location))
                                &middot; Rak: {{ $detail->book->rack_location }}
                            @endif
                        </p>
                    </div>

                    <span class="shrink-0 rounded-lg bg-indigo-50 px-3 py-1 text-xs font-bold text-indigo-700">
                        × {{ $detail->quantity }}
                    </span>

                </div>

            @endforeach

        </div>

    </div>


    {{-- Keterangan + tombol --}}
    <div class="flex flex-col gap-3 border-t border-slate-100 bg-slate-50 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">

        <p class="text-sm text-slate-600">
            {{ $inv['hint'] }}
        </p>

        <button
            type="button"
            onclick="window.print()"
            class="no-print inline-flex shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-100"
        >
            Cetak / Simpan PDF
        </button>

    </div>

</div>


@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        (function () {
            var el = document.getElementById(@json($qrId));
            if (!el) return;

            if (typeof QRCode === 'undefined') {
                el.innerHTML = '<p class="text-center text-xs text-slate-500">QR gagal dimuat.<br>Tunjukkan kode peminjaman.</p>';
                return;
            }

            new QRCode(el, {
                text: @json($qrText),
                width: 176,
                height: 176,
                colorDark: '#0f172a',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.M
            });
        })();
    </script>
@endpush
