<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Penerimaan Barang - {{ $purchase->purchase_code }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f5f9;
            color: #0f172a;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            padding: 2rem;
        }

        .note-container {
            max-width: 850px;
            margin: 0 auto;
            background: #fff;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .note-container {
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
    <div class="note-container">
        <!-- Action Bar -->
        <div class="d-flex justify-content-between align-items-center mb-4 no-print pb-3 border-bottom">
            <button onclick="window.history.back()" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali
            </button>
            <button onclick="window.print()" class="btn btn-primary btn-sm">
                <i class="bi bi-printer"></i> Cetak / Simpan PDF
            </button>
        </div>

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-4">
            <div>
                <h3 class="fw-bold mb-0">KasirKu</h3>
                <p class="text-muted small mb-0">Surat Bukti Penerimaan Barang / Pasokan Masuk (Goods Receipt Note)</p>
            </div>
            <div class="text-end">
                <h5 class="fw-bold text-primary mb-1">{{ $purchase->purchase_code }}</h5>
                <div class="text-muted small">{{ $purchase->created_at->format('d F Y, H:i') }} WIB</div>
            </div>
        </div>

        <!-- Vendor & Warehouse Info -->
        <div class="row g-3 mb-4">
            <div class="col-6">
                <div class="p-3 border rounded">
                    <div class="text-muted small">Supplier / Vendor:</div>
                    <h6 class="fw-bold mb-1">{{ $purchase->supplier_name }}</h6>
                    <div class="small">No. Ref Surat Jalan: <strong>{{ $purchase->invoice_ref ?: '-' }}</strong></div>
                </div>
            </div>
            <div class="col-6">
                <div class="p-3 border rounded">
                    <div class="text-muted small">Diterima Di Gudang Oleh:</div>
                    <h6 class="fw-bold mb-1">{{ $purchase->user->name ?? 'Admin Gudang' }}</h6>
                    <div class="small">Status Fisik: <span class="badge bg-success">Telah Diterima & Masuk Stok</span></div>
                </div>
            </div>
        </div>

        <!-- Table of Items -->
        <table class="table table-bordered table-sm align-middle mb-4" style="font-size: 0.88rem;">
            <thead class="table-light text-center">
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>Kode Barang</th>
                    <th>Nama Menu / Produk</th>
                    <th class="text-center">Kuantitas Masuk</th>
                    <th class="text-end">Harga Beli Satuan</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchase->details as $idx => $item)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td class="font-monospace fw-semibold">{{ $item->product_code }}</td>
                        <td class="fw-bold">{{ $item->product_name }}</td>
                        <td class="text-center fw-bold text-success">+{{ $item->qty }}</td>
                        <td class="text-end">Rp {{ number_format($item->cost_price, 0, ',', '.') }}</td>
                        <td class="text-end fw-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="table-light fw-bold">
                <tr>
                    <td colspan="5" class="text-end">TOTAL BIAYA PEMBELIAN:</td>
                    <td class="text-end text-primary">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        @if($purchase->notes)
            <div class="p-2 border rounded mb-4 bg-light small">
                <strong>Catatan Penerimaan:</strong> {{ $purchase->notes }}
            </div>
        @endif

        <!-- Signatures -->
        <div class="row mt-5 pt-4">
            <div class="col-6 text-center">
                <div class="small text-muted mb-5">Pengirim (Kurir / Vendor Supplier)</div>
                <div class="fw-bold text-decoration-underline">( ........................................ )</div>
            </div>
            <div class="col-6 text-center">
                <div class="small text-muted mb-5">Petugas Penerima Gudang</div>
                <div class="fw-bold text-decoration-underline">{{ $purchase->user->name ?? 'Admin Gudang' }}</div>
            </div>
        </div>
    </div>
</body>
</html>
