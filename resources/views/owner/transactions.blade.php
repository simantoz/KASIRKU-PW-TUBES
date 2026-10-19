@extends('layouts.app')

@section('title', 'Riwayat Transaksi Penjualan')
@section('page_title', 'Riwayat Transaksi & Laporan Penjualan')

@section('content')
<div class="container-fluid px-0">
    <!-- Filter Card -->
    <div class="card card-custom p-4 mb-4 border-0 shadow-sm">
        <form method="GET" action="{{ route('owner.transactions') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold text-muted small">Dari Tanggal</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold text-muted small">Sampai Tanggal</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold text-muted small">Pilih Kasir</label>
                <select name="user_id" class="form-select">
                    <option value="">Semua Kasir</option>
                    @foreach($cashiers as $c)
                        <option value="{{ $c->id }}" {{ request('user_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold text-muted small">Metode Bayar</label>
                <select name="payment_method" class="form-select">
                    <option value="">Semua Metode</option>
                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Tunai (Cash)</option>
                    <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                    <option value="transfer" {{ request('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary-custom flex-grow-1">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                <a href="{{ route('owner.transactions') }}" class="btn btn-light border" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Summary & Export Bar -->
    <div class="card card-custom p-3 mb-4 border-0 shadow-sm bg-white">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-4">
                <div>
                    <span class="text-muted small">Total Omzet (Hasil Filter):</span>
                    <h4 class="fw-bold text-success mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                </div>
                <div class="border-start ps-4">
                    <span class="text-muted small">Total Transaksi:</span>
                    <h4 class="fw-bold text-dark mb-0">{{ number_format($totalTransactionsCount) }} Trx</h4>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('owner.export_csv', request()->query()) }}" class="btn btn-success fw-semibold">
                    <i class="bi bi-file-earmark-excel"></i> Export CSV / Excel
                </a>
                <a href="{{ route('owner.print_report', request()->query()) }}" target="_blank" class="btn btn-outline-dark fw-semibold">
                    <i class="bi bi-printer"></i> Cetak Laporan PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card card-custom p-0 border-0 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">No. Invoice</th>
                        <th>Waktu Transaksi</th>
                        <th>Kasir</th>
                        <th>Pelanggan</th>
                        <th>Metode Bayar</th>
                        <th>Subtotal</th>
                        <th>Total Bayar</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $t)
                        <tr>
                            <td class="ps-3">
                                <span class="badge bg-dark-subtle text-dark font-monospace fw-semibold">{{ $t->invoice_code }}</span>
                            </td>
                            <td>
                                <div class="fw-medium text-dark">{{ $t->created_at->format('d/m/Y') }}</div>
                                <div class="text-muted small">{{ $t->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td>
                                <span class="fw-semibold text-dark">{{ $t->user->name ?? '-' }}</span>
                            </td>
                            <td>
                                <span class="text-muted">{{ $t->customer_name }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border text-uppercase" style="font-size: 0.75rem;">
                                    {{ $t->payment_method }}
                                </span>
                            </td>
                            <td class="text-muted">
                                Rp {{ number_format($t->subtotal, 0, ',', '.') }}
                            </td>
                            <td>
                                <span class="fw-bold text-primary">Rp {{ number_format($t->total_amount, 0, ',', '.') }}</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-primary" onclick="showTransactionDetail({{ $t->id }})">
                                        <i class="bi bi-eye"></i> Detail
                                    </button>
                                    <a href="{{ route('kasir.receipt', $t->id) }}" target="_blank" class="btn btn-outline-secondary" title="Cetak Struk">
                                        <i class="bi bi-receipt"></i> Struk
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                Tidak ada data transaksi yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top d-flex justify-content-end">
            {{ $transactions->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Modal Detail Transaksi (Populated via AJAX) -->
<div class="modal fade" id="transactionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <div>
                    <h6 class="modal-title fw-bold" id="modalInvoiceCode">Detail Transaksi</h6>
                    <span class="text-muted small" id="modalDate"></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3 mb-4">
                    <div class="col-sm-4">
                        <div class="text-muted small">Kasir Bertugas</div>
                        <div class="fw-bold text-dark" id="modalCashier"></div>
                    </div>
                    <div class="col-sm-4">
                        <div class="text-muted small">Nama Pelanggan</div>
                        <div class="fw-bold text-dark" id="modalCustomer"></div>
                    </div>
                    <div class="col-sm-4">
                        <div class="text-muted small">Metode Pembayaran</div>
                        <span class="badge bg-primary text-uppercase" id="modalPaymentMethod"></span>
                    </div>
                </div>

                <h6 class="fw-bold mb-2">Daftar Item Barang:</h6>
                <div class="table-responsive border rounded-3 mb-3">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="modalItemsBody">
                            <!-- Populated by JS -->
                        </tbody>
                    </table>
                </div>

                <div class="bg-light p-3 rounded-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Subtotal Belanja:</span>
                        <span class="fw-semibold text-dark" id="modalSubtotal"></span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Uang Diterima:</span>
                        <span class="fw-semibold text-dark" id="modalPaid"></span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Kembalian:</span>
                        <span class="fw-semibold text-dark" id="modalChange"></span>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold text-dark fs-6">Total Tagihan:</span>
                        <span class="fw-bold text-primary fs-5" id="modalTotal"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <a href="#" id="modalPrintBtn" target="_blank" class="btn btn-primary">
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
        Swal.fire({
            title: 'Memuat data...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // AJAX Request to fetch transaction detail
        $.ajax({
            url: `/owner/transactions/${id}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                Swal.close();
                if (response.success) {
                    const t = response.data;
                    $('#modalInvoiceCode').text(t.invoice_code);
                    $('#modalDate').text(new Date(t.created_at).toLocaleString('id-ID'));
                    $('#modalCashier').text(t.user ? t.user.name : '-');
                    $('#modalCustomer').text(t.customer_name);
                    $('#modalPaymentMethod').text(t.payment_method);
                    $('#modalSubtotal').text('Rp ' + t.subtotal.toLocaleString('id-ID'));
                    $('#modalPaid').text('Rp ' + t.paid_amount.toLocaleString('id-ID'));
                    $('#modalChange').text('Rp ' + t.change_amount.toLocaleString('id-ID'));
                    $('#modalTotal').text('Rp ' + t.total_amount.toLocaleString('id-ID'));
                    $('#modalPrintBtn').attr('href', `/receipt/${t.id}`);

                    let itemsHtml = '';
                    t.details.forEach(item => {
                        itemsHtml += `
                            <tr>
                                <td>
                                    <div class="fw-semibold">${item.product_name}</div>
                                    <small class="text-muted font-monospace">${item.product_code}</small>
                                </td>
                                <td class="text-center fw-bold">${item.qty}</td>
                                <td class="text-end">Rp ${item.selling_price.toLocaleString('id-ID')}</td>
                                <td class="text-end fw-semibold">Rp ${item.subtotal.toLocaleString('id-ID')}</td>
                            </tr>
                        `;
                    });
                    $('#modalItemsBody').html(itemsHtml);

                    const modal = new bootstrap.Modal(document.getElementById('transactionModal'));
                    modal.show();
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Tidak dapat memuat detail transaksi.'
                });
            }
        });
    }
</script>
@endsection
