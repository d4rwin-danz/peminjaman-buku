@extends('layouts.app')

@section('title', 'Peminjaman Saya')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- HEADER --}}
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>
                <p class="text-sm font-semibold text-indigo-600 mb-1">
                    E-LIBRARY
                </p>

                <h1 class="text-3xl font-bold text-gray-900">
                    Peminjaman Saya
                </h1>

                <p class="text-gray-500 mt-2">
                    Lihat seluruh riwayat dan status peminjaman buku kamu.
                </p>
            </div>

            <a href="{{ route('siswa.books.index') }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-3
                      bg-indigo-600 text-white rounded-xl font-semibold
                      hover:bg-indigo-700 transition shadow-sm">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 4v16m8-8H4"/>
                </svg>

                Pinjam Buku
            </a>

        </div>
    </div>


    {{-- STATISTIK --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        @php
            $allLoans = \App\Models\Loan::where('user_id', auth()->id())->get();

            $totalLoans = $allLoans->count();

            $activeLoans = $allLoans
                ->whereIn('status', ['approved', 'borrowed', 'return_pending'])
                ->count();

            $returnedLoans = $allLoans
                ->where('status', 'returned')
                ->count();

            $pendingLoans = $allLoans
                ->where('status', 'pending')
                ->count();
        @endphp

        {{-- TOTAL --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3">

                <div class="w-11 h-11 rounded-xl bg-indigo-50
                            flex items-center justify-center">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-6 h-6 text-indigo-600"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 6v6l4 2"/>
                    </svg>

                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Total
                    </p>

                    <p class="text-2xl font-bold text-gray-900">
                        {{ $totalLoans }}
                    </p>
                </div>

            </div>
        </div>


        {{-- PENDING --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3">

                <div class="w-11 h-11 rounded-xl bg-yellow-50
                            flex items-center justify-center">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-6 h-6 text-yellow-600"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 8v4l3 2"/>
                    </svg>

                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Menunggu
                    </p>

                    <p class="text-2xl font-bold text-gray-900">
                        {{ $pendingLoans }}
                    </p>
                </div>

            </div>
        </div>


        {{-- AKTIF --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3">

                <div class="w-11 h-11 rounded-xl bg-blue-50
                            flex items-center justify-center">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-6 h-6 text-blue-600"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 6v12m6-6H6"/>
                    </svg>

                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Aktif
                    </p>

                    <p class="text-2xl font-bold text-gray-900">
                        {{ $activeLoans }}
                    </p>
                </div>

            </div>
        </div>


        {{-- SELESAI --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3">

                <div class="w-11 h-11 rounded-xl bg-green-50
                            flex items-center justify-center">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-6 h-6 text-green-600"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M5 13l4 4L19 7"/>
                    </svg>

                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Selesai
                    </p>

                    <p class="text-2xl font-bold text-gray-900">
                        {{ $returnedLoans }}
                    </p>
                </div>

            </div>
        </div>

    </div>

    {{-- FILTER & PENCARIAN --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">

    <form method="GET"
          action="{{ route('siswa.loans.index') }}"
          class="grid grid-cols-1 md:grid-cols-12 gap-3">

        {{-- SEARCH --}}
        <div class="md:col-span-6">

            <label for="search"
                   class="block text-sm font-semibold text-gray-700 mb-2">
                Cari Peminjaman
            </label>

            <div class="relative">

                <div class="absolute inset-y-0 left-0 pl-3
                            flex items-center pointer-events-none">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-5 h-5 text-gray-400"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1-16 0 8 8 0 0 1 16 0z"/>

                    </svg>

                </div>

                <input
                    type="text"
                    name="search"
                    id="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Cari judul atau kode buku..."
                    class="w-full pl-10 pr-4 py-3 rounded-xl
                           border border-gray-200
                           focus:ring-2 focus:ring-indigo-500
                           focus:border-indigo-500 outline-none">
            </div>

        </div>


        {{-- STATUS --}}
        <div class="md:col-span-4">

            <label for="status"
                   class="block text-sm font-semibold text-gray-700 mb-2">
                Status
            </label>

            <select
                name="status"
                id="status"
                class="w-full px-4 py-3 rounded-xl
                       border border-gray-200
                       focus:ring-2 focus:ring-indigo-500
                       focus:border-indigo-500 outline-none">

                <option value="">
                    Semua Status
                </option>

                <option value="pending"
                    {{ ($status ?? '') === 'pending' ? 'selected' : '' }}>
                    Menunggu Persetujuan
                </option>

                <option value="approved"
                    {{ ($status ?? '') === 'approved' ? 'selected' : '' }}>
                    Disetujui
                </option>

                <option value="borrowed"
                    {{ ($status ?? '') === 'borrowed' ? 'selected' : '' }}>
                    Sedang Dipinjam
                </option>

                <option value="return_pending"
                    {{ ($status ?? '') === 'return_pending' ? 'selected' : '' }}>
                    Menunggu Pengembalian
                </option>

                <option value="returned"
                    {{ ($status ?? '') === 'returned' ? 'selected' : '' }}>
                    Dikembalikan
                </option>

                <option value="rejected"
                    {{ ($status ?? '') === 'rejected' ? 'selected' : '' }}>
                    Ditolak
                </option>

            </select>

        </div>


        {{-- BUTTON --}}
        <div class="md:col-span-2 flex items-end gap-2">

            <button
                type="submit"
                class="flex-1 px-4 py-3 rounded-xl
                       bg-indigo-600 text-white
                       font-semibold
                       hover:bg-indigo-700 transition">

                Cari
            </button>

            <a href="{{ route('siswa.loans.index') }}"
               class="px-4 py-3 rounded-xl
                      border border-gray-200
                      text-gray-600
                      font-semibold
                      hover:bg-gray-50 transition">

                Reset
            </a>

        </div>

    </form>

</div>

    {{-- DAFTAR PEMINJAMAN --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        <div class="px-6 py-5 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-900">
                Riwayat Peminjaman
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Semua transaksi peminjaman buku kamu.
            </p>
        </div>


        @forelse($loans as $loan)

            @php
                $statusConfig = [
                    'pending' => [
                        'label' => 'Menunggu Persetujuan',
                        'class' => 'bg-yellow-100 text-yellow-700',
                    ],

                    'approved' => [
                        'label' => 'Disetujui',
                        'class' => 'bg-blue-100 text-blue-700',
                    ],

                    'borrowed' => [
                        'label' => 'Sedang Dipinjam',
                        'class' => 'bg-indigo-100 text-indigo-700',
                    ],

                    'return_pending' => [
                        'label' => 'Menunggu Pengembalian',
                        'class' => 'bg-orange-100 text-orange-700',
                    ],

                    'returned' => [
                        'label' => 'Dikembalikan',
                        'class' => 'bg-green-100 text-green-700',
                    ],

                    'rejected' => [
                        'label' => 'Ditolak',
                        'class' => 'bg-red-100 text-red-700',
                    ],
                ];

                $status = $statusConfig[$loan->status] ?? [
                    'label' => ucfirst($loan->status),
                    'class' => 'bg-gray-100 text-gray-700',
                ];
            @endphp


            <div class="p-6 border-b border-gray-100 last:border-b-0">

                <div class="flex flex-col lg:flex-row lg:items-center
                            lg:justify-between gap-5">

                    {{-- INFO --}}
                    <div class="flex items-start gap-4 min-w-0">

                        {{-- ICON --}}
                        <div class="w-12 h-12 shrink-0 rounded-xl
                                    bg-indigo-50 flex items-center justify-center">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-6 h-6 text-indigo-600"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>

                        </div>


                        <div class="min-w-0">

                            <div class="flex flex-wrap items-center gap-2">

                                <h3 class="font-bold text-gray-900">
                                    Peminjaman #{{ $loan->id }}
                                </h3>

                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $status['class'] }}">
                                    {{ $status['label'] }}
                                </span>

                            </div>


                            {{-- BUKU --}}
                            <div class="mt-2 space-y-1">

                                @foreach($loan->details as $detail)

                                    <p class="text-sm text-gray-700">
                                        <span class="font-semibold">
                                            {{ $detail->book->title }}
                                        </span>

                                        <span class="text-gray-500">
                                            × {{ $detail->quantity }}
                                        </span>
                                    </p>

                                @endforeach

                            </div>


                            {{-- TANGGAL --}}
                            <div class="flex flex-wrap gap-x-5 gap-y-1 mt-3
                                        text-xs text-gray-500">

                                <span>
                                    📅 Pinjam:
                                    {{ $loan->loan_date?->format('d M Y') }}
                                </span>

                                <span>
                                    ⏰ Jatuh tempo:
                                    {{ $loan->due_date?->format('d M Y') }}
                                </span>

                                @if($loan->return_date)

                                    <span>
                                        ✓ Kembali:
                                        {{ $loan->return_date->format('d M Y') }}
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- ACTION --}}
                    <div class="flex flex-col sm:flex-row lg:flex-col
                                gap-2 lg:min-w-[150px]">

                        <a href="{{ route('siswa.loans.show', $loan) }}"
                           class="inline-flex items-center justify-center gap-2
                                  px-4 py-2.5 rounded-xl
                                  border border-gray-200
                                  text-gray-700 font-semibold text-sm
                                  hover:bg-gray-50 transition">

                            Lihat Detail
                        </a>


                        @if($loan->status === 'borrowed')

                            <a href="{{ route('siswa.loans.return.create', $loan) }}"
                               class="inline-flex items-center justify-center gap-2
                                      px-4 py-2.5 rounded-xl
                                      bg-orange-500 text-white
                                      font-semibold text-sm
                                      hover:bg-orange-600 transition">

                                Ajukan Pengembalian
                            </a>

                        @elseif($loan->status === 'return_pending')

                            <span class="inline-flex items-center justify-center
                                         px-4 py-2.5 rounded-xl
                                         bg-orange-50 text-orange-700
                                         font-semibold text-sm">

                                Menunggu Verifikasi
                            </span>

                        @elseif($loan->status === 'returned')

                            <span class="inline-flex items-center justify-center
                                         px-4 py-2.5 rounded-xl
                                         bg-green-50 text-green-700
                                         font-semibold text-sm">

                                ✓ Selesai
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        @empty

            <div class="px-6 py-16 text-center">

                <div class="w-16 h-16 mx-auto rounded-2xl
                            bg-gray-100 flex items-center justify-center">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-8 h-8 text-gray-400"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332 0-4.5 1.253"/>
                    </svg>

                </div>

                <h3 class="mt-4 text-lg font-bold text-gray-900">
                    Belum ada peminjaman
                </h3>

                <p class="mt-2 text-sm text-gray-500">
                    Kamu belum memiliki riwayat peminjaman buku.
                </p>

                <a href="{{ route('siswa.books.index') }}"
                   class="inline-flex mt-5 px-5 py-3
                          bg-indigo-600 text-white rounded-xl
                          font-semibold hover:bg-indigo-700 transition">

                    Jelajahi Buku
                </a>

            </div>

        @endforelse


        {{-- PAGINATION --}}
        @if($loans->hasPages())

            <div class="px-6 py-5 border-t border-gray-100">
                {{ $loans->links() }}
            </div>

        @endif

    </div>

</div>
@endsection