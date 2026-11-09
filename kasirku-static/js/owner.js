// ========================================
// KASIRKU - OWNER DASHBOARD LOGIC
// ========================================

// Initialize owner dashboard
function initOwnerDashboard() {
  checkRole('owner');
  displayUserName();
  loadDashboardData();
  renderRevenueChart();
  renderTopProducts();
  renderLowStockAlerts();
}

// Load dashboard metrics
function loadDashboardData() {
  // Get transactions from localStorage or use default
  const transactions = JSON.parse(localStorage.getItem('transactions')) || TRANSACTIONS;
  
  // Calculate metrics
  const totalRevenue = transactions.reduce((sum, t) => sum + t.total, 0);
  const totalCost = transactions.reduce((sum, t) => {
    return sum + t.items.reduce((itemSum, item) => {
      const product = PRODUCTS.find(p => p.name === item.product);
      return itemSum + (product ? product.cost_price * item.qty : 0);
    }, 0);
  }, 0);
  const totalProfit = totalRevenue - totalCost;
  const totalTransactions = transactions.length;
  const totalProductsSold = transactions.reduce((sum, t) => {
    return sum + t.items.reduce((itemSum, item) => itemSum + item.qty, 0);
  }, 0);

  // Display metrics
  document.getElementById('totalRevenue').textContent = formatRupiah(totalRevenue);
  document.getElementById('totalProfit').textContent = formatRupiah(totalProfit);
  document.getElementById('totalTransactions').textContent = totalTransactions;
  document.getElementById('totalProductsSold').textContent = totalProductsSold;
}

// Render revenue chart (last 5 days)
function renderRevenueChart() {
  const transactions = JSON.parse(localStorage.getItem('transactions')) || TRANSACTIONS;
  
  // Group by date
  const revenueByDate = {};
  transactions.forEach(t => {
    const date = t.date.split(' ')[0];
    if (!revenueByDate[date]) {
      revenueByDate[date] = 0;
    }
    revenueByDate[date] += t.total;
  });

  // Sort dates
  const dates = Object.keys(revenueByDate).sort();
  const revenues = dates.map(d => revenueByDate[d]);

  // Format labels
  const labels = dates.map(d => {
    const [year, month, day] = d.split('-');
    return `${day}/${month}`;
  });

  // Create chart
  const ctx = document.getElementById('revenueChart').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [{
        label: 'Revenue (Rp)',
        data: revenues,
        borderColor: '#ea7c69',
        backgroundColor: 'rgba(234, 124, 105, 0.1)',
        tension: 0.4,
        fill: true,
        pointBackgroundColor: '#ea7c69',
        pointBorderColor: '#fff',
        pointBorderWidth: 2,
        pointRadius: 5
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            color: '#8892b0',
            callback: function(value) {
              return formatRupiahShort(value);
            }
          },
          grid: {
            color: 'rgba(255, 255, 255, 0.05)'
          }
        },
        x: {
          ticks: {
            color: '#8892b0'
          },
          grid: {
            display: false
          }
        }
      }
    }
  });
}

// Render top products table
function renderTopProducts() {
  const transactions = JSON.parse(localStorage.getItem('transactions')) || TRANSACTIONS;
  
  // Calculate product sales
  const productSales = {};
  transactions.forEach(t => {
    t.items.forEach(item => {
      if (!productSales[item.product]) {
        productSales[item.product] = {
          name: item.product,
          qty: 0,
          revenue: 0
        };
      }
      productSales[item.product].qty += item.qty;
      productSales[item.product].revenue += item.price * item.qty;
    });
  });

  // Sort by revenue
  const topProducts = Object.values(productSales)
    .sort((a, b) => b.revenue - a.revenue)
    .slice(0, 5);

  // Render table
  const tbody = document.getElementById('topProductsTable');
  tbody.innerHTML = topProducts.map((p, index) => `
    <tr>
      <td class="px-4 py-3">${index + 1}</td>
      <td class="px-4 py-3">${p.name}</td>
      <td class="px-4 py-3 text-center">${p.qty}</td>
      <td class="px-4 py-3 text-end">${formatRupiah(p.revenue)}</td>
    </tr>
  `).join('');
}

// Render low stock alerts
function renderLowStockAlerts() {
  const products = JSON.parse(localStorage.getItem('products')) || PRODUCTS;
  const lowStockProducts = products.filter(p => p.stock < p.min_stock);

  const container = document.getElementById('lowStockAlerts');
  
  if (lowStockProducts.length === 0) {
    container.innerHTML = '<p class="text-center py-4" style="color: #8892b0;">Tidak ada produk dengan stok rendah</p>';
    return;
  }

  container.innerHTML = lowStockProducts.map(p => `
    <div class="d-flex justify-content-between align-items-center p-3 mb-2" style="background: rgba(239, 68, 68, 0.1); border-radius: 12px;">
      <div>
        <p class="mb-0 fw-semibold" style="font-size: 0.9rem;">${p.name}</p>
        <p class="mb-0" style="font-size: 0.8rem; color: #f87171;">Stok: ${p.stock} ${p.unit} (Min: ${p.min_stock})</p>
      </div>
      <i class="bi bi-exclamation-triangle-fill" style="color: #f87171; font-size: 1.2rem;"></i>
    </div>
  `).join('');
}

// Format rupiah
function formatRupiah(number) {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(number);
}

// Format rupiah short (for chart)
function formatRupiahShort(number) {
  if (number >= 1000000) {
    return (number / 1000000).toFixed(1) + 'jt';
  } else if (number >= 1000) {
    return (number / 1000).toFixed(0) + 'rb';
  }
  return number.toString();
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', initOwnerDashboard);
