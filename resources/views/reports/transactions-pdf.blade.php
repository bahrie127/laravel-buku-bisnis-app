<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi - Finansial Kopi Flutter</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            color: #2563eb;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        .summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
        }

        .summary-item {
            text-align: center;
        }

        .summary-item h3 {
            margin: 0;
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
        }

        .summary-item p {
            margin: 5px 0 0 0;
            font-size: 18px;
            font-weight: bold;
        }

        .income {
            color: #059669;
        }

        .expense {
            color: #dc2626;
        }

        .total {
            color: #2563eb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background-color: #f9fafb;
            font-weight: bold;
            color: #374151;
            text-transform: uppercase;
            font-size: 12px;
        }

        tr:hover {
            background-color: #f9fafb;
        }

        .amount {
            text-align: right;
            font-weight: bold;
        }

        .amount.positive {
            color: #059669;
        }

        .amount.negative {
            color: #dc2626;
        }

        .date {
            font-size: 14px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Laporan Transaksi</h1>
        <p>Dibuat pada {{ now()->format('d F Y \p\u\k\u\l H:i:s') }}</p>
        <p>Periode: {{ $startDate }} - {{ $endDate }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <h3>Total Pemasukan</h3>
            <p class="income">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
        </div>
        <div class="summary-item">
            <h3>Total Pengeluaran</h3>
            <p class="expense">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</p>
        </div>
        <div class="summary-item">
            <h3>Saldo Bersih</h3>
            <p class="total">Rp {{ number_format($netBalance, 0, ',', '.') }}</p>
        </div>
        <div class="summary-item">
            <h3>Total Transaksi</h3>
            <p class="total">{{ $transactions->count() }}</p>
        </div>
    </div>

    @if ($transactions->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Deskripsi</th>
                    <th>Akun</th>
                    <th>Kategori</th>
                    <th>Tipe</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td class="date">{{ $transaction->date->format('d M Y') }}</td>
                        <td>{{ $transaction->note ?? '-' }}</td>
                        <td>{{ $transaction->account->name }}</td>
                        <td>{{ $transaction->category->name ?? 'Tidak Dikategorikan' }}</td>
                        <td>
                            <span class="{{ $transaction->type === 'income' ? 'income' : 'expense' }}">
                                {{ $transaction->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                            </span>
                        </td>
                        <td class="amount {{ $transaction->amount >= 0 ? 'positive' : 'negative' }}">
                            Rp {{ number_format(abs($transaction->amount), 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <p>Tidak ada transaksi ditemukan untuk periode yang dipilih.</p>
        </div>
    @endif

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh {{ config('app.name') }}</p>
    </div>
</body>

</html>
