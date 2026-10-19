@extends('layouts.app')

@section('title', 'Stok Masuk Supplier')
@section('page_title', 'Stok Masuk & Penerimaan Supplier')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Card -->
    <div class="card card-custom p-4 mb-4 border-0 shadow-sm bg-white">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h5 class="fw-bold text-dark mb-1">Riwayat Penerimaan Stok Masuk</h5>
                <p class="text-muted mb-0 small">Pencatatan pasokan barang dari supplier yang secara otomatis menambah stok gudang & kasir.</p>
            </div>
            <button type="button" class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addStockModal">
                <i class="bi bi-truck me-1"></i> Input Stok Masuk Baru
            </button>
        </div>
    </div>

    <!-- Filter & Summary Row -->
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card card-custom p-3 border-0 shadow-sm h-100">
                <form method="GET" action="{{ route('gudang.stock.index') }}" class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-muted">Dari Tanggal</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-muted">Sampai Tanggal</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small text-muted">Pilih Barang</label>
                        <select name="product_id" class="form-select">
                            <option value="">Semua Barang</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}" {{ request('product_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1 d-flex gap-1">
                        <button type="submit" class="btn btn-primary-custom w-100" title="Filter">
                            <i class="bi bi-funnel"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-custom p-3 border-0 shadow-sm h-100 bg-white d-flex justify-content-center">
                <div class="d-flex align-items-center justify-content-around text-center">
                    <div>
                        <span class="text-muted small">Total Barang Masuk:</span>
                        <h4 class="fw-bold text-success mb-0">+{{ number_format($totalStockInQty) }} Unit</h4>
                    </div>
                    <div class="border-start ps-3">
                        <span class="text-muted small">Total Biaya Pasokan:</span>
                        <h5 class="fw-bold text-dark mb-0">Rp {{ number_format($totalStockInCost, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Log Table -->
    <div class="card card-custom p-0 border-0 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Tanggal & Waktu</th>
                        <th>Kode & Nama Barang</th>
                        <th>Supplier</th>
                        <th class="text-center">Jumlah Masuk</th>
                        <th class="text-end">Harga Beli Satuan</th>
                        <th class="text-end">Total Biaya</th>
                        <th>Dicatat Oleh</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mutations as $m)
                        <tr>
                            <td class="ps-3">
                                <div class="fw-semibold text-dark">{{ $m->created_at->format('d/m/Y') }}</div>
                                <div class="text-muted small">{{ $m->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $m->product->name ?? 'Produk Dihapus' }}</div>
                                <span class="badge bg-light text-muted font-monospace border">{{ $m->product->code ?? '-' }}</span>
                            </td>
                            <td>
                                <span class="fw-medium text-dark">{{ $m->supplier_name ?: '-' }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success-subtle text-success fw-bold px-2 py-1">
                                    +{{ $m->qty }} {{ $m->product->unit ?? 'pcs' }}
                                </span>
                            </td>
                            <td class="text-end text-muted">
                                Rp {{ number_format($m->cost_price, 0, ',', '.') }}
                            </td>
                            <td class="text-end fw-bold text-dark">
                                Rp {{ number_format($m->total_cost, 0, ',', '.') }}
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $m->user->name ?? 'Gudang' }}</span>
                            </td>
                            <td class="text-muted small">
                                {{ $m->notes ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                Belum ada riwayat stok masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top d-flex justify-content-end">
            {{ $mutations->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Modal Input Stok Masuk -->
<div class="modal fade" id="addStockModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('gudang.stock.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-light">
                    <h6 class="modal-title fw-bold">Input Penerimaan Stok dari Supplier</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Pilih Barang yang Masuk</label>
                        <select name="product_id" id="stockProductSelect" class="form-select" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}" data-cost="{{ $p->cost_price }}" data-stock="{{ $p->stock }}" data-unit="{{ $p->unit }}">
                                    {{ $p->name }} (Sisa Stok: {{ $p->stock }} {{ $p->unit }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nama Supplier / Pemasok</label>
                        <input type="text" name="supplier_name" class="form-control" placeholder="Contoh: PT Indomarco, CV Sumber Rejeki" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Jumlah Masuk (Qty)</label>
                            <input type="number" name="qty" id="stockQtyInput" class="form-control" placeholder="0" min="1" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Harga Beli Satuan (Rp)</label>
                            <input type="number" name="cost_price" id="stockCostPriceInput" class="form-control" placeholder="0" min="0" required>
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded-3 mb-3 border">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Total Estimasi Biaya Pasokan:</span>
                            <span class="fw-bold text-primary fs-6" id="stockTotalCostDisplay">Rp 0</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Catatan Penerimaan (Opsional)</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Nomor faktur supplier, kondisi barang, dll..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom">Simpan & Tambah Stok</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $('#stockProductSelect').on('change', function() {
        const selected = $(this).find(':selected');
        const defaultCost = selected.data('cost');
        if (defaultCost !== undefined) {
            $('#stockCostPriceInput').val(defaultCost);
            calcStockTotal();
        }
    });

    $('#stockQtyInput, #stockCostPriceInput').on('input', function() {
        calcStockTotal();
    });

    function calcStockTotal() {
        const qty = parseFloat($('#stockQtyInput').val()) || 0;
        const cost = parseFloat($('#stockCostPriceInput').val()) || 0;
        const total = qty * cost;
        $('#stockTotalCostDisplay').text('Rp ' + total.toLocaleString('id-ID'));
    }
</script>
@endsection
