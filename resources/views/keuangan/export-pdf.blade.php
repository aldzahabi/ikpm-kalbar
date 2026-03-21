<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan - {{ $accountName }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #15803d;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 18pt;
            color: #15803d;
            margin-bottom: 5px;
        }
        .header h2 {
            font-size: 14pt;
            color: #666;
            font-weight: normal;
        }
        .info-section {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f0fdf4;
            border-left: 4px solid #15803d;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            color: #15803d;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 9pt;
        }
        thead {
            background-color: #15803d;
            color: white;
        }
        th {
            padding: 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #15803d;
        }
        td {
            padding: 6px;
            border: 1px solid #ddd;
        }
        tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .badge-income {
            background-color: #d1fae5;
            color: #065f46;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8pt;
        }
        .badge-expense {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8pt;
        }
        .summary {
            margin-top: 20px;
            padding: 15px;
            background-color: #f0fdf4;
            border: 2px solid #15803d;
            border-radius: 5px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 11pt;
        }
        .summary-label {
            font-weight: bold;
        }
        .summary-total {
            font-size: 14pt;
            font-weight: bold;
            color: #15803d;
            border-top: 2px solid #15803d;
            padding-top: 8px;
            margin-top: 8px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 8pt;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>IKPM GONTOR PONTIANAK</h1>
        <h2>Laporan Keuangan</h2>
        <p style="font-size: 11pt; margin-top: 5px;">{{ $accountName }}</p>
    </div>

    <!-- Info Section -->
    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Periode:</span>
            <span>
                @if(request('date_from') && request('date_to'))
                    {{ \Carbon\Carbon::parse(request('date_from'))->format('d/m/Y') }} - {{ \Carbon\Carbon::parse(request('date_to'))->format('d/m/Y') }}
                @else
                    Semua Periode
                @endif
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal Cetak:</span>
            <span>{{ now()->format('d/m/Y H:i:s') }}</span>
        </div>
        @if($account)
        <div class="info-row">
            <span class="info-label">Saldo Akun:</span>
            <span style="font-weight: bold; color: {{ $account->current_balance >= 0 ? '#15803d' : '#dc2626' }};">
                Rp {{ number_format($account->current_balance, 0, ',', '.') }}
            </span>
        </div>
        @endif
    </div>

    <!-- Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 8%;">Tanggal</th>
                <th style="width: 15%;">Akun</th>
                <th style="width: 15%;">Kategori</th>
                <th style="width: 10%;">Tipe</th>
                <th style="width: 25%;">Keterangan</th>
                <th style="width: 12%;" class="text-right">Nominal</th>
                <th style="width: 15%;">Input By</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                    <td>{{ $transaction->account->name }}</td>
                    <td>{{ $transaction->category->name }}</td>
                    <td class="text-center">
                        <span class="{{ $transaction->category->type === 'income' ? 'badge-income' : 'badge-expense' }}">
                            {{ $transaction->category->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                        </span>
                    </td>
                    <td>{{ Str::limit($transaction->description ?? '-', 40) }}</td>
                    <td class="text-right" style="color: {{ $transaction->category->type === 'income' ? '#15803d' : '#dc2626' }}; font-weight: bold;">
                        {{ $transaction->category->type === 'income' ? '+' : '-' }}Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                    </td>
                    <td>{{ $transaction->user->name }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px; color: #999;">
                        Tidak ada transaksi ditemukan
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Summary -->
    <div class="summary">
        <div class="summary-row">
            <span class="summary-label">Total Pemasukan:</span>
            <span style="color: #15803d; font-weight: bold;">Rp {{ number_format($totalIncome, 0, ',', '.') }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Total Pengeluaran:</span>
            <span style="color: #dc2626; font-weight: bold;">Rp {{ number_format($totalExpense, 0, ',', '.') }}</span>
        </div>
        <div class="summary-row summary-total">
            <span>Saldo Bersih:</span>
            <span style="color: {{ $netBalance >= 0 ? '#15803d' : '#dc2626' }};">
                Rp {{ number_format($netBalance, 0, ',', '.') }}
            </span>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Dicetak oleh: {{ $printedBy }} | {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>IKPM Gontor Pontianak - Sistem Manajemen Keuangan</p>
    </div>
</body>
</html>
