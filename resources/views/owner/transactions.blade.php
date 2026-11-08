@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: #fff; font-size: 1.6rem;">Riwayat Transaksi Penjualan</h2>
            <div style="color: #8892b0; font-size: 0.85rem;">Semua riwayat transaksi dari seluruh kasir</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('owner.export_csv', request()->query()) }}" class="btn" style="background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 12px; font-weight: 600;">
                <i class="bi bi-file-earmark-excel me-1"></i> Export CSV
            </a>
            <a href="{{ route('owner.print_report', request()->query()) }}" target="_blank" class="btn btn-primary-custom">
                <i class="bi bi-printer me-1"></i> Cetak PDF
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card-custom p-4 mb-4">
        <form method="GET" action="{{ route('owner.transactions') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold" style="color: #8892b0;">Dari Tanggal</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold" style="color: #8892b0;">Sampai Tanggal</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold" style="color: #8892b0;">Kasir</label>
                <select name="user_id" class="form-select">
                    <option value="">Semua Kasir</option>
                    @foreach($cashiers as $c)
                        <option value="{{ $c->id }}" {{ request('user_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold" style="color: #8892b0;">Metode Bayar</label>
                <select name="payment_method" class="form-select">
                    <option value="">Semua</option>
                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                    <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                    <option value="transfer" {{ request('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary-custom flex-grow-1">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                <a href="{{ route('owner.transactions') }}" class="btn" style="background: #2d303e; color: #fff; border-radius: 12px;" title="Reset">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Box -->
    <div class="d-flex gap-4 p-3 mb-4 card-custom">
        <div>
            <span style="color: #8892b0; font-size: 0.82rem;">Total Omzet:</span>
            <h4 class="fw-bold mb-0" style="color: #34d399;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
        </div>
        <div class="border-start ps-4" style="border-color: rgba(255,255,255,0.08) !important;">
            <span style="color: #8892b0; font-size: 0.82rem;">Total Transaksi:</span>
            <h4 class="fw-bold mb-0 text-white">{{ number_format($totalTransactionsCount) }} Trx</h4>
        </div>
    </div>

    <!-- Table -->
    <div class="card-custom p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-dark-custom mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">No. Invoice</th>
                        <th>Waktu</th>
                        <th>Kasir</th>
                        <th>Pelanggan</th>
                        <th>Metode</th>
                        <th class="text-end">Total</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $t)
                        <tr>
                            <td class="ps-4">
                                <span class="badge" style="background: #2d303e; color: #fff;">{{ $t->invoice_code }}</span>
                            </td>
                            <td>
                                <div class="text-white">{{ $t->created_at->format('d/m/Y') }}</div>
                                <small style="color: #8892b0;">{{ $t->created_at->format('H:i') }} WIB</small>
                            </td>
                            <td class="text-white">{{ $t->user->name ?? '-' }}</td>
                            <td style="color: #8892b0;">{{ $t->customer_name }}</td>
                            <td>
                                <span class="badge text-uppercase" style="background: rgba(234, 124, 105, 0.15); color: #ea7c69;">
                                    {{ $t->payment_method }}
                                </span>
                            </td>
                            <td class="text-end fw-bold" style="color: #ea7c69;">
                                Rp {{ number_format($t->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="text-center pe-4">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn" style="background: #2d303e; color: #fff; border-radius: 8px 0 0 8px;" onclick="showTransactionDetail({{ $t->id }})">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <a href="{{ route('kasir.receipt', $t->id) }}" target="_blank" class="btn" style="background: #2d303e; color: #ea7c69; border-radius: 0 8px 8px 0;" title="Struk">
                                        <i class="bi bi-receipt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5" style="color: #8892b0;">
                                Tidak ada data transaksi yang sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top d-flex justify-content-end" style="border-color: rgba(255,255,255,0.08) !important;">
            {{ $transactions->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Modal Detail Transaksi -->
<div class="modal fade" id="transactionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content dark-modal shadow">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold text-white" id="modalInvoiceCode">Detail Transaksi</h5>
                    <span style="color: #8892b0; font-size: 0.8rem;" id="modalDate"></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3 mb-4">
                    <div class="col-sm-4">
                        <div style="color: #8892b0; font-size: 0.8rem;">Kasir</div>
                        <div class="fw-bold text-white" id="modalCashier"></div>
                    </div>
                    <div class="col-sm-4">
                        <div style="color: #8892b0; font-size: 0.8rem;">Pelanggan</div>
                        <div class="fw-bold text-white" id="modalCustomer"></div>
                    </div>
                    <div class="col-sm-4">
                        <div style="color: #8892b0; font-size: 0.8rem;">Metode</div>
                        <span class="badge text-uppercase" style="background: rgba(234, 124, 105, 0.15); color: #ea7c69;" id="modalPaymentMethod"></span>
                    </div>
                </div>

                <div class="table-responsive border rounded-3 mb-3" style="border-color: rgba(255,255,255,0.08) !important;">
                    <table class="table table-dark-custom mb-0">
                        <thead>
                            <tr>
                                <th>Menu</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="modalItemsBody"></tbody>
                    </table>
                </div>

                <div class="p-3 rounded-3" style="background: #2d303e;">
                    <div class="d-flex justify-content-between mb-1" style="color: #8892b0;">
                        <span>Total Tagihan:</span>
                        <span class="fw-bold fs-5 text-white" id="modalTotal"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                <a href="#" id="modalPrintBtn" target="_blank" class="btn btn-sm btn-primary-custom">
                    <i class="bi bi-printer"></i> Cetak Struk
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function showTransactionDetail(id) {
        $.ajax({
            url: `/owner/transactions/${id}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const t = response.data;
                    $('#modalInvoiceCode').text(t.invoice_code);
                    $('#modalDate').text(new Date(t.created_at).toLocaleString('id-ID'));
                    $('#modalCashier').text(t.user ? t.user.name : '-');
                    $('#modalCustomer').text(t.customer_name);
                    $('#modalPaymentMethod').text(t.payment_method);
                    $('#modalTotal').text('Rp ' + t.total_amount.toLocaleString('id-ID'));
                    $('#modalPrintBtn').attr('href', `/receipt/${t.id}`);

                    let itemsHtml = '';
                    t.details.forEach(item => {
                        itemsHtml += `
                            <tr>
                                <td class="text-white">${item.product_name}</td>
                                <td class="text-center">${item.qty}</td>
                                <td class="text-end">Rp ${item.selling_price.toLocaleString('id-ID')}</td>
                                <td class="text-end text-white fw-bold">Rp ${item.subtotal.toLocaleString('id-ID')}</td>
                            </tr>
                        `;
                    });
                    $('#modalItemsBody').html(itemsHtml);

                    const modal = new bootstrap.Modal(document.getElementById('transactionModal'));
                    modal.show();
                }
            }
        });
    }
</script>
@endsection
