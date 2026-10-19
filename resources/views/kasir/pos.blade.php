@extends('layouts.app')

@section('title', 'Transaksi Kasir POS')
@section('page_title', 'Point of Sales (Kasir)')

@section('styles')
<style>
    .pos-container {
        display: flex;
        gap: 1.25rem;
        align-items: flex-start;
    }

    .pos-catalog {
        flex: 1 1 65%;
        min-width: 0;
    }

    .pos-cart {
        flex: 0 0 35%;
        position: sticky;
        top: 80px;
    }

    /* Product Card */
    .product-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.85rem;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .product-card:hover {
        border-color: #2563eb;
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.1);
    }

    .product-card:active {
        transform: scale(0.98);
    }

    .product-icon-wrap {
        height: 70px;
        border-radius: 8px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #64748b;
        margin-bottom: 0.6rem;
    }

    /* Category Pill Filter */
    .category-pills {
        display: flex;
        gap: 0.5rem;
        overflow-x: auto;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
        scrollbar-width: thin;
    }

    .category-btn {
        white-space: nowrap;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #475569;
        padding: 0.45rem 1rem;
        border-radius: 9999px;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .category-btn.active, .category-btn:hover {
        background: #2563eb;
        color: #fff;
        border-color: #2563eb;
    }

    /* Cart Styles */
    .cart-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        max-height: calc(100vh - 100px);
    }

    .cart-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .cart-items-wrapper {
        flex-grow: 1;
        overflow-y: auto;
        padding: 1rem 1.25rem;
        max-height: 280px;
    }

    .cart-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.65rem 0;
        border-bottom: 1px dashed #e2e8f0;
    }

    .qty-btn {
        width: 26px;
        height: 26px;
        border-radius: 6px;
        border: 1px solid #cbd5e1;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.15s;
    }

    .qty-btn:hover {
        background: #f1f5f9;
        border-color: #94a3b8;
    }

    .denom-btn {
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        border-radius: 6px;
        padding: 0.35rem 0.6rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: #334155;
        cursor: pointer;
        transition: all 0.15s;
    }

    .denom-btn:hover {
        background: #e2e8f0;
    }

    @media (max-width: 991.98px) {
        .pos-container {
            flex-direction: column;
        }
        .pos-cart {
            position: static;
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="pos-container">
    <!-- Left Catalog Column -->
    <div class="pos-catalog">
        <!-- Search & Filter Card -->
        <div class="card card-custom p-3 mb-3 border-0 shadow-sm bg-white">
            <div class="row g-2">
                <div class="col-12">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Ketik nama produk atau scan barcode..." autofocus autocomplete="off">
                        <button class="btn btn-outline-secondary" type="button" id="clearSearchBtn">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Pills Bar -->
        <div class="category-pills" id="categoryPills">
            <button class="category-btn active" data-category="all">Semua Kategori</button>
            @foreach($categories as $cat)
                <button class="category-btn" data-category="{{ $cat->id }}">{{ $cat->name }}</button>
            @endforeach
        </div>

        <!-- Product Grid -->
        <div class="row g-2" id="productsGrid">
            <!-- Rendered by AJAX & Blade Initial Data -->
            @foreach($products as $p)
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="product-card" onclick="addToCart({{ $p->id }}, '{{ addslashes($p->name) }}', {{ $p->selling_price }}, {{ $p->stock }}, '{{ $p->code }}', '{{ $p->unit }}')">
                        <div class="product-icon-wrap">
                            @if(str_contains(strtolower($p->name), 'mie') || str_contains(strtolower($p->name), 'makan'))
                                <i class="bi bi-egg-fried"></i>
                            @elseif(str_contains(strtolower($p->name), 'teh') || str_contains(strtolower($p->name), 'air') || str_contains(strtolower($p->name), 'minerale') || str_contains(strtolower($p->name), 'milk'))
                                <i class="bi bi-cup-straw"></i>
                            @elseif(str_contains(strtolower($p->name), 'chitato') || str_contains(strtolower($p->name), 'oreo') || str_contains(strtolower($p->name), 'snack') || str_contains(strtolower($p->name), 'silverqueen'))
                                <i class="bi bi-cookie"></i>
                            @elseif(str_contains(strtolower($p->name), 'sabun') || str_contains(strtolower($p->name), 'pepsodent'))
                                <i class="bi bi-droplet"></i>
                            @else
                                <i class="bi bi-bag"></i>
                            @endif
                        </div>
                        <div>
                            <span class="badge bg-light text-muted font-monospace border mb-1" style="font-size: 0.68rem;">{{ $p->code }}</span>
                            <div class="fw-bold text-dark text-truncate" title="{{ $p->name }}" style="font-size: 0.88rem;">{{ $p->name }}</div>
                            <div class="d-flex align-items-center justify-content-between mt-2">
                                <span class="fw-bold text-primary" style="font-size: 0.92rem;">Rp {{ number_format($p->selling_price, 0, ',', '.') }}</span>
                                <span class="badge {{ $p->stock <= $p->min_stock ? 'bg-danger' : 'bg-success-subtle text-success' }}" style="font-size: 0.72rem;">
                                    Stok: {{ $p->stock }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Right Cart & Payment Column -->
    <div class="pos-cart">
        <div class="cart-card">
            <div class="cart-header">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-cart3 fs-5 text-primary"></i>
                    <h6 class="fw-bold mb-0 text-dark">Keranjang Belanja</h6>
                    <span class="badge bg-primary rounded-pill" id="cartItemCount">0</span>
                </div>
                <button type="button" class="btn btn-outline-danger btn-sm py-0 px-2" onclick="clearCart()" title="Hapus semua barang">
                    <i class="bi bi-trash"></i> Reset
                </button>
            </div>

            <!-- Customer Name Input -->
            <div class="px-3 pt-2">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                    <input type="text" id="customerNameInput" class="form-control border-start-0" placeholder="Nama Pelanggan (Default: Umum)" value="Pelanggan Umum">
                </div>
            </div>

            <!-- Cart Items List -->
            <div class="cart-items-wrapper" id="cartItemsList">
                <div class="text-center text-muted py-5" id="emptyCartNotice">
                    <i class="bi bi-basket3 fs-1 text-secondary opacity-50 d-block mb-2"></i>
                    <span>Keranjang belanja masih kosong.</span>
                    <small class="d-block text-muted">Klik produk pada katalog di sebelah kiri untuk menambahkan.</small>
                </div>
            </div>

            <!-- Calculation & Payment Footer -->
            <div class="p-3 bg-light border-top">
                <div class="d-flex justify-content-between align-items-center mb-1 text-muted small">
                    <span>Subtotal:</span>
                    <span class="fw-semibold text-dark" id="displaySubtotal">Rp 0</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-bold text-dark fs-6">Total Tagihan:</span>
                    <span class="fw-bold text-primary fs-4" id="displayTotal">Rp 0</span>
                </div>

                <!-- Payment Method -->
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted mb-1">Metode Pembayaran</label>
                    <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check" name="paymentMethod" id="payCash" value="cash" checked autocomplete="off">
                        <label class="btn btn-outline-primary btn-sm py-1 fw-semibold" for="payCash">
                            <i class="bi bi-cash"></i> Tunai
                        </label>

                        <input type="radio" class="btn-check" name="paymentMethod" id="payQris" value="qris" autocomplete="off">
                        <label class="btn btn-outline-primary btn-sm py-1 fw-semibold" for="payQris">
                            <i class="bi bi-qr-code-scan"></i> QRIS
                        </label>

                        <input type="radio" class="btn-check" name="paymentMethod" id="payTransfer" value="transfer" autocomplete="off">
                        <label class="btn btn-outline-primary btn-sm py-1 fw-semibold" for="payTransfer">
                            <i class="bi bi-bank"></i> Transfer
                        </label>
                    </div>
                </div>

                <!-- Cash Tender Input Section (Shown when Cash selected) -->
                <div id="cashInputSection" class="mb-3">
                    <label class="form-label fw-semibold small text-muted mb-1">Uang Diterima (Rp)</label>
                    <input type="number" id="cashPaidInput" class="form-control form-control-lg fw-bold text-success" placeholder="0" min="0">

                    <!-- Quick Denomination Buttons -->
                    <div class="d-flex gap-1 mt-2 flex-wrap">
                        <button type="button" class="denom-btn" onclick="setExactAmount()">Uang Pas</button>
                        <button type="button" class="denom-btn" onclick="addCash(10000)">+10k</button>
                        <button type="button" class="denom-btn" onclick="addCash(20000)">+20k</button>
                        <button type="button" class="denom-btn" onclick="addCash(50000)">+50k</button>
                        <button type="button" class="denom-btn" onclick="addCash(100000)">+100k</button>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                        <span class="small fw-semibold text-muted">Kembalian:</span>
                        <span class="fw-bold fs-6 text-dark" id="displayChange">Rp 0</span>
                    </div>
                </div>

                <!-- Checkout Submit Button -->
                <button type="button" class="btn btn-success btn-lg w-100 fw-bold d-flex align-items-center justify-content-center gap-2" id="checkoutBtn" onclick="processCheckout()">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>Bayar & Cetak Struk</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Receipt Modal Popup on Success -->
<div class="modal fade" id="receiptModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white py-2">
                <h6 class="modal-title fw-bold">
                    <i class="bi bi-check2-circle me-1"></i> Transaksi Berhasil!
                </h6>
            </div>
            <div class="modal-body p-3 font-monospace" style="font-size: 0.8rem;" id="receiptThermalPreview">
                <!-- Populated by JS -->
            </div>
            <div class="modal-footer bg-light p-2 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Transaksi Baru</button>
                <a href="#" id="printDirectLink" target="_blank" class="btn btn-primary btn-sm fw-semibold">
                    <i class="bi bi-printer"></i> Cetak Struk
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // State management for POS
    let cart = {}; // { productId: { id, name, price, stock, code, unit, qty } }
    let currentCategoryId = 'all';

    // 1. Add to Cart with stock check
    function addToCart(id, name, price, stock, code, unit) {
        if (stock <= 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Stok Habis!',
                text: `Produk "${name}" saat ini sedang kosong di gudang.`,
                timer: 1500,
                showConfirmButton: false
            });
            return;
        }

        if (cart[id]) {
            if (cart[id].qty + 1 > stock) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Batas Stok Tercapai',
                    text: `Hanya tersedia ${stock} ${unit} untuk produk ini.`,
                    timer: 1500,
                    showConfirmButton: false
                });
                return;
            }
            cart[id].qty += 1;
        } else {
            cart[id] = {
                id: id,
                name: name,
                price: price,
                stock: stock,
                code: code,
                unit: unit,
                qty: 1
            };
        }

        renderCart();
    }

    // 2. Adjust Qty
    function updateQty(id, delta) {
        if (!cart[id]) return;

        const newQty = cart[id].qty + delta;
        if (newQty <= 0) {
            delete cart[id];
        } else if (newQty > cart[id].stock) {
            Swal.fire({
                icon: 'warning',
                title: 'Batas Stok Tercapai',
                text: `Hanya tersedia ${cart[id].stock} ${cart[id].unit} untuk produk ini.`,
                timer: 1500,
                showConfirmButton: false
            });
            return;
        } else {
            cart[id].qty = newQty;
        }

        renderCart();
    }

    // 3. Remove Item
    function removeFromCart(id) {
        delete cart[id];
        renderCart();
    }

    // 4. Clear Cart
    function clearCart() {
        cart = {};
        renderCart();
    }

    // 5. Render Cart UI
    function renderCart() {
        const items = Object.values(cart);
        const cartList = $('#cartItemsList');
        const itemCount = $('#cartItemCount');

        let totalQty = 0;
        let subtotal = 0;

        if (items.length === 0) {
            cartList.html(`
                <div class="text-center text-muted py-5" id="emptyCartNotice">
                    <i class="bi bi-basket3 fs-1 text-secondary opacity-50 d-block mb-2"></i>
                    <span>Keranjang belanja masih kosong.</span>
                    <small class="d-block text-muted">Klik produk pada katalog di sebelah kiri untuk menambahkan.</small>
                </div>
            `);
            itemCount.text('0');
            $('#displaySubtotal').text('Rp 0');
            $('#displayTotal').text('Rp 0');
            $('#cashPaidInput').val('');
            $('#displayChange').text('Rp 0');
            return;
        }

        let html = '';
        items.forEach(item => {
            const itemTotal = item.price * item.qty;
            totalQty += item.qty;
            subtotal += itemTotal;

            html += `
                <div class="cart-item">
                    <div style="flex: 1; min-width: 0; padding-right: 0.5rem;">
                        <div class="fw-bold text-dark text-truncate" style="font-size: 0.85rem;" title="${item.name}">${item.name}</div>
                        <div class="text-muted" style="font-size: 0.75rem;">Rp ${item.price.toLocaleString('id-ID')} / ${item.unit}</div>
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        <button type="button" class="qty-btn" onclick="updateQty(${item.id}, -1)">-</button>
                        <span class="fw-bold text-dark text-center px-1" style="min-width: 24px; font-size: 0.85rem;">${item.qty}</span>
                        <button type="button" class="qty-btn" onclick="updateQty(${item.id}, 1)">+</button>
                    </div>
                    <div class="text-end ps-2" style="min-width: 80px;">
                        <div class="fw-bold text-dark" style="font-size: 0.85rem;">Rp ${itemTotal.toLocaleString('id-ID')}</div>
                        <a href="javascript:void(0)" class="text-danger small text-decoration-none" onclick="removeFromCart(${item.id})">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    </div>
                </div>
            `;
        });

        cartList.html(html);
        itemCount.text(totalQty);
        $('#displaySubtotal').text('Rp ' + subtotal.toLocaleString('id-ID'));
        $('#displayTotal').text('Rp ' + subtotal.toLocaleString('id-ID'));

        calcChange();
    }

    // 6. Payment Method & Cash Calculations
    $('input[name="paymentMethod"]').on('change', function() {
        const method = $(this).val();
        if (method === 'cash') {
            $('#cashInputSection').slideDown();
        } else {
            $('#cashInputSection').slideUp();
        }
    });

    $('#cashPaidInput').on('input', function() {
        calcChange();
    });

    function getCartTotal() {
        return Object.values(cart).reduce((sum, item) => sum + (item.price * item.qty), 0);
    }

    function calcChange() {
        const total = getCartTotal();
        const paid = parseFloat($('#cashPaidInput').val()) || 0;
        const change = paid - total;

        if (change >= 0) {
            $('#displayChange').text('Rp ' + change.toLocaleString('id-ID')).removeClass('text-danger').addClass('text-success');
        } else {
            $('#displayChange').text('Kurang Rp ' + Math.abs(change).toLocaleString('id-ID')).removeClass('text-success').addClass('text-danger');
        }
    }

    function setExactAmount() {
        const total = getCartTotal();
        $('#cashPaidInput').val(total);
        calcChange();
    }

    function addCash(amount) {
        const current = parseFloat($('#cashPaidInput').val()) || 0;
        $('#cashPaidInput').val(current + amount);
        calcChange();
    }

    // 7. AJAX Live Product Search & Category Filter
    let searchTimer = null;

    $('#searchInput').on('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            fetchFilteredProducts();
        }, 300);
    });

    $('#clearSearchBtn').on('click', function() {
        $('#searchInput').val('');
        fetchFilteredProducts();
    });

    $('#categoryPills').on('click', '.category-btn', function() {
        $('.category-btn').removeClass('active');
        $(this).addClass('active');
        currentCategoryId = $(this).data('category');
        fetchFilteredProducts();
    });

    function fetchFilteredProducts() {
        const keyword = $('#searchInput').val();

        // AJAX GET request to Kasir API
        $.ajax({
            url: '{{ route("kasir.api.products") }}',
            type: 'GET',
            data: {
                search: keyword,
                category_id: currentCategoryId
            },
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    renderProductsGrid(res.products);
                }
            }
        });
    }

    function renderProductsGrid(products) {
        const grid = $('#productsGrid');
        if (products.length === 0) {
            grid.html(`
                <div class="col-12 text-center text-muted py-5">
                    <i class="bi bi-search fs-1 text-secondary opacity-50 d-block mb-2"></i>
                    <span>Tidak ada produk yang cocok dengan pencarian Anda.</span>
                </div>
            `);
            return;
        }

        let html = '';
        products.forEach(p => {
            let iconClass = 'bi bi-bag';
            const nameLower = p.name.toLowerCase();
            if (nameLower.includes('mie') || nameLower.includes('makan')) iconClass = 'bi bi-egg-fried';
            else if (nameLower.includes('teh') || nameLower.includes('air') || nameLower.includes('minerale') || nameLower.includes('milk')) iconClass = 'bi bi-cup-straw';
            else if (nameLower.includes('chitato') || nameLower.includes('oreo') || nameLower.includes('snack') || nameLower.includes('silverqueen')) iconClass = 'bi bi-cookie';
            else if (nameLower.includes('sabun') || nameLower.includes('pepsodent')) iconClass = 'bi bi-droplet';

            const badgeClass = p.is_low ? 'bg-danger' : 'bg-success-subtle text-success';

            html += `
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="product-card" onclick="addToCart(${p.id}, '${p.name.replace(/'/g, "\\'")}', ${p.selling_price}, ${p.stock}, '${p.code}', '${p.unit}')">
                        <div class="product-icon-wrap">
                            <i class="${iconClass}"></i>
                        </div>
                        <div>
                            <span class="badge bg-light text-muted font-monospace border mb-1" style="font-size: 0.68rem;">${p.code}</span>
                            <div class="fw-bold text-dark text-truncate" title="${p.name}" style="font-size: 0.88rem;">${p.name}</div>
                            <div class="d-flex align-items-center justify-content-between mt-2">
                                <span class="fw-bold text-primary" style="font-size: 0.92rem;">${p.formatted_price}</span>
                                <span class="badge ${badgeClass}" style="font-size: 0.72rem;">
                                    Stok: ${p.stock}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        grid.html(html);
    }

    // 8. Process Checkout (AJAX POST)
    function processCheckout() {
        const items = Object.values(cart);
        if (items.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Keranjang Kosong',
                text: 'Pilih minimal satu barang untuk bertransaksi.'
            });
            return;
        }

        const total = getCartTotal();
        const paymentMethod = $('input[name="paymentMethod"]:checked').val();
        let paidAmount = parseFloat($('#cashPaidInput').val()) || 0;

        if (paymentMethod === 'cash') {
            if (paidAmount < total) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Uang Kurang',
                    text: `Total tagihan adalah Rp ${total.toLocaleString('id-ID')}. Uang yang diterima kurang!`
                });
                return;
            }
        } else {
            paidAmount = total; // non-cash
        }

        const payload = {
            customer_name: $('#customerNameInput').val(),
            payment_method: paymentMethod,
            paid_amount: paidAmount,
            items: items.map(item => ({ id: item.id, qty: item.qty }))
        };

        $('#checkoutBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Memproses Transaksi...');

        // AJAX POST to checkout route
        $.ajax({
            url: '{{ route("kasir.checkout") }}',
            type: 'POST',
            data: JSON.stringify(payload),
            contentType: 'application/json',
            dataType: 'json',
            success: function(res) {
                $('#checkoutBtn').prop('disabled', false).html('<i class="bi bi-check-circle-fill"></i> <span>Bayar & Cetak Struk</span>');

                if (res.success) {
                    const trx = res.transaction;

                    // Build thermal receipt preview
                    let receiptHtml = `
                        <div class="text-center pb-2 border-bottom">
                            <strong class="fs-6">KASIRKU POS</strong><br>
                            <small class="text-muted">Jl. Pemrograman Web Telkom No. 1</small><br>
                            <small class="text-muted">${trx.date}</small>
                        </div>
                        <div class="py-2 border-bottom small">
                            <div>No: <strong>${trx.invoice_code}</strong></div>
                            <div>Kasir: ${trx.cashier}</div>
                            <div>Plg: ${trx.customer}</div>
                        </div>
                        <div class="py-2 border-bottom">
                    `;

                    trx.items.forEach(it => {
                        receiptHtml += `
                            <div class="d-flex justify-content-between">
                                <span>${it.product_name} x${it.qty}</span>
                                <span>Rp ${it.subtotal.toLocaleString('id-ID')}</span>
                            </div>
                        `;
                    });

                    receiptHtml += `
                        </div>
                        <div class="pt-2 small">
                            <div class="d-flex justify-content-between fw-bold">
                                <span>TOTAL:</span>
                                <span>Rp ${trx.total.toLocaleString('id-ID')}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Metode:</span>
                                <span>${trx.payment_method}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Bayar:</span>
                                <span>Rp ${trx.paid.toLocaleString('id-ID')}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Kembali:</span>
                                <span>Rp ${trx.change.toLocaleString('id-ID')}</span>
                            </div>
                        </div>
                        <div class="text-center pt-3 text-muted small">
                            *** Terima Kasih ***
                        </div>
                    `;

                    $('#receiptThermalPreview').html(receiptHtml);
                    $('#printDirectLink').attr('href', trx.print_url);

                    // Show Modal
                    const modal = new bootstrap.Modal(document.getElementById('receiptModal'));
                    modal.show();

                    // Reset cart and refresh catalog stocks
                    clearCart();
                    fetchFilteredProducts();
                }
            },
            error: function(xhr) {
                $('#checkoutBtn').prop('disabled', false).html('<i class="bi bi-check-circle-fill"></i> <span>Bayar & Cetak Struk</span>');

                let errMsg = 'Terjadi kesalahan saat memproses transaksi.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errMsg = xhr.responseJSON.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Transaksi Gagal',
                    text: errMsg
                });
            }
        });
    }
</script>
@endsection
