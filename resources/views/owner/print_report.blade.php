<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan Penjualan - KasirKu</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f5f9;
            color: #0f172a;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            padding: 2rem;
        }

        .report-container {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            padding: 2.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .report-container {
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="report-container">
        <!-- Action Buttons -->
        <div class="d-flex justify-content-between align-items-center mb-4 no-print pb-3 border-bottom">
            <button onclick="window.history.back()" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali
            </button>
            <button onclick="window.print()" class="btn btn-primary btn-sm">
                <i class="bi bi-printer"></i> Cetak / Simpan PDF
            </button>
        </div>

        <!-- Header -->
        <div class="text-center mb-4 pb-3 border-bottom">
            <h3 class="fw-bold mb-1">KASIRKU POINT OF SALES</h3>
            <p class="text-muted mb-1" style="font-size: 0.9rem;">Sistem Rekapitulasi Laporan Penjualan & Keuangan Toko</p>
            <div class="small fw-semibold mt-2">
                Periode: <span class="text-primary">{{ \Carbon\Carbon::parse($dateFrom)->format('d F Y') }}</span> s/d <span class="text-primary">{{ \Carbon\Carbon::parse($dateTo)->format('d F Y') }}</span>
            </div>
            <div class="text-muted" style="font-size: 0.78rem;">Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }} WIB oleh {{ Auth::user()->name }} (Owner)</div>
        </div>

        <!-- Financial Summary Cards -->
        <div class="row g-3 mb-4">
            <div class="col-4">
                <div class="p-3 border rounded text-center">
                    <div class="text-muted small">Total Transaksi</div>
                    <h5 class="fw-bold mb-0 text-dark">{{ number_format($transactions->count()) }}</h5>
                </div>
            </div>
            <div class="col-4">
                <div class="p-3 border rounded text-center">
                    <div class="text-muted small">Total Pendapatan (Omzet)</div>
                    <h5 class="fw-bold mb-0 text-success">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h5>
                </div>
            </div>
            <div class="col-4">
                <div class="p-3 border rounded text-center">
                    <div class="text-muted small">Rata-rata per Transaksi</div>
                    <h5 class="fw-bold mb-0 text-primary">
                        Rp {{ $transactions->count() > 0 ? number_format($totalRevenue / $transactions->count(), 0, ',', '.') : 0 }}
                    </h5>
                </div>
            </div>
        </div>

        <!-- Detailed Transactions Table -->
        <table class="table table-sm table-bordered align-middle mb-4" style="font-size: 0.82rem;">
            <thead class="table-light text-center">
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>No Invoice</th>
                    <th>Waktu</th>
                    <th>Kasir</th>
                    <th>Metode</th>
                    <th>Pelanggan</th>
                    <th class="text-end">Total (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $idx => $t)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td class="font-monospace fw-semibold">{{ $t->invoice_code }}</td>
                        <td class="text-center">{{ $t->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $t->user->name ?? '-' }}</td>
                        <td class="text-center text-uppercase">{{ $t->payment_method }}</td>
                        <td>{{ $t->customer_name }}</td>
                        <td class="text-end fw-semibold">{{ number_format($t->total_amount, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Tidak ada data transaksi pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot class="table-light fw-bold">
                <tr>
                    <td colspan="6" class="text-end">TOTAL PENJUALAN:</td>
                    <td class="text-end text-success">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- Signatures -->
        <div class="row mt-5 pt-4">
            <div class="col-6 text-center">
                <div class="small text-muted mb-5">Mengetahui, Admin Toko</div>
                <div class="fw-bold text-decoration-underline">( ........................................ )</div>
            </div>
            <div class="col-6 text-center">
                <div class="small text-muted mb-5">Disahkan oleh, Pemilik Toko (Owner)</div>
                <div class="fw-bold text-decoration-underline">{{ Auth::user()->name }}</div>
            </div>
        </div>
    </div>
</body>
</html>
