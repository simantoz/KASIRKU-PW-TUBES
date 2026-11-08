@extends('layouts.app')

@section('title', 'Riwayat Barang Masuk (PO)')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold mb-1" style="color: #fff; font-size: 1.6rem;">Riwayat Transaksi Barang Masuk</h2>
            <div style="color: #8892b0; font-size: 0.85rem;">Faktur Pembelian & Pasokan Supplier (Purchase Orders)</div>
        </div>
        <div class="d-flex gap-2">
            @if(Auth::user()->role === 'gudang')
                <a href="{{ route('gudang.purchases.create') }}" class="btn btn-primary-custom d-flex align-items-center gap-2">
                    <i class="bi bi-plus-circle"></i>
                    <span>Input Barang Masuk</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Summary KPI Box -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="stat-card">
                <div style="color: #8892b0; font-size: 0.82rem; font-weight: 600;">Total Faktur PO Masuk</div>
                <h3 class="fw-bold mt-2 mb-1" style="color: #fff;">{{ number_format($totalPurchasesCount) }} <span style="font-size: 0.9rem; font-weight: normal; color: #8892b0;">Transaksi</span></h3>
                <div class="small fw-semibold" style="color: #60a5fa;">
                    <i class="bi bi-truck"></i> Penerimaan dari supplier
                </div>
                <div class="stat-icon" style="background: rgba(59, 130, 246, 0.15); color: #60a5fa;">
                    <i class="bi bi-receipt"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="stat-card">
                <div style="color: #8892b0; font-size: 0.82rem; font-weight: 600;">Total Biaya Belanja Stok (Pengeluaran)</div>
                <h3 class="fw-bold mt-2 mb-1" style="color: #ea7c69;">Rp {{ number_format($totalSpent, 0, ',', '.') }}</h3>
                <div class="small fw-semibold" style="color: #8892b0;">
                    Akumulasi biaya pasokan bahan
                </div>
                <div class="stat-icon" style="background: rgba(234, 124, 105, 0.15); color: #ea7c69;">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Card -->
    <div class="card-custom p-4 mb-4">
        <form method="GET" action="{{ url()->current() }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-semibold" style="color: #8892b0;">Cari No PO / Supplier / No Faktur</label>
                <input type="text" name="search" class="form-control" placeholder="Cari..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold" style="color: #8892b0;">Dari Tanggal</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold" style="color: #8892b0;">Sampai Tanggal</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary-custom flex-grow-1">Filter</button>
                <a href="{{ url()->current() }}" class="btn" style="background: #2d303e; color: #fff; border-radius: 12px;" title="Reset">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Table PO -->
    <div class="card-custom p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-dark-custom mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">No. PO</th>
                        <th>Waktu Penerimaan</th>
                        <th>Supplier / Vendor</th>
                        <th>Ref Surat Jalan</th>
                        <th>Item Masuk</th>
                        <th class="text-end">Total Pembelian</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchases as $p)
                        <tr>
                            <td class="ps-4">
                                <span class="badge" style="background: #2d303e; color: #fff;">{{ $p->purchase_code }}</span>
                            </td>
                            <td>
                                <div class="text-white">{{ $p->created_at->format('d/m/Y') }}</div>
                                <small style="color: #8892b0;">{{ $p->created_at->format('H:i') }} WIB</small>
                            </td>
                            <td>
                                <div class="fw-bold text-white">{{ $p->supplier_name }}</div>
                                <small style="color: #8892b0;">Penerima: {{ $p->user->name ?? 'Gudang' }}</small>
                            </td>
                            <td>
                                <span style="color: #abb2bf;">{{ $p->invoice_ref ?: '-' }}</span>
                            </td>
                            <td>
                                <span class="badge" style="background: rgba(59, 130, 246, 0.15); color: #60a5fa;">
                                    {{ $p->details->sum('qty') }} unit ({{ $p->details->count() }} menu)
                                </span>
                            </td>
                            <td class="text-end fw-bold" style="color: #ea7c69;">
                                Rp {{ number_format($p->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="text-center pe-4">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn" style="background: #2d303e; color: #fff;" onclick="showPurchaseDetail({{ $p->id }})" title="Lihat Detail Item">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <a href="{{ route('gudang.purchases.print', $p->id) }}" target="_blank" class="btn" style="background: #2d303e; color: #ea7c69;" title="Cetak Surat Penerimaan Barang">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5" style="color: #8892b0;">
                                Belum ada transaksi barang masuk yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top d-flex justify-content-end" style="border-color: rgba(255,255,255,0.08) !important;">
            {{ $purchases->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Modal Detail Barang Masuk -->
<div class="modal fade" id="purchaseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content dark-modal shadow">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold text-white" id="modalPoCode">Faktur Barang Masuk</h5>
                    <span style="color: #8892b0; font-size: 0.8rem;" id="modalPoDate"></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3 mb-4">
                    <div class="col-sm-4">
                        <div style="color: #8892b0; font-size: 0.8rem;">Supplier</div>
                        <div class="fw-bold text-white" id="modalPoSupplier"></div>
                    </div>
                    <div class="col-sm-4">
                        <div style="color: #8892b0; font-size: 0.8rem;">No Surat Jalan / Ref</div>
                        <div class="fw-bold text-white" id="modalPoRef"></div>
                    </div>
                    <div class="col-sm-4">
                        <div style="color: #8892b0; font-size: 0.8rem;">Diterima Oleh</div>
                        <div class="fw-bold text-white" id="modalPoUser"></div>
                    </div>
                </div>

                <h6 class="fw-bold text-white mb-2" style="font-size: 0.9rem;">Daftar Item Barang Masuk:</h6>
                <div class="table-responsive border rounded-3 mb-3" style="border-color: rgba(255,255,255,0.08) !important;">
                    <table class="table table-dark-custom mb-0">
                        <thead>
                            <tr>
                                <th>Menu / Barang</th>
                                <th class="text-center">Qty Masuk</th>
                                <th class="text-end">Harga Beli</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="modalPoItemsBody"></tbody>
                    </table>
                </div>

                <div class="p-3 rounded-3" style="background: #2d303e;">
                    <div class="d-flex justify-content-between align-items-center">
                        <span style="color: #8892b0;">Total Pengeluaran:</span>
                        <span class="fw-bold fs-5 text-white" id="modalPoTotal"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                <a href="#" id="modalPoPrintBtn" target="_blank" class="btn btn-sm btn-primary-custom">
                    <i class="bi bi-printer"></i> Cetak Bukti Terima
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function showPurchaseDetail(id) {
        $.ajax({
            url: `/gudang/purchases/${id}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const p = response.data;
                    $('#modalPoCode').text(p.purchase_code);
                    $('#modalPoDate').text(new Date(p.created_at).toLocaleString('id-ID'));
                    $('#modalPoSupplier').text(p.supplier_name);
                    $('#modalPoRef').text(p.invoice_ref || '-');
                    $('#modalPoUser').text(p.user ? p.user.name : '-');
                    $('#modalPoTotal').text('Rp ' + p.total_amount.toLocaleString('id-ID'));
                    $('#modalPoPrintBtn').attr('href', `/purchases/${p.id}/print`);

                    let itemsHtml = '';
                    p.details.forEach(item => {
                        itemsHtml += `
                            <tr>
                                <td>
                                    <div class="text-white fw-bold">${item.product_name}</div>
                                    <small style="color: #8892b0;">${item.product_code}</small>
                                </td>
                                <td class="text-center fw-bold" style="color: #34d399;">+${item.qty}</td>
                                <td class="text-end" style="color: #8892b0;">Rp ${item.cost_price.toLocaleString('id-ID')}</td>
                                <td class="text-end text-white fw-bold">Rp ${item.subtotal.toLocaleString('id-ID')}</td>
                            </tr>
                        `;
                    });
                    $('#modalPoItemsBody').html(itemsHtml);

                    const modal = new bootstrap.Modal(document.getElementById('purchaseModal'));
                    modal.show();
                }
            }
        });
    }
</script>
@endsection
