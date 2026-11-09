// ========================================
// KASIRKU - GUDANG LOGIC
// ========================================

let products = [];
let categories = [];
let editingProductId = null;

// Initialize gudang dashboard
function initGudangDashboard() {
  checkRole('gudang');
  displayUserName();
  loadData();
  renderProductsTable();
  renderCategoriesTable();
}

// Load data from localStorage or use defaults
function loadData() {
  products = JSON.parse(localStorage.getItem('products')) || [...PRODUCTS];
  categories = JSON.parse(localStorage.getItem('categories')) || [...CATEGORIES];
}

// Save data to localStorage
function saveData() {
  localStorage.setItem('products', JSON.stringify(products));
  localStorage.setItem('categories', JSON.stringify(categories));
}

// Render products table
function renderProductsTable(filter = '') {
  const tbody = document.getElementById('productsTable');
  
  let filteredProducts = products;
  
  // Apply search filter
  if (filter) {
    filteredProducts = products.filter(p => 
      p.name.toLowerCase().includes(filter.toLowerCase()) ||
      p.code.toLowerCase().includes(filter.toLowerCase()) ||
      p.category.toLowerCase().includes(filter.toLowerCase())
    );
  }
  
  // Apply category filter
  const categoryFilter = document.getElementById('categoryFilter')?.value;
  if (categoryFilter && categoryFilter !== 'all') {
    filteredProducts = filteredProducts.filter(p => p.category_id == categoryFilter);
  }
  
  if (filteredProducts.length === 0) {
    tbody.innerHTML = '<tr><td colspan="8" class="text-center py-4" style="color: #8892b0;">Tidak ada produk ditemukan</td></tr>';
    return;
  }
  
  tbody.innerHTML = filteredProducts.map(p => {
    const stockStatus = p.stock < p.min_stock ? 
      '<span class="badge bg-danger">Rendah</span>' : 
      '<span class="badge bg-success">Normal</span>';
    
    return `
      <tr>
        <td class="px-3 py-3">${p.code}</td>
        <td class="px-3 py-3">${p.name}</td>
        <td class="px-3 py-3">${p.category}</td>
        <td class="px-3 py-3 text-end">${formatRupiah(p.cost_price)}</td>
        <td class="px-3 py-3 text-end">${formatRupiah(p.selling_price)}</td>
        <td class="px-3 py-3 text-center">${p.stock} ${p.unit}</td>
        <td class="px-3 py-3 text-center">${stockStatus}</td>
        <td class="px-3 py-3">
          <button class="btn btn-sm btn-warning me-1" onclick="editProduct(${p.id})">
            <i class="bi bi-pencil"></i>
          </button>
          <button class="btn btn-sm btn-danger" onclick="deleteProduct(${p.id})">
            <i class="bi bi-trash"></i>
          </button>
        </td>
      </tr>
    `;
  }).join('');
}

// Show add product modal
function showAddProductModal() {
  editingProductId = null;
  document.getElementById('modalTitle').textContent = 'Tambah Produk';
  document.getElementById('productForm').reset();
  
  // Populate category dropdown
  const categorySelect = document.getElementById('productCategory');
  categorySelect.innerHTML = categories.map(c => 
    `<option value="${c.id}">${c.name}</option>`
  ).join('');
  
  new bootstrap.Modal(document.getElementById('productModal')).show();
}

// Edit product
function editProduct(id) {
  const product = products.find(p => p.id === id);
  if (!product) return;
  
  editingProductId = id;
  document.getElementById('modalTitle').textContent = 'Edit Produk';
  
  // Populate form
  document.getElementById('productCode').value = product.code;
  document.getElementById('productName').value = product.name;
  document.getElementById('productCategory').value = product.category_id;
  document.getElementById('productCostPrice').value = product.cost_price;
  document.getElementById('productSellingPrice').value = product.selling_price;
  document.getElementById('productStock').value = product.stock;
  document.getElementById('productMinStock').value = product.min_stock;
  document.getElementById('productUnit').value = product.unit;
  
  // Populate category dropdown
  const categorySelect = document.getElementById('productCategory');
  categorySelect.innerHTML = categories.map(c => 
    `<option value="${c.id}" ${c.id === product.category_id ? 'selected' : ''}>${c.name}</option>`
  ).join('');
  
  new bootstrap.Modal(document.getElementById('productModal')).show();
}

// Save product
function saveProduct(event) {
  event.preventDefault();
  
  const formData = {
    code: document.getElementById('productCode').value,
    name: document.getElementById('productName').value,
    category_id: parseInt(document.getElementById('productCategory').value),
    cost_price: parseInt(document.getElementById('productCostPrice').value),
    selling_price: parseInt(document.getElementById('productSellingPrice').value),
    stock: parseInt(document.getElementById('productStock').value),
    min_stock: parseInt(document.getElementById('productMinStock').value),
    unit: document.getElementById('productUnit').value
  };
  
  // Get category name
  const category = categories.find(c => c.id === formData.category_id);
  formData.category = category.name;
  
  if (editingProductId) {
    // Update existing product
    const index = products.findIndex(p => p.id === editingProductId);
    products[index] = { ...products[index], ...formData };
  } else {
    // Add new product
    const newId = Math.max(...products.map(p => p.id), 0) + 1;
    products.push({ id: newId, ...formData });
  }
  
  saveData();
  renderProductsTable();
  bootstrap.Modal.getInstance(document.getElementById('productModal')).hide();
}

// Delete product
function deleteProduct(id) {
  if (!confirm('Yakin ingin menghapus produk ini?')) return;
  
  products = products.filter(p => p.id !== id);
  saveData();
  renderProductsTable();
}

// Render categories table
function renderCategoriesTable() {
  const tbody = document.getElementById('categoriesTable');
  
  tbody.innerHTML = categories.map((c, index) => `
    <tr>
      <td class="px-3 py-3">${index + 1}</td>
      <td class="px-3 py-3">${c.name}</td>
      <td class="px-3 py-3">${c.slug}</td>
      <td class="px-3 py-3">
        <button class="btn btn-sm btn-warning me-1" onclick="editCategory(${c.id})">
          <i class="bi bi-pencil"></i>
        </button>
        <button class="btn btn-sm btn-danger" onclick="deleteCategory(${c.id})">
          <i class="bi bi-trash"></i>
        </button>
      </td>
    </tr>
  `).join('');
  
  // Also update category filter in products tab
  const categoryFilter = document.getElementById('categoryFilter');
  if (categoryFilter) {
    categoryFilter.innerHTML = `
      <option value="all">Semua Kategori</option>
      ${categories.map(c => `<option value="${c.id}">${c.name}</option>`).join('')}
    `;
  }
}

// Search products
function searchProducts() {
  const searchTerm = document.getElementById('searchProduct').value;
  renderProductsTable(searchTerm);
}

// Filter by category
function filterByCategory() {
  renderProductsTable();
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
document.addEventListener('DOMContentLoaded', initGudangDashboard);
