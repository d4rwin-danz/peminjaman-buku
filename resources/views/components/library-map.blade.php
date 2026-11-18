@php
    $rackLocations = [
        'utara_1' => 'Rak Utara 1',
        'utara_2' => 'Rak Utara 2',
        'utara_3' => 'Rak Utara 3',
        'utara_4' => 'Rak Utara 4',
        'utara_5' => 'Rak Utara 5',
        'utara_6' => 'Rak Utara 6',
        'barat_1' => 'Rak Barat 1',
        'barat_2' => 'Rak Barat 2',
        'barat_3' => 'Rak Barat 3',
        'barat_4' => 'Rak Barat 4',
        'barat_5' => 'Rak Barat 5',
        'barat_6' => 'Rak Barat 6',
        'selatan_1' => 'Rak Selatan 1',
        'selatan_2' => 'Rak Selatan 2',
    ];

    $currentRack = $book->rack_location;
    $currentRackName = $rackLocations[$currentRack] ?? null;

    $topRacks = [
        ['id' => 'utara_6', 'x' => 28, 'w' => 135],
        ['id' => 'utara_5', 'x' => 164, 'w' => 121],
        ['id' => 'utara_4', 'x' => 286, 'w' => 121],
        ['id' => 'utara_3', 'x' => 408, 'w' => 122],
        ['id' => 'utara_2', 'x' => 532, 'w' => 117],
        ['id' => 'utara_1', 'x' => 651, 'w' => 121],
    ];

    $westRacks = [
        ['id' => 'barat_1', 'y' => 110],
        ['id' => 'barat_2', 'y' => 202],
        ['id' => 'barat_3', 'y' => 299],
        ['id' => 'barat_4', 'y' => 377],
        ['id' => 'barat_5', 'y' => 470],
        ['id' => 'barat_6', 'y' => 561],
    ];

    $southRacks = [
        ['id' => 'selatan_1', 'x' => 352],
        ['id' => 'selatan_2', 'x' => 694],
    ];
@endphp

