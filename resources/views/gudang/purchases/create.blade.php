@extends('layouts.app')

@section('title', 'Input Transaksi Barang Masuk')

@section('styles')
<style>
    body {
        background-color: #1f1d2b;
    }
    .page-content {
        padding: 1.5rem 1.75rem;
    }

    .restock-layout {
        display: flex;
        gap: 1.75rem;
        align-items: flex-start;
    }

    /* Left Catalog Column */
    .restock-catalog {
        flex: 1;
        min-width: 0;
    }

    .catalog-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .search-box-wrap {
        position: relative;
        width: 300px;
    }

    .search-box-wrap input {
        background: #2d303e;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        padding: 0.65rem 1rem 0.65rem 2.5rem;
        color: #fff;
        font-size: 0.88rem;
        width: 100%;
        transition: all 0.2s;
    }

    .search-box-wrap input:focus {
        outline: none;
        border-color: #ea7c69;
        box-shadow: 0 0 0 3px rgba(234, 124, 105, 0.2);
    }

    .search-box-wrap .search-icon {
        position: absolute;
        left: 0.85rem;
        top: 50%;
        transform: translateY(-50%);
        color: #8892b0;
        font-size: 0.95rem;
    }

    .category-tabs {
        display: flex;
        gap: 0.75rem;
        overflow-x: auto;
        padding-bottom: 0.75rem;
        margin-bottom: 1.25rem;
        scrollbar-width: none;
    }
    .category-tabs::-webkit-scrollbar { display: none; }

    .cat-tab-btn {
        background: #252836;
        border: 1px solid rgba(255, 255, 255, 0.06);
        color: #abb2bf;
        padding: 0.55rem 1.15rem;
        border-radius: 12px;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
        transition: all 0.2s;
    }

    .cat-tab-btn:hover {
        background: #2d303e;
        color: #fff;
    }

    .cat-tab-btn.active {
        background: rgba(234, 124, 105, 0.15);
        color: #ea7c69;
        border-color: #ea7c69;
    }

    /* Product Mini Card */
    .product-pick-card {
        background: #252836;
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 16px;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.85rem;
        height: 100%;
    }

    .product-pick-card:hover {
        transform: translateY(-2px);
        border-color: rgba(234, 124, 105, 0.5);
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.3);
    }

    .pick-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #2d303e;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        flex-shrink: 0;
    }

    /* Right PO Cart Panel */
    .restock-panel {
        width: 420px;
        background: #252836;
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 20px;
        padding: 1.5rem;
        position: sticky;
        top: 1.5rem;
        display: flex;
        flex-direction: column;
        max-height: calc(100vh - 3rem);
    }

    .panel-header {
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .items-scroll {
        flex: 1;
        overflow-y: auto;
        margin-right: -0.5rem;
        padding-right: 0.5rem;
        margin-bottom: 1rem;
        min-height: 180px;
    }

    .cart-po-row {
        background: #2d303e;
        border-radius: 12px;
        padding: 0.75rem;
        margin-bottom: 0.6rem;
    }

    .cart-po-row .row-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.4rem;
    }

    .cart-po-row .row-inputs {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .btn-submit-po {
        background: #ea7c69;
        border: none;
        color: #fff;
        font-weight: 700;
        font-size: 0.95rem;
        padding: 0.85rem;
        border-radius: 14px;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
        margin-top: 1rem;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 8px 24px rgba(234, 124, 105, 0.4);
    }

    .btn-submit-po:hover {
        background: #e06550;
        transform: translateY(-1px);
    }

    @media (max-width: 1199.98px) {
        .restock-layout {
            flex-direction: column;
        }
        .restock-panel {
            width: 100%;
            position: static;
            max-height: none;
        }
    }
</style>
@endsection

@section('content')
<div class="restock-layout">
    <!-- Left Catalog Column -->
    <div class="restock-catalog">
        <div class="catalog-header">
            <div>
                <h2 class="fw-bold text-white mb-1" style="font-size: 1.5rem;">Input Penerimaan Barang Masuk</h2>
                <div style="color: #8892b0; font-size: 0.85rem;">Klik menu di bawah untuk menambahkan ke daftar barang datang dari supplier</div>
            </div>
            <div class="search-box-wrap">
                <i class="bi bi-search search-icon"></i>
                <input type="text" id="searchInput" placeholder="Cari nama menu..." autocomplete="off">
            </div>
        </div>

        <!-- Category Tabs -->
        <div class="category-tabs" id="categoryTabs">
            <button class="cat-tab-btn active" data-category="all">
                <span>Semua Kategori</span>
            </button>
            @foreach($categories as $cat)
                <button class="cat-tab-btn" data-category="{{ $cat->id }}">
                    <span>{{ $cat->name }}</span>
                </button>
            @endforeach
        </div>

        <!-- Products Grid -->
        <div class="row g-3" id="productsGrid">
            @foreach($products as $p)
                @php
                    $pNameLower = strtolower($p->name);
                    $icon = '🍔';
                    if (str_contains($pNameLower, 'mie') || str_contains($pNameLower, 'ramen')) $icon = '🍜';
                    elseif (str_contains($pNameLower, 'teh') || str_contains($pNameLower, 'kopi') || str_contains($pNameLower, 'air') || str_contains($pNameLower, 'jus')) $icon = '🥤';
                    elseif (str_contains($pNameLower, 'chitato') || str_contains($pNameLower, 'snack') || str_contains($pNameLower, 'dessert')) $icon = '🍨';
                    elseif (str_contains($pNameLower, 'ayam')) $icon = '🍗';
                @endphp
                <div class="col-xl-4 col-md-6 product-card-item" data-category="{{ $p->category_id }}" data-name="{{ strtolower($p->name) }}">
                    <div class="product-pick-card" onclick="addToRestock({{ $p->id }}, '{{ addslashes($p->name) }}', '{{ $p->code }}', {{ $p->cost_price }}, '{{ $p->unit }}', '{{ $icon }}')">
                        <div class="pick-icon">{{ $icon }}</div>
                        <div class="overflow-hidden flex-grow-1">
                            <div class="fw-bold text-white text-truncate" style="font-size: 0.9rem;" title="{{ $p->name }}">{{ $p->name }}</div>
                            <div style="color: #8892b0; font-size: 0.78rem;">Sisa Stok: <strong class="text-white">{{ $p->stock }} {{ $p->unit }}</strong></div>
                            <div style="color: #ea7c69; font-size: 0.8rem; font-weight: 600;">Modal: Rp {{ number_format($p->cost_price, 0, ',', '.') }}</div>
                        </div>
                        <button type="button" class="btn btn-sm" style="background: rgba(234, 124, 105, 0.2); color: #ea7c69; border-radius: 8px;">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Right PO Cart Panel -->
    <div class="restock-panel">
        <div class="panel-header">
            <h4 class="fw-bold text-white mb-0" style="font-size: 1.2rem;">Faktur Pembelian (PO)</h4>
            <div style="color: #8892b0; font-size: 0.8rem;">Petugas Gudang: {{ Auth::user()->name }}</div>
        </div>

        <!-- Supplier & Reference Info Form -->
        <div class="mb-3">
            <label class="form-label small fw-semibold" style="color: #8892b0; margin-bottom: 2px;">Nama Supplier / Vendor *</label>
            <input type="text" id="supplierNameInput" class="form-control form-control-sm" placeholder="Contoh: PT Indomarco, CV Sumber Rejeki" required>
        </div>
        <div class="row g-2 mb-3">
            <div class="col-6">
                <label class="form-label small fw-semibold" style="color: #8892b0; margin-bottom: 2px;">No. Surat Jalan / Ref</label>
                <input type="text" id="invoiceRefInput" class="form-control form-control-sm" placeholder="SJ-98231">
            </div>
            <div class="col-6">
                <label class="form-label small fw-semibold" style="color: #8892b0; margin-bottom: 2px;">Catatan (Opsional)</label>
                <input type="text" id="notesInput" class="form-control form-control-sm" placeholder="Kondisi barang...">
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-2">
            <span style="font-size: 0.82rem; color: #8892b0; font-weight: 600;">Daftar Item Masuk:</span>
            <button type="button" class="btn btn-sm py-0 px-2 text-danger" style="font-size: 0.75rem;" onclick="clearRestockCart()">Reset</button>
        </div>

        <!-- Items Scroll -->
        <div class="items-scroll" id="restockItemsList">
            <div class="text-center py-5" style="color: #8892b0;" id="emptyCartNotice">
                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                <span>Belum ada barang dipilih</span>
                <small class="d-block mt-1">Klik menu di sebelah kiri untuk menambah stok</small>
            </div>
        </div>

        <!-- Summary & Submit -->
        <div class="border-top pt-3" style="border-color: rgba(255,255,255,0.08) !important;">
            <div class="d-flex justify-content-between align-items-center mb-1" style="color: #8892b0; font-size: 0.85rem;">
                <span>Total Item Masuk:</span>
                <span class="text-white fw-bold" id="displayTotalQty">0 Unit</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="fw-bold text-white fs-6">Total Biaya Belanja:</span>
                <span class="fw-bold fs-4" style="color: #ea7c69;" id="displayTotalCost">Rp 0</span>
            </div>

            <button type="button" class="btn-submit-po" id="btnSubmitPo" onclick="submitRestockOrder()">
                <i class="bi bi-check2-circle fs-5"></i>
                <span>Simpan & Tambah Stok</span>
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let restockCart = {}; // { id: { id, name, code, cost_price, unit, icon, qty } }

    function addToRestock(id, name, code, costPrice, unit, icon) {
        if (restockCart[id]) {
            restockCart[id].qty += 10; // Default increment 10 pcs for restock
        } else {
            restockCart[id] = {
                id: id,
                name: name,
                code: code,
                cost_price: costPrice,
                unit: unit,
                icon: icon,
                qty: 10
            };
        }
        renderRestockCart();
    }

    function updateItemQty(id, newQty) {
        if (!restockCart[id]) return;
        newQty = parseInt(newQty) || 0;
        if (newQty <= 0) {
            delete restockCart[id];
        } else {
            restockCart[id].qty = newQty;
        }
        renderRestockCart();
    }

    function updateItemCost(id, newCost) {
        if (!restockCart[id]) return;
        restockCart[id].cost_price = parseInt(newCost) || 0;
        renderRestockCart();
    }

    function removeRestockItem(id) {
        delete restockCart[id];
        renderRestockCart();
    }

    function clearRestockCart() {
        restockCart = {};
        renderRestockCart();
    }

    function renderRestockCart() {
        const items = Object.values(restockCart);
        const listContainer = $('#restockItemsList');

        if (items.length === 0) {
            listContainer.html(`
                <div class="text-center py-5" style="color: #8892b0;" id="emptyCartNotice">
                    <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                    <span>Belum ada barang dipilih</span>
                    <small class="d-block mt-1">Klik menu di sebelah kiri untuk menambah stok</small>
                </div>
            `);
            $('#displayTotalQty').text('0 Unit');
            $('#displayTotalCost').text('Rp 0');
            return;
        }

        let html = '';
        let totalQty = 0;
        let totalCost = 0;

        items.forEach(item => {
            const subtotal = item.qty * item.cost_price;
            totalQty += item.qty;
            totalCost += subtotal;

            html += `
                <div class="cart-po-row">
                    <div class="row-header">
                        <div class="fw-bold text-white text-truncate" style="font-size: 0.85rem;" title="${item.name}">${item.icon} ${item.name}</div>
                        <button type="button" class="btn btn-sm py-0 text-danger border-0" onclick="removeRestockItem(${item.id})">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </div>
                    <div class="row-inputs">
                        <div style="flex: 1;">
                            <label class="small text-muted" style="font-size: 0.7rem;">Qty Masuk (${item.unit})</label>
                            <input type="number" class="form-control form-control-sm text-center fw-bold" style="background: #1f1d2b;" value="${item.qty}" min="1" onchange="updateItemQty(${item.id}, this.value)">
                        </div>
                        <div style="flex: 1.5;">
                            <label class="small text-muted" style="font-size: 0.7rem;">Harga Beli (Rp)</label>
                            <input type="number" class="form-control form-control-sm text-end" style="background: #1f1d2b;" value="${item.cost_price}" min="0" onchange="updateItemCost(${item.id}, this.value)">
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2 pt-1 border-top" style="border-color: rgba(255,255,255,0.05) !important; font-size: 0.75rem;">
                        <span style="color: #8892b0;">Subtotal:</span>
                        <span class="fw-bold text-white">Rp ${subtotal.toLocaleString('id-ID')}</span>
                    </div>
                </div>
            `;
        });

        listContainer.html(html);
        $('#displayTotalQty').text(totalQty.toLocaleString('id-ID') + ' Unit');
        $('#displayTotalCost').text('Rp ' + totalCost.toLocaleString('id-ID'));
    }

    // Filter by Category
    $('#categoryTabs').on('click', '.cat-tab-btn', function() {
        $('.cat-tab-btn').removeClass('active');
        $(this).addClass('active');
        const catId = $(this).data('category');

        $('.product-card-item').each(function() {
            if (catId === 'all' || $(this).data('category') == catId) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Search filter
    $('#searchInput').on('input', function() {
        const val = $(this).val().toLowerCase();
        $('.product-card-item').each(function() {
            const name = $(this).data('name');
            if (name.includes(val)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    function submitRestockOrder() {
        const items = Object.values(restockCart);
        if (items.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Daftar Kosong',
                text: 'Pilih minimal satu menu untuk ditambahkan stoknya.',
                background: '#252836',
                color: '#fff',
                confirmButtonColor: '#ea7c69'
            });
            return;
        }

        const supplierName = $('#supplierNameInput').val().trim();
        if (!supplierName) {
            Swal.fire({
                icon: 'warning',
                title: 'Supplier Belum Diisi',
                text: 'Mohon masukkan nama supplier / vendor pengirim.',
                background: '#252836',
                color: '#fff',
                confirmButtonColor: '#ea7c69'
            });
            $('#supplierNameInput').focus();
            return;
        }

        const payload = {
            supplier_name: supplierName,
            invoice_ref: $('#invoiceRefInput').val().trim(),
            notes: $('#notesInput').val().trim(),
            items: items.map(it => ({
                id: it.id,
                qty: it.qty,
                cost_price: it.cost_price
            }))
        };

        $('#btnSubmitPo').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan Data...');

        $.ajax({
            url: '{{ route("gudang.purchases.store") }}',
            type: 'POST',
            data: JSON.stringify(payload),
            contentType: 'application/json',
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: `Faktur ${res.purchase.purchase_code} berhasil diproses. Stok barang otomatis bertambah!`,
                        background: '#252836',
                        color: '#fff',
                        confirmButtonColor: '#ea7c69',
                        showCancelButton: true,
                        confirmButtonText: 'Cetak Bukti Terima',
                        cancelButtonText: 'Selesai'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.open(res.purchase.print_url, '_blank');
                        }
                        window.location.href = '{{ route("gudang.purchases.index") }}';
                    });
                }
            },
            error: function(xhr) {
                $('#btnSubmitPo').prop('disabled', false).html('<i class="bi bi-check2-circle fs-5"></i> <span>Simpan & Tambah Stok</span>');
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: (xhr.responseJSON && xhr.responseJSON.message) || 'Terjadi kesalahan sistem.',
                    background: '#252836',
                    color: '#fff',
                    confirmButtonColor: '#ea7c69'
                });
            }
        });
    }
</script>
@endsection
