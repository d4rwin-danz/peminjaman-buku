<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <title>Laporan Peminjaman Buku</title>

    <style>
        @page {
            margin: 25px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #222;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .header h2 {
            margin: 5px 0 0;
            font-size: 13px;
            font-weight: normal;
        }

        .filter {
            margin-bottom: 15px;
            padding: 8px;
            border: 1px solid #ccc;
        }

        .filter table {
            width: 100%;
            border-collapse: collapse;
        }

        .filter td {
            padding: 3px;
        }

        .summary {
            margin-bottom: 15px;
        }

        .summary table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary td {
            border: 1px solid #ccc;
            padding: 7px;
            text-align: center;
        }

        .summary strong {
            display: block;
            font-size: 14px;
            margin-bottom: 3px;
        }

        table.report {
            width: 100%;
            border-collapse: collapse;
        }

        table.report th,
        table.report td {
            border: 1px solid #999;
            padding: 6px;
        }

        table.report th {
            background-color: #eeeeee;
            text-align: center;
            font-weight: bold;
        }

        table.report td {
            vertical-align: top;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .status {
            text-align: center;
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            font-size: 9px;
            text-align: right;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>LAPORAN PEMINJAMAN BUKU</h1>
        <h2>E-Library SMK Negeri 5 Surakarta</h2>
    </div>


    {{-- FILTER --}}
    <div class="filter">
        <table>
            <tr>
                <td width="15%"><strong>Periode</strong></td>
                <td>
                    @if ($dateFrom || $dateTo)
                        {{ $dateFrom ?: 'Awal' }}
                        s/d
                        {{ $dateTo ?: 'Sekarang' }}
                    @else
                        Semua tanggal
                    @endif
                </td>
            </tr>

            <tr>
                <td><strong>Status</strong></td>
                <td>
                    @if ($status)
                        {{ ucwords(str_replace('_', ' ', $status)) }}
                    @else
                        Semua status
                    @endif
                </td>
            </tr>

            <tr>
                <td><strong>Pencarian</strong></td>
                <td>
                    {{ $search ?: 'Tidak ada' }}
                </td>
            </tr>
        </table>
    </div>


    {{-- RINGKASAN --}}
    <div class="summary">
        <table>
            <tr>
                <td>
                    <strong>{{ $totalLoans }}</strong>
                    Total Peminjaman
                </td>

                <td>
                    <strong>
                        {{ $loans->where('status', 'pending')->count() }}
                    </strong>
                    Menunggu
                </td>

                <td>
                    <strong>
                        {{ $loans->whereIn('status', ['approved', 'borrowed', 'return_pending'])->count() }}
                    </strong>
                    Aktif
                </td>

                <td>
                    <strong>
                        {{ $loans->where('status', 'returned')->count() }}
                    </strong>
                    Dikembalikan
                </td>

                <td>
                    <strong>
                        Rp {{ number_format($totalFine, 0, ',', '.') }}
                    </strong>
                    Total Denda
                </td>
            </tr>
        </table>
    </div>


    {{-- DATA PEMINJAMAN --}}
    <table class="report">

        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="15%">Siswa</th>
                <th width="28%">Buku</th>
                <th width="11%">Tanggal Pinjam</th>
                <th width="11%">Jatuh Tempo</th>
                <th width="12%">Status</th>
                <th width="10%">Denda</th>
            </tr>
        </thead>

        <tbody>

            @forelse ($loans as $loan)

                <tr>

                    <td class="center">
                        {{ $loop->iteration }}
                    </td>

                    <td>
                        <strong>{{ $loan->user->name ?? '-' }}</strong>

                        @if ($loan->user?->email)
                            <br>
                            {{ $loan->user->email }}
                        @endif
                    </td>

                    <td>
                        @forelse ($loan->details as $detail)

                            <div>
                                {{ $detail->book->title ?? '-' }}

                                @if ($detail->quantity > 1)
                                    ({{ $detail->quantity }} buku)
                                @endif
                            </div>

                        @empty
                            -
                        @endforelse
                    </td>

                    <td class="center">
                        {{ $loan->loan_date?->format('d-m-Y') ?? '-' }}
                    </td>

                    <td class="center">
                        {{ $loan->due_date?->format('d-m-Y') ?? '-' }}
                    </td>

                    <td class="status">
                        {{ ucwords(str_replace('_', ' ', $loan->status)) }}
                    </td>

                    <td class="right">
                        @if ($loan->fine > 0)
                            Rp {{ number_format($loan->fine, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="7" class="center">
                        Tidak ada data peminjaman.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>


    <div class="footer">
        Dicetak pada:
        {{ now()->format('d-m-Y H:i:s') }}
    </div>

</body>
</html>