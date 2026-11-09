// ========================================
// KASIRKU - KASIR POS LOGIC
// ========================================

let cart = [];
let products = [];

// Initialize kasir dashboard
function initKasirDashboard() {
  checkRole('kasir');
  displayUserName();
  loadData();
  loadCart();
  renderProducts();
  renderCart();
}

// Load data
function loadData() {
  products = JSON.parse(localStorage.getItem('products')) || [...PRODUCTS];
}

// Load cart from localStorage
function loadCart() {
  cart = JSON.parse(localStorage.getItem('cart')) || [];
}

// Save cart to localStorage
function saveCart() {
  localStorage.setItem('cart', JSON.stringify(cart));
}

// Render products grid
function renderProducts(filter = '') {
  const container = document.getElementById('productsGrid');
  
  let filteredProducts = products;
  
  if (filter) {
    filteredProducts = products.filter(p => 
      p.name.toLowerCase().includes(filter.toLowerCase()) ||
      p.code.toLowerCase().includes(filter.toLowerCase())
    );
  }
  
  if (filteredProducts.length === 0) {
    container.innerHTML = '<p class="text-center py-4" style="color: #8892b0;">Produk tidak ditemukan</p>';
    return;
  }
  
  container.innerHTML = filteredProducts.map(p => `
    <div class="product-card" onclick="addToCart(${p.id})">
      <div class="product-icon">
        <i class="bi bi-box-seam"></i>
      </div>
      <p class="product-name mb-1">${p.name}</p>
      <p class="product-price mb-2">${formatRupiah(p.selling_price)}</p>
      <p class="product-stock">Stok: ${p.stock} ${p.unit}</p>
    </div>
  `).join('');
}

// Search products
function searchProducts() {
  const searchTerm = document.getElementById('searchProduct').value;
  renderProducts(searchTerm);
}

// Add to cart
function addToCart(productId) {
  const product = products.find(p => p.id === productId);
  if (!product) return;
  
  if (product.stock <= 0) {
    alert('Stok produk habis!');
    return;
  }
  
  const existingItem = cart.find(c => c.productId === productId);
  
  if (existingItem) {
    if (existingItem.qty >= product.stock) {
      alert('Stok tidak mencukupi!');
      return;
    }
    existingItem.qty += 1;
  } else {
    cart.push({
      productId: product.id,
      code: product.code,
      name: product.name,
      price: product.selling_price,
      qty: 1,
      unit: product.unit
    });
  }
  
  saveCart();
  renderCart();
}

// Remove from cart
function removeFromCart(productId) {
  cart = cart.filter(c => c.productId !== productId);
  saveCart();
  renderCart();
}

// Update cart quantity
function updateCartQty(productId, newQty) {
  const item = cart.find(c => c.productId === productId);
  if (!item) return;
  
  const product = products.find(p => p.id === productId);
  
  if (newQty <= 0) {
    removeFromCart(productId);
    return;
  }
  
  if (newQty > product.stock) {
    alert('Stok tidak mencukupi!');
    return;
  }
  
  item.qty = newQty;
  saveCart();
  renderCart();
}

// Render cart
function renderCart() {
  const container = document.getElementById('cartItems');
  
  if (cart.length === 0) {
    container.innerHTML = '<p class="text-center py-4" style="color: #8892b0;">Keranjang kosong</p>';
    document.getElementById('cartSubtotal').textContent = 'Rp 0';
    document.getElementById('cartTotal').textContent = 'Rp 0';
    return;
  }
  
  container.innerHTML = cart.map(item => `
    <div class="cart-item">
      <div class="flex-grow-1">
        <p class="mb-1 fw-semibold">${item.name}</p>
        <p class="mb-0 text-muted" style="font-size: 0.85rem;">${formatRupiah(item.price)} x ${item.qty}</p>
      </div>
      <div class="d-flex align-items-center gap-2">
        <button class="btn btn-sm btn-outline-secondary" onclick="updateCartQty(${item.productId}, ${item.qty - 1})">
          <i class="bi bi-dash"></i>
        </button>
        <span class="fw-bold" style="min-width: 30px; text-align: center;">${item.qty}</span>
        <button class="btn btn-sm btn-outline-secondary" onclick="updateCartQty(${item.productId}, ${item.qty + 1})">
          <i class="bi bi-plus"></i>
        </button>
        <button class="btn btn-sm btn-danger ms-2" onclick="removeFromCart(${item.productId})">
          <i class="bi bi-trash"></i>
        </button>
      </div>
    </div>
  `).join('');
  
  // Calculate totals
  const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
  document.getElementById('cartSubtotal').textContent = formatRupiah(subtotal);
  document.getElementById('cartTotal').textContent = formatRupiah(subtotal);
}

// Clear cart
function clearCart() {
  if (!confirm('Yakin ingin mengosongkan keranjang?')) return;
  
  cart = [];
  saveCart();
  renderCart();
}

// Checkout
function checkout() {
  if (cart.length === 0) {
    alert('Keranjang masih kosong!');
    return;
  }
  
  const paymentMethod = document.getElementById('paymentMethod').value;
  const paidAmount = parseInt(document.getElementById('paidAmount').value) || 0;
  const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
  
  if (paymentMethod === 'cash' && paidAmount < subtotal) {
    alert('Jumlah bayar kurang!');
    return;
  }
  
  const change = paymentMethod === 'cash' ? paidAmount - subtotal : 0;
  
  // Generate invoice
  const now = new Date();
  const dateStr = now.toISOString().split('T')[0].replace(/-/g, '');
  const invoiceNumber = `KSK-${dateStr}-${String(Math.floor(Math.random() * 10000)).padStart(4, '0')}`;
  
  // Create transaction
  const user = getCurrentUser();
  const transaction = {
    id: Date.now(),
    invoice: invoiceNumber,
    date: now.toISOString().replace('T', ' ').split('.')[0],
    user_id: user.id,
    user_name: user.name,
    items: cart.map(item => ({
      product: item.name,
      qty: item.qty,
      price: item.price
    })),
    subtotal: subtotal,
    discount: 0,
    total: subtotal,
    paid: paidAmount,
    change: change,
    payment: paymentMethod
  };
  
  // Save transaction
  let transactions = JSON.parse(localStorage.getItem('transactions')) || [...TRANSACTIONS];
  transactions.push(transaction);
  localStorage.setItem('transactions', JSON.stringify(transactions));
  
  // Update product stock
  cart.forEach(item => {
    const product = products.find(p => p.id === item.productId);
    if (product) {
      product.stock -= item.qty;
    }
  });
  localStorage.setItem('products', JSON.stringify(products));
  
  // Show success message
  alert(`Transaksi berhasil!\nInvoice: ${invoiceNumber}\nTotal: ${formatRupiah(subtotal)}\nBayar: ${formatRupiah(paidAmount)}\nKembalian: ${formatRupiah(change)}`);
  
  // Clear cart
  cart = [];
  saveCart();
  renderCart();
  
  // Reset form
  document.getElementById('paidAmount').value = '';
  
  // Reload products
  loadData();
  renderProducts();
}

// Search products
function searchProducts() {
  const searchTerm = document.getElementById('searchProduct').value;
  renderProducts(searchTerm);
}

// Format rupiah
function formatRupiah(number) {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(number);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', initKasirDashboard);
