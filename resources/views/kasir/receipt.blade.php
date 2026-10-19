<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi - {{ $transaction->invoice_code }}</title>
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: #f1f5f9;
            color: #000;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        .receipt-card {
            width: 320px;
            background: #fff;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        .dashed-line {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        .double-line {
            border-top: 2px dashed #000;
            margin: 8px 0;
        }

        .receipt-header {
            margin-bottom: 8px;
        }

        .receipt-header h2 {
            font-size: 1.25rem;
            margin-bottom: 3px;
            letter-spacing: -0.5px;
        }

        .receipt-header p {
            font-size: 0.72rem;
            line-height: 1.2;
            color: #333;
        }

        .receipt-meta {
            font-size: 0.72rem;
            line-height: 1.4;
        }

        .meta-row {
            display: flex;
            justify-content: space-between;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.75rem;
            margin-bottom: 3px;
        }

        .item-detail {
            font-size: 0.7rem;
            color: #444;
            padding-left: 8px;
            margin-bottom: 4px;
        }

        .calc-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.75rem;
            margin-bottom: 2px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.95rem;
            font-weight: bold;
            margin: 4px 0;
        }

        .receipt-footer {
            margin-top: 12px;
            font-size: 0.7rem;
            line-height: 1.3;
        }

        .action-bar {
            width: 320px;
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .btn-action {
            font-family: sans-serif;
            font-size: 0.8rem;
            padding: 6px 12px;
            border-radius: 4px;
            border: 1px solid #ccc;
            background: #fff;
            cursor: pointer;
            text-decoration: none;
            color: #333;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-print {
            background: #2563eb;
            color: #fff;
            border-color: #2563eb;
        }

        @media print {
            body {
                background: none;
                padding: 0;
            }
            .action-bar {
                display: none !important;
            }
            .receipt-card {
                box-shadow: none;
                padding: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Top Action Bar -->
    <div class="action-bar">
        <a href="javascript:window.close()" class="btn-action">
            <i class="bi bi-x"></i> Tutup
        </a>
        <button onclick="window.print()" class="btn-action btn-print">
            <i class="bi bi-printer"></i> Cetak Struk
        </button>
    </div>

    <!-- Thermal Paper Receipt -->
    <div class="receipt-card">
        <!-- Store Information -->
        <div class="receipt-header text-center">
            <h2>KASIRKU POS</h2>
            <p>Telkom University Jakarta</p>
            <p>Jl. Pemrograman Web No. 1, Jakarta</p>
            <p>Telp: 0812-3456-7890</p>
        </div>

        <div class="dashed-line"></div>

        <!-- Meta Information -->
        <div class="receipt-meta">
            <div class="meta-row">
                <span>No. Invoice:</span>
                <span class="fw-bold">{{ $transaction->invoice_code }}</span>
            </div>
            <div class="meta-row">
                <span>Waktu:</span>
                <span>{{ $transaction->created_at->format('d/m/Y H:i:s') }}</span>
            </div>
            <div class="meta-row">
                <span>Kasir:</span>
                <span>{{ $transaction->user->name ?? 'Kasir' }}</span>
            </div>
            <div class="meta-row">
                <span>Pelanggan:</span>
                <span>{{ $transaction->customer_name }}</span>
            </div>
        </div>

        <div class="dashed-line"></div>

        <!-- Items Table -->
        <div class="receipt-items">
            @foreach($transaction->details as $item)
                <div class="item-row">
                    <span class="fw-bold">{{ $item->product_name }}</span>
                    <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="item-detail">
                    {{ $item->qty }} x Rp {{ number_format($item->selling_price, 0, ',', '.') }}
                </div>
            @endforeach
        </div>

        <div class="dashed-line"></div>

        <!-- Financial Summary -->
        <div class="receipt-calc">
            <div class="calc-row">
                <span>Subtotal:</span>
                <span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($transaction->discount > 0)
                <div class="calc-row">
                    <span>Diskon:</span>
                    <span>-Rp {{ number_format($transaction->discount, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="double-line"></div>
            <div class="total-row">
                <span>TOTAL AKHIR:</span>
                <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
            </div>
            <div class="dashed-line"></div>
            <div class="calc-row">
                <span>Metode Bayar:</span>
                <span style="text-transform: uppercase;">{{ $transaction->payment_method }}</span>
            </div>
            <div class="calc-row">
                <span>Bayar (Tunai):</span>
                <span>Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</span>
            </div>
            <div class="calc-row">
                <span>Kembalian:</span>
                <span>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="dashed-line"></div>

        <!-- Footer -->
        <div class="receipt-footer text-center">
            <p>Terima Kasih Atas Kunjungan Anda!</p>
            <p>Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan tanpa struk ini.</p>
            <br>
            <p>*** Layanan Pelanggan: kasirku.id ***</p>
        </div>
    </div>
</body>
</html>