<section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

    <div class="flex flex-col gap-3 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
        <div>
            <h2 class="text-xl font-bold text-slate-900 sm:text-2xl">
                Peta Lokasi Buku
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Temukan posisi rak buku di perpustakaan.
            </p>
        </div>

        <div class="rounded-xl bg-indigo-50 px-4 py-3">
            <p class="text-xs font-medium text-indigo-500">
                LOKASI BUKU
            </p>
            <p class="mt-1 font-bold text-indigo-800">
                {{ $currentRackName ?? 'Belum ditentukan' }}
            </p>
        </div>
    </div>

    <div class="p-3 sm:p-5">

        <div class="mb-4 flex flex-wrap gap-x-5 gap-y-2 text-xs text-slate-600 sm:text-sm">
            <span class="flex items-center gap-2">
                <span class="h-3.5 w-3.5 rounded-sm bg-[#176482]"></span>
                Rak buku
            </span>

            <span class="flex items-center gap-2">
                <span class="h-3.5 w-3.5 rounded-sm bg-blue-600"></span>
                Rak buku yang dipilih
            </span>

            <span class="flex items-center gap-2">
                <span class="h-3.5 w-3.5 rounded-sm bg-green-800"></span>
                Area baca
            </span>

            <span class="flex items-center gap-2">
                <span class="h-3.5 w-3.5 rounded-sm bg-yellow-300"></span>
                Meja petugas
            </span>
        </div>

        <div class="overflow-x-auto rounded-xl border border-slate-200 bg-slate-50">
            <svg
                viewBox="0 0 1200 710"
                role="img"
                aria-label="Denah perpustakaan dengan rak Utara, Barat, dan Selatan"
                class="block h-auto w-full min-w-[650px]"
                xmlns="http://www.w3.org/2000/svg"
            >
                <rect
                    x="1"
                    y="1"
                    width="1198"
                    height="708"
                    rx="8"
                    fill="#ffffff"
                    stroke="#cbd5e1"
                    stroke-width="2"
                />

                <text
                    x="600"
                    y="17"
                    fill="#64748b"
                    font-size="12"
                    text-anchor="middle"
                >UTARA ↑</text>

                {{-- RAK UTARA: NOMOR DIMULAI DARI KANAN --}}
                @foreach ($topRacks as $rack)
                    <g>
                        <rect
                            x="{{ $rack['x'] }}"
                            y="25"
                            width="{{ $rack['w'] }}"
                            height="46"
                            rx="1"
                            fill="{{ $currentRack === $rack['id'] ? '#2563eb' : '#176482' }}"
                            stroke="{{ $currentRack === $rack['id'] ? '#1d4ed8' : '#0f3f54' }}"
                            stroke-width="{{ $currentRack === $rack['id'] ? 4 : 1 }}"
                        />

                        <text
                            x="{{ $rack['x'] + $rack['w'] / 2 }}"
                            y="52"
                            fill="#ffffff"
                            font-size="13"
                            font-weight="600"
                            text-anchor="middle"
                        >{{ str_replace('Rak ', '', $rackLocations[$rack['id']]) }}</text>
                    </g>
                @endforeach

                {{-- LEMARI MERAH --}}
                <rect
                    x="773"
                    y="25"
                    width="144"
                    height="46"
                    fill="#dc2626"
                    stroke="#991b1b"
                />

                <rect
                    x="918"
                    y="25"
                    width="145"
                    height="46"
                    fill="#dc2626"
                    stroke="#991b1b"
                />

                <text
                    x="845"
                    y="52"
                    fill="#ffffff"
                    font-size="13"
                    font-weight="600"
                    text-anchor="middle"
                >Lemari 1</text>

                <text
                    x="990"
                    y="52"
                    fill="#ffffff"
                    font-size="13"
                    font-weight="600"
                    text-anchor="middle"
                >Lemari 2</text>

                {{-- RAK BARAT --}}
                @foreach ($westRacks as $rack)
                    <g>
                        <rect
                            x="28"
                            y="{{ $rack['y'] }}"
                            width="299"
                            height="62"
                            rx="1"
                            fill="{{ $currentRack === $rack['id'] ? '#2563eb' : '#176482' }}"
                            stroke="{{ $currentRack === $rack['id'] ? '#1d4ed8' : '#0f3f54' }}"
                            stroke-width="{{ $currentRack === $rack['id'] ? 4 : 1 }}"
                        />

                        <text
                            x="177"
                            y="{{ $rack['y'] + 36 }}"
                            fill="#ffffff"
                            font-size="17"
                            font-weight="600"
                            text-anchor="middle"
                        >{{ $rackLocations[$rack['id']] }}</text>
                    </g>
                @endforeach

                {{-- AREA BACA BERBENTUK U --}}
                <path
                    d="M413 226 H893 V265 H448 V500 H893 V538 H413 Z"
                    fill="#176b2a"
                />

                {{-- AREA LESEHAN --}}
                <rect
                    x="475"
                    y="278"
                    width="407"
                    height="208"
                    fill="#176b2a"
                />

                <text
                    x="678"
                    y="383"
                    fill="#ffffff"
                    font-size="23"
                    font-weight="700"
                    text-anchor="middle"
                >AREA BACA</text>

                <text
                    x="678"
                    y="411"
                    fill="#d1fae5"
                    font-size="15"
                    text-anchor="middle"
                >Lesehan</text>

                {{-- MEJA PETUGAS SISI TIMUR --}}
                <path
                    d="M1023 252 H1178 V271 H1034 V486 H1178 V505 H1047 V665 H1028 V505 H1023 Z"
                    fill="#fff500"
                    stroke="#b8a900"
                    stroke-width="1"
                />

                <text
                    x="1098"
                    y="370"
                    fill="#713f12"
                    font-size="14"
                    font-weight="600"
                    text-anchor="middle"
                    transform="rotate(90 1098 370)"
                >MEJA PETUGAS</text>

                {{-- PINTU --}}
                <rect
                    x="1159"
                    y="71"
                    width="19"
                    height="146"
                    fill="#cf75d4"
                    stroke="#9333a6"
                />

                <text
                    x="1170"
                    y="145"
                    fill="#581c87"
                    font-size="12"
                    font-weight="700"
                    text-anchor="middle"
                    transform="rotate(90 1170 145)"
                >PINTU</text>

                {{-- RAK SELATAN --}}
                @foreach ($southRacks as $rack)
                    <g>
                        <rect
                            x="{{ $rack['x'] }}"
                            y="659"
                            width="299"
                            height="48"
                            rx="1"
                            fill="{{ $currentRack === $rack['id'] ? '#2563eb' : '#176482' }}"
                            stroke="{{ $currentRack === $rack['id'] ? '#1d4ed8' : '#0f3f54' }}"
                            stroke-width="{{ $currentRack === $rack['id'] ? 4 : 1 }}"
                        />

                        <text
                            x="{{ $rack['x'] + 149 }}"
                            y="688"
                            fill="#ffffff"
                            font-size="15"
                            font-weight="600"
                            text-anchor="middle"
                        >{{ $rackLocations[$rack['id']] }}</text>
                    </g>
                @endforeach

                <text
                    x="600"
                    y="700"
                    fill="#64748b"
                    font-size="12"
                    text-anchor="middle"
                >SELATAN ↓</text>
            </svg>
        </div>

        @if ($currentRackName)
            <div class="mt-4 flex items-start gap-3 rounded-xl border border-blue-200 bg-blue-50 p-4">
                

                <div>
                    <p class="font-bold text-blue-900">
                        Buku ini berada di {{ $currentRackName }}
                    </p>

                    <p class="mt-1 text-sm text-blue-700">
                        Rak berwarna biru terang pada denah menunjukkan lokasi buku yang sedang dilihat.
                    </p>
                </div>
            </div>
        @else
            <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 p-4">
                <p class="font-semibold text-amber-900">
                    Lokasi buku belum ditentukan.
                </p>

                <p class="mt-1 text-sm text-amber-800">
                    Silakan tanyakan lokasi buku kepada petugas perpustakaan.
                </p>
            </div>
        @endif

        <p class="mt-3 text-xs text-slate-400">
            Denah bersifat ilustratif dan mengikuti susunan pada gambar referensi.
        </p>

    </div>
</section>