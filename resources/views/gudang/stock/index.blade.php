@extends('layouts.app')

@section('title', 'Stok Masuk')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: #fff; font-size: 1.6rem;">Penerimaan Stok Masuk</h2>
            <div style="color: #8892b0; font-size: 0.85rem;">Input pasokan bahan / menu dari supplier</div>
        </div>
        <button type="button" class="btn btn-primary-custom d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addStockModal">
            <i class="bi bi-truck"></i>
            <span>Input Stok Masuk</span>
        </button>
    </div>

    <!-- Filter Card -->
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card-custom p-3 h-100">
                <form method="GET" action="{{ route('gudang.stock.index') }}" class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold" style="color: #8892b0;">Dari Tanggal</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold" style="color: #8892b0;">Sampai Tanggal</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold" style="color: #8892b0;">Pilih Menu</label>
                        <select name="product_id" class="form-select">
                            <option value="">Semua Menu</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}" {{ request('product_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary-custom w-100"><i class="bi bi-funnel"></i></button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-custom p-3 h-100 d-flex align-items-center justify-content-around text-center">
                <div>
                    <span style="color: #8892b0; font-size: 0.82rem;">Total Unit Masuk:</span>
                    <h4 class="fw-bold mb-0" style="color: #34d399;">+{{ number_format($totalStockInQty) }}</h4>
                </div>
                <div class="border-start ps-3" style="border-color: rgba(255,255,255,0.08) !important;">
                    <span style="color: #8892b0; font-size: 0.82rem;">Total Biaya:</span>
                    <h5 class="fw-bold mb-0 text-white">Rp {{ number_format($totalStockInCost, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card-custom p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-dark-custom mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Waktu</th>
                        <th>Menu / Barang</th>
                        <th>Supplier</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-end">Harga Beli</th>
                        <th class="text-end">Total Biaya</th>
                        <th>Petugas</th>
                        <th class="pe-4">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mutations as $m)
                        <tr>
                            <td class="ps-4">
                                <div class="text-white">{{ $m->created_at->format('d/m/Y') }}</div>
                                <small style="color: #8892b0;">{{ $m->created_at->format('H:i') }} WIB</small>
                            </td>
                            <td>
                                <div class="fw-bold text-white">{{ $m->product->name ?? '-' }}</div>
                                <small style="color: #8892b0;">{{ $m->product->code ?? '-' }}</small>
                            </td>
                            <td class="text-white">{{ $m->supplier_name ?: '-' }}</td>
                            <td class="text-center">
                                <span class="badge fw-bold" style="background: rgba(16, 185, 129, 0.15); color: #34d399;">
                                    +{{ $m->qty }} {{ $m->product->unit ?? 'pcs' }}
                                </span>
                            </td>
                            <td class="text-end" style="color: #8892b0;">Rp {{ number_format($m->cost_price, 0, ',', '.') }}</td>
                            <td class="text-end fw-bold text-white">Rp {{ number_format($m->total_cost, 0, ',', '.') }}</td>
                            <td><span class="badge" style="background: #2d303e; color: #fff;">{{ $m->user->name ?? '-' }}</span></td>
                            <td class="pe-4" style="color: #8892b0;">{{ $m->notes ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5" style="color: #8892b0;">Belum ada riwayat stok masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top d-flex justify-content-end" style="border-color: rgba(255,255,255,0.08) !important;">
            {{ $mutations->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Modal Input Stok Masuk -->
<div class="modal fade" id="addStockModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content dark-modal shadow">
            <form action="{{ route('gudang.stock.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-white">Input Stok Masuk Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold" style="color: #8892b0;">Pilih Menu / Produk</label>
                        <select name="product_id" id="stockProductSelect" class="form-select" required>
                            <option value="">Pilih Menu</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}" data-cost="{{ $p->cost_price }}" data-stock="{{ $p->stock }}" data-unit="{{ $p->unit }}">
                                    {{ $p->name }} (Sisa: {{ $p->stock }} {{ $p->unit }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold" style="color: #8892b0;">Nama Supplier</label>
                        <input type="text" name="supplier_name" class="form-control" placeholder="PT Indomarco / CV Sukses" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold" style="color: #8892b0;">Jumlah Masuk</label>
                            <input type="number" name="qty" id="stockQtyInput" class="form-control" min="1" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold" style="color: #8892b0;">Harga Beli Satuan (Rp)</label>
                            <input type="number" name="cost_price" id="stockCostPriceInput" class="form-control" min="0" required>
                        </div>
                    </div>

                    <div class="p-3 rounded-3 mb-3" style="background: #2d303e;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="color: #8892b0; font-size: 0.85rem;">Total Biaya Pasokan:</span>
                            <span class="fw-bold text-white fs-6" id="stockTotalCostDisplay">Rp 0</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold" style="color: #8892b0;">Catatan</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary-custom">Simpan & Tambah Stok</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $('#stockProductSelect').on('change', function() {
        const defaultCost = $(this).find(':selected').data('cost');
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
        $('#stockTotalCostDisplay').text('Rp ' + (qty * cost).toLocaleString('id-ID'));
    }
</script>
@endsection
