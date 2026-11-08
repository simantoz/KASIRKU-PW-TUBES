@extends('layouts.app')

@section('title', 'KasirKu - Order')

@section('styles')
<style>
    body {
        background-color: #1f1d2b;
    }
    .page-content {
        padding: 1.5rem 1.75rem;
    }

    .pos-layout {
        display: flex;
        gap: 1.75rem;
        align-items: flex-start;
    }

    /* Left side: Catalog */
    .pos-main {
        flex: 1;
        min-width: 0;
    }

    /* Header in Main */
    .catalog-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .restaurant-title {
        font-size: 1.6rem;
        font-weight: 700;
        color: #ffffff;
        margin: 0;
    }

    .restaurant-date {
        font-size: 0.85rem;
        color: #8892b0;
        margin-top: 2px;
    }

    .search-box-wrap {
        position: relative;
        width: 280px;
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

    /* Category Navigation Tabs like Reference */
    .category-tabs {
        display: flex;
        gap: 0.85rem;
        overflow-x: auto;
        padding-bottom: 0.75rem;
        margin-bottom: 1.5rem;
        scrollbar-width: none;
    }
    .category-tabs::-webkit-scrollbar {
        display: none;
    }

    .cat-tab-btn {
        background: #252836;
        border: 1px solid rgba(255, 255, 255, 0.06);
        color: #abb2bf;
        padding: 0.65rem 1.25rem;
        border-radius: 14px;
        font-size: 0.88rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .cat-tab-btn .cat-emoji {
        font-size: 1.15rem;
    }

    .cat-tab-btn:hover {
        background: #2d303e;
        color: #fff;
        border-color: rgba(234, 124, 105, 0.4);
    }

    .cat-tab-btn.active {
        background: rgba(234, 124, 105, 0.15);
        color: #ea7c69;
        border: 1px solid #ea7c69;
        box-shadow: 0 4px 16px rgba(234, 124, 105, 0.25);
    }

    /* Product Card like Reference */
    .product-food-card {
        background: #252836;
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 18px;
        padding: 1.15rem;
        display: flex;
        flex-direction: column;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        position: relative;
    }

    .product-food-card:hover {
        transform: translateY(-4px);
        border-color: rgba(234, 124, 105, 0.5);
        box-shadow: 0 12px 24px -6px rgba(0, 0, 0, 0.4);
    }

    .product-food-card:active {
        transform: scale(0.98);
    }

    .food-img-container {
        width: 100%;
        height: 120px;
        border-radius: 14px;
        background: #2d303e;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.85rem;
        position: relative;
        overflow: hidden;
    }

    .food-img-container .food-icon-huge {
        font-size: 3.5rem;
        filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));
    }

    .food-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 0.35rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .food-price {
        font-size: 1.05rem;
        font-weight: 700;
        color: #ea7c69;
        margin-bottom: 0.2rem;
    }

    .food-stock {
        font-size: 0.78rem;
        color: #8892b0;
    }

    /* Right Order Panel like Reference */
    .pos-order-panel {
        width: 380px;
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

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.25rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .order-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #ffffff;
        margin: 0;
    }

    .order-subtitle {
        font-size: 0.82rem;
        color: #8892b0;
        margin-top: 2px;
    }

    .btn-addon {
        background: #2d303e;
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: #fff;
        padding: 0.45rem 0.85rem;
        border-radius: 10px;
        font-size: 0.78rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-addon:hover {
        background: #ea7c69;
        color: #fff;
        border-color: #ea7c69;
    }

    /* Cart Items in Order Panel */
    .order-items-scroll {
        flex: 1;
        overflow-y: auto;
        margin-right: -0.5rem;
        padding-right: 0.5rem;
        margin-bottom: 1rem;
        min-height: 180px;
    }

    .order-item-row {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .order-item-thumb {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: #2d303e;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }

    .order-item-info {
        flex: 1;
        min-width: 0;
    }

    .order-item-name {
        font-size: 0.85rem;
        font-weight: 600;
        color: #ffffff;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .order-item-note {
        font-size: 0.72rem;
        color: #8892b0;
    }

    .order-item-price {
        font-size: 0.88rem;
        font-weight: 700;
        color: #fff;
        text-align: right;
    }

    .qty-controls {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        background: #2d303e;
        border-radius: 8px;
        padding: 2px 4px;
    }

    .qty-mini-btn {
        width: 22px;
        height: 22px;
        border: none;
        background: transparent;
        color: #abb2bf;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        cursor: pointer;
        border-radius: 6px;
    }

    .qty-mini-btn:hover {
        background: rgba(234, 124, 105, 0.2);
        color: #ea7c69;
    }

    .qty-num {
        font-size: 0.8rem;
        font-weight: 700;
        color: #fff;
        min-width: 18px;
        text-align: center;
    }

    /* Summary Calculation Footer */
    .order-summary-box {
        border-top: 1px dashed rgba(255, 255, 255, 0.12);
        padding-top: 1rem;
    }

    .summary-line {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
        font-size: 0.85rem;
        color: #8892b0;
    }

    .summary-line.total-line {
        font-size: 1.1rem;
        font-weight: 700;
        color: #ffffff;
        margin-top: 0.75rem;
        padding-top: 0.75rem;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
    }

    .summary-line.total-line span:last-child {
        color: #ea7c69;
    }

    .btn-print-bills {
        background: #ea7c69;
        border: none;
        color: #ffffff;
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

    .btn-print-bills:hover {
        background: #e06550;
        box-shadow: 0 10px 28px rgba(234, 124, 105, 0.55);
        transform: translateY(-1px);
    }

    .btn-print-bills:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Modal Dark Override */
    .modal-content.dark-modal {
        background: #252836;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        color: #fff;
    }

    @media (max-width: 1199.98px) {
        .pos-layout {
            flex-direction: column;
        }
        .pos-order-panel {
            width: 100%;
            position: static;
            max-height: none;
        }
    }
</style>
@endsection

@section('content')
<div class="pos-layout">
    <!-- Left: Catalog & Filter -->
    <div class="pos-main">
        <!-- Top Title & Search like Reference -->
        <div class="catalog-header">
            <div>
                <h1 class="restaurant-title">KasirKu</h1>
                <div class="restaurant-date">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</div>
            </div>
            <div class="search-box-wrap">
                <i class="bi bi-search search-icon"></i>
                <input type="text" id="searchInput" placeholder="Search menu here..." autocomplete="off">
            </div>
        </div>

        <!-- Category Tabs like Reference (Burger, Noodles, Drinks, Desserts...) -->
        <div class="category-tabs" id="categoryTabs">
            <button class="cat-tab-btn active" data-category="all">
                <span class="cat-emoji">🍽️</span>
                <span>All Menu</span>
            </button>
            @foreach($categories as $cat)
                @php
                    $emoji = '🍔';
                    $catLower = strtolower($cat->name);
                    if (str_contains($catLower, 'mie') || str_contains($catLower, 'noodle')) $emoji = '🍜';
                    elseif (str_contains($catLower, 'minum') || str_contains($catLower, 'drink') || str_contains($catLower, 'beverage')) $emoji = '🥤';
                    elseif (str_contains($catLower, 'snack') || str_contains($catLower, 'dessert') || str_contains($catLower, 'kue')) $emoji = '🍨';
                    elseif (str_contains($catLower, 'nasi') || str_contains($catLower, 'rice')) $emoji = '🍚';
                @endphp
                <button class="cat-tab-btn" data-category="{{ $cat->id }}">
                    <span class="cat-emoji">{{ $emoji }}</span>
                    <span>{{ $cat->name }}</span>
                </button>
            @endforeach
        </div>

        <!-- Product Cards Grid -->
        <div class="row g-3" id="productsGrid">
            @foreach($products as $p)
                @php
                    $pNameLower = strtolower($p->name);
                    $icon = '🍔';
                    if (str_contains($pNameLower, 'mie') || str_contains($pNameLower, 'ramen') || str_contains($pNameLower, 'spaghetti')) $icon = '🍜';
                    elseif (str_contains($pNameLower, 'teh') || str_contains($pNameLower, 'kopi') || str_contains($pNameLower, 'air') || str_contains($pNameLower, 'jus') || str_contains($pNameLower, 'drink')) $icon = '🥤';
                    elseif (str_contains($pNameLower, 'chitato') || str_contains($pNameLower, 'snack') || str_contains($pNameLower, 'oreo') || str_contains($pNameLower, 'ice') || str_contains($pNameLower, 'dessert')) $icon = '🍨';
                    elseif (str_contains($pNameLower, 'ayam') || str_contains($pNameLower, 'chicken')) $icon = '🍗';
                    elseif (str_contains($pNameLower, 'pizza')) $icon = '🍕';
                @endphp
                <div class="col-xxl-3 col-xl-4 col-md-6 col-sm-6">
                    <div class="product-food-card" onclick="addToCart({{ $p->id }}, '{{ addslashes($p->name) }}', {{ $p->selling_price }}, {{ $p->stock }}, '{{ $p->code }}', '{{ $p->unit }}', '{{ $icon }}')">
                        <div class="food-img-container">
                            <span class="food-icon-huge">{{ $icon }}</span>
                        </div>
                        <div class="food-title" title="{{ $p->name }}">{{ $p->name }}</div>
                        <div class="food-price">Rp {{ number_format($p->selling_price, 0, ',', '.') }}</div>
                        <div class="food-stock">{{ $p->stock }} items available</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Right: Order Panel like Reference -->
    <div class="pos-order-panel">
        <div class="order-header">
            <div>
                <h2 class="order-title">Order</h2>
                <div class="order-subtitle" id="tableOrCustomer">Table 01 &bull; Kasir: {{ Auth::user()->name }}</div>
            </div>
            <button type="button" class="btn-addon" onclick="changeCustomerName()">
                <i class="bi bi-person-fill"></i>
                <span id="customerLabel">Pelanggan</span>
            </button>
        </div>

        <!-- Items Scroll -->
        <div class="order-items-scroll" id="cartItemsList">
            <div class="text-center py-5" style="color: #8892b0;" id="emptyNotice">
                <i class="bi bi-cart3 fs-1 d-block mb-2 opacity-50"></i>
                <span>No order items yet</span>
                <small class="d-block mt-1">Tap a menu item on the left to add</small>
            </div>
        </div>

        <!-- Summary & Print Bill -->
        <div class="order-summary-box">
            <!-- Payment Method Select -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span style="font-size: 0.8rem; color: #8892b0;">Payment:</span>
                <div class="btn-group btn-group-sm" role="group">
                    <input type="radio" class="btn-check" name="paymentMethod" id="payCash" value="cash" checked>
                    <label class="btn btn-outline-secondary py-1 px-2" for="payCash" style="border-color: rgba(255,255,255,0.1); color: #fff; font-size: 0.75rem;">Cash</label>

                    <input type="radio" class="btn-check" name="paymentMethod" id="payQris" value="qris">
                    <label class="btn btn-outline-secondary py-1 px-2" for="payQris" style="border-color: rgba(255,255,255,0.1); color: #fff; font-size: 0.75rem;">QRIS</label>

                    <input type="radio" class="btn-check" name="paymentMethod" id="payTransfer" value="transfer">
                    <label class="btn btn-outline-secondary py-1 px-2" for="payTransfer" style="border-color: rgba(255,255,255,0.1); color: #fff; font-size: 0.75rem;">Transfer</label>
                </div>
            </div>

            <!-- Cash Tender (Only when Cash) -->
            <div id="cashInputRow" class="mb-2">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span style="font-size: 0.8rem; color: #8892b0;">Cash Paid:</span>
                    <input type="number" id="cashPaidInput" class="form-control form-control-sm text-end" style="width: 140px; background: #2d303e; border-color: rgba(255,255,255,0.1); color: #34d399; font-weight: 700;" placeholder="0">
                </div>
                <div class="d-flex justify-content-end gap-1 mb-2">
                    <button type="button" class="btn btn-sm py-0 px-2 text-white" style="background: #2d303e; font-size: 0.7rem; border-radius: 6px;" onclick="setExactAmount()">Exact</button>
                    <button type="button" class="btn btn-sm py-0 px-2 text-white" style="background: #2d303e; font-size: 0.7rem; border-radius: 6px;" onclick="addCash(20000)">+20k</button>
                    <button type="button" class="btn btn-sm py-0 px-2 text-white" style="background: #2d303e; font-size: 0.7rem; border-radius: 6px;" onclick="addCash(50000)">+50k</button>
                    <button type="button" class="btn btn-sm py-0 px-2 text-white" style="background: #2d303e; font-size: 0.7rem; border-radius: 6px;" onclick="addCash(100000)">+100k</button>
                </div>
                <div class="d-flex justify-content-between align-items-center" style="font-size: 0.8rem; color: #8892b0;">
                    <span>Kembalian:</span>
                    <span class="fw-bold" id="displayChange" style="color: #34d399;">Rp 0</span>
                </div>
            </div>

            <div class="summary-line">
                <span>Sub Total</span>
                <span id="displaySubtotal">Rp 0</span>
            </div>
            <div class="summary-line">
                <span>Tax (0%)</span>
                <span>Rp 0</span>
            </div>
            <div class="summary-line total-line">
                <span>Total</span>
                <span id="displayTotal">Rp 0</span>
            </div>

            <button type="button" class="btn-print-bills" id="checkoutBtn" onclick="processCheckout()">
                <i class="bi bi-printer-fill"></i>
                <span>Print bills</span>
            </button>
        </div>
    </div>
</div>

<!-- Receipt Modal Popup on Success -->
<div class="modal fade" id="receiptModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content dark-modal shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold text-white d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                    <span>Order Success!</span>
                </h6>
            </div>
            <div class="modal-body p-3 font-monospace" style="font-size: 0.8rem; color: #abb2bf;" id="receiptThermalPreview">
            </div>
            <div class="modal-footer border-0 pt-0 d-flex justify-content-between">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" id="printDirectLink" target="_blank" class="btn btn-sm btn-primary-custom">
                    <i class="bi bi-printer"></i> Print Receipt
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let cart = {};
    let currentCategoryId = 'all';
    let customerName = 'Pelanggan Umum';

    function changeCustomerName() {
        Swal.fire({
            title: 'Customer Name / Table',
            input: 'text',
            inputValue: customerName,
            background: '#252836',
            color: '#fff',
            confirmButtonColor: '#ea7c69',
            showCancelButton: true
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                customerName = result.value;
                $('#customerLabel').text(customerName);
                $('#tableOrCustomer').text(customerName + ' • Kasir: {{ Auth::user()->name }}');
            }
        });
    }

    function addToCart(id, name, price, stock, code, unit, icon) {
        if (stock <= 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Out of Stock',
                text: `${name} is currently out of stock.`,
                background: '#252836',
                color: '#fff',
                confirmButtonColor: '#ea7c69'
            });
            return;
        }

        if (cart[id]) {
            if (cart[id].qty + 1 > stock) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Stock Limit',
                    text: `Only ${stock} available.`,
                    background: '#252836',
                    color: '#fff',
                    confirmButtonColor: '#ea7c69'
                });
                return;
            }
            cart[id].qty += 1;
        } else {
            cart[id] = { id, name, price, stock, code, unit, icon: icon || '🍔', qty: 1 };
        }
        renderCart();
    }

    function updateQty(id, delta) {
        if (!cart[id]) return;
        const newQty = cart[id].qty + delta;
        if (newQty <= 0) {
            delete cart[id];
        } else if (newQty > cart[id].stock) {
            Swal.fire({
                icon: 'warning',
                title: 'Stock Limit',
                text: `Only ${cart[id].stock} available.`,
                background: '#252836',
                color: '#fff',
                confirmButtonColor: '#ea7c69'
            });
            return;
        } else {
            cart[id].qty = newQty;
        }
        renderCart();
    }

    function removeFromCart(id) {
        delete cart[id];
        renderCart();
    }

    function renderCart() {
        const items = Object.values(cart);
        const cartList = $('#cartItemsList');

        if (items.length === 0) {
            cartList.html(`
                <div class="text-center py-5" style="color: #8892b0;" id="emptyNotice">
                    <i class="bi bi-cart3 fs-1 d-block mb-2 opacity-50"></i>
                    <span>No order items yet</span>
                    <small class="d-block mt-1">Tap a menu item on the left to add</small>
                </div>
            `);
            $('#displaySubtotal').text('Rp 0');
            $('#displayTotal').text('Rp 0');
            $('#displayChange').text('Rp 0');
            return;
        }

        let html = '';
        let subtotal = 0;

        items.forEach(item => {
            const itemTotal = item.price * item.qty;
            subtotal += itemTotal;
            html += `
                <div class="order-item-row">
                    <div class="order-item-thumb">${item.icon}</div>
                    <div class="order-item-info">
                        <div class="order-item-name" title="${item.name}">${item.name}</div>
                        <div class="order-item-note">Rp ${item.price.toLocaleString('id-ID')}</div>
                    </div>
                    <div class="qty-controls">
                        <button type="button" class="qty-mini-btn" onclick="updateQty(${item.id}, -1)">-</button>
                        <span class="qty-num">${item.qty}</span>
                        <button type="button" class="qty-mini-btn" onclick="updateQty(${item.id}, 1)">+</button>
                    </div>
                    <div class="order-item-price">
                        Rp ${itemTotal.toLocaleString('id-ID')}
                    </div>
                </div>
            `;
        });

        cartList.html(html);
        $('#displaySubtotal').text('Rp ' + subtotal.toLocaleString('id-ID'));
        $('#displayTotal').text('Rp ' + subtotal.toLocaleString('id-ID'));
        calcChange();
    }

    $('input[name="paymentMethod"]').on('change', function() {
        if ($(this).val() === 'cash') {
            $('#cashInputRow').slideDown();
        } else {
            $('#cashInputRow').slideUp();
        }
    });

    $('#cashPaidInput').on('input', function() {
        calcChange();
    });

    function getCartTotal() {
        return Object.values(cart).reduce((sum, it) => sum + (it.price * it.qty), 0);
    }

    function calcChange() {
        const total = getCartTotal();
        const paid = parseFloat($('#cashPaidInput').val()) || 0;
        const change = paid - total;
        if (change >= 0) {
            $('#displayChange').text('Rp ' + change.toLocaleString('id-ID')).css('color', '#34d399');
        } else {
            $('#displayChange').text('-Rp ' + Math.abs(change).toLocaleString('id-ID')).css('color', '#f87171');
        }
    }

    function setExactAmount() {
        $('#cashPaidInput').val(getCartTotal());
        calcChange();
    }

    function addCash(amount) {
        const cur = parseFloat($('#cashPaidInput').val()) || 0;
        $('#cashPaidInput').val(cur + amount);
        calcChange();
    }

    // Filter by Category Tabs
    $('#categoryTabs').on('click', '.cat-tab-btn', function() {
        $('.cat-tab-btn').removeClass('active');
        $(this).addClass('active');
        currentCategoryId = $(this).data('category');
        fetchFilteredProducts();
    });

    // Search Input
    let timer = null;
    $('#searchInput').on('input', function() {
        clearTimeout(timer);
        timer = setTimeout(() => {
            fetchFilteredProducts();
        }, 250);
    });

    function fetchFilteredProducts() {
        const keyword = $('#searchInput').val();
        $.ajax({
            url: '{{ route("kasir.api.products") }}',
            type: 'GET',
            data: { search: keyword, category_id: currentCategoryId },
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
                <div class="col-12 text-center py-5" style="color: #8892b0;">
                    <i class="bi bi-search fs-1 d-block mb-2 opacity-50"></i>
                    <span>No menu items match your search.</span>
                </div>
            `);
            return;
        }

        let html = '';
        products.forEach(p => {
            let icon = '🍔';
            const nameLower = p.name.toLowerCase();
            if (nameLower.includes('mie') || nameLower.includes('ramen')) icon = '🍜';
            else if (nameLower.includes('teh') || nameLower.includes('kopi') || nameLower.includes('air') || nameLower.includes('jus')) icon = '🥤';
            else if (nameLower.includes('chitato') || nameLower.includes('snack') || nameLower.includes('oreo') || nameLower.includes('dessert')) icon = '🍨';
            else if (nameLower.includes('ayam') || nameLower.includes('chicken')) icon = '🍗';
            else if (nameLower.includes('pizza')) icon = '🍕';

            html += `
                <div class="col-xxl-3 col-xl-4 col-md-6 col-sm-6">
                    <div class="product-food-card" onclick="addToCart(${p.id}, '${p.name.replace(/'/g, "\\'")}', ${p.selling_price}, ${p.stock}, '${p.code}', '${p.unit}', '${icon}')">
                        <div class="food-img-container">
                            <span class="food-icon-huge">${icon}</span>
                        </div>
                        <div class="food-title" title="${p.name}">${p.name}</div>
                        <div class="food-price">${p.formatted_price}</div>
                        <div class="food-stock">${p.stock} items available</div>
                    </div>
                </div>
            `;
        });
        grid.html(html);
    }

    function processCheckout() {
        const items = Object.values(cart);
        if (items.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Empty Order',
                text: 'Select at least one menu item.',
                background: '#252836',
                color: '#fff',
                confirmButtonColor: '#ea7c69'
            });
            return;
        }

        const total = getCartTotal();
        const paymentMethod = $('input[name="paymentMethod"]:checked').val();
        let paidAmount = parseFloat($('#cashPaidInput').val()) || 0;

        if (paymentMethod === 'cash' && paidAmount < total) {
            Swal.fire({
                icon: 'warning',
                title: 'Insufficient Cash',
                text: `Total is Rp ${total.toLocaleString('id-ID')}. Paid amount is less.`,
                background: '#252836',
                color: '#fff',
                confirmButtonColor: '#ea7c69'
            });
            return;
        }
        if (paymentMethod !== 'cash') paidAmount = total;

        const payload = {
            customer_name: customerName,
            payment_method: paymentMethod,
            paid_amount: paidAmount,
            items: items.map(it => ({ id: it.id, qty: it.qty }))
        };

        $('#checkoutBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Processing...');

        $.ajax({
            url: '{{ route("kasir.checkout") }}',
            type: 'POST',
            data: JSON.stringify(payload),
            contentType: 'application/json',
            dataType: 'json',
            success: function(res) {
                $('#checkoutBtn').prop('disabled', false).html('<i class="bi bi-printer-fill"></i> <span>Print bills</span>');
                if (res.success) {
                    const trx = res.transaction;
                    let receiptHtml = `
                        <div class="text-center pb-2 border-bottom border-secondary">
                            <strong class="text-white fs-6">KasirKu</strong><br>
                            <small class="text-muted">${trx.date}</small>
                        </div>
                        <div class="py-2 border-bottom border-secondary small">
                            <div>Invoice: <strong class="text-white">${trx.invoice_code}</strong></div>
                            <div>Kasir: ${trx.cashier}</div>
                            <div>Customer: ${trx.customer}</div>
                        </div>
                        <div class="py-2 border-bottom border-secondary">
                    `;
                    trx.items.forEach(it => {
                        receiptHtml += `
                            <div class="d-flex justify-content-between">
                                <span>${it.product_name} x${it.qty}</span>
                                <span class="text-white">Rp ${it.subtotal.toLocaleString('id-ID')}</span>
                            </div>
                        `;
                    });
                    receiptHtml += `
                        </div>
                        <div class="pt-2 small">
                            <div class="d-flex justify-content-between fw-bold text-white">
                                <span>TOTAL:</span>
                                <span>Rp ${trx.total.toLocaleString('id-ID')}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Paid:</span>
                                <span>Rp ${trx.paid.toLocaleString('id-ID')}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Change:</span>
                                <span>Rp ${trx.change.toLocaleString('id-ID')}</span>
                            </div>
                        </div>
                    `;
                    $('#receiptThermalPreview').html(receiptHtml);
                    $('#printDirectLink').attr('href', trx.print_url);

                    const modal = new bootstrap.Modal(document.getElementById('receiptModal'));
                    modal.show();

                    cart = {};
                    renderCart();
                    fetchFilteredProducts();
                }
            },
            error: function(xhr) {
                $('#checkoutBtn').prop('disabled', false).html('<i class="bi bi-printer-fill"></i> <span>Print bills</span>');
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: (xhr.responseJSON && xhr.responseJSON.message) || 'Transaction failed',
                    background: '#252836',
                    color: '#fff',
                    confirmButtonColor: '#ea7c69'
                });
            }
        });
    }
</script>
@endsection
