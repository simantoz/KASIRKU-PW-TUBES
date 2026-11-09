// ========================================
// KASIRKU - DUMMY DATA
// ========================================

// USERS
const USERS = [
  { id: 1, name: 'Pak Hendra (Owner / Manajer)', email: 'owner@kasirku.com', password: 'password', role: 'owner', phone: '081234567890' },
  { id: 2, name: 'Budi Santoso (Admin Gudang)', email: 'gudang@kasirku.com', password: 'password', role: 'gudang', phone: '081298765432' },
  { id: 3, name: 'Siti Rahma (Kasir 1)', email: 'kasir@kasirku.com', password: 'password', role: 'kasir', phone: '085612345678' },
  { id: 4, name: 'Dimas Aditya (Kasir 2)', email: 'kasir2@kasirku.com', password: 'password', role: 'kasir', phone: '085798765432' }
];

// CATEGORIES
const CATEGORIES = [
  { id: 1, name: 'Makanan & Mie Instan', slug: 'makanan-mie-instan' },
  { id: 2, name: 'Minuman & Kopi', slug: 'minuman-kopi' },
  { id: 3, name: 'Snack & Biskuit', slug: 'snack-biskuit' },
  { id: 4, name: 'Sembako & Dapur', slug: 'sembako-dapur' },
  { id: 5, name: 'Perawatan Diri & Kebersihan', slug: 'perawatan-diri-kebersihan' }
];

// PRODUCTS
const PRODUCTS = [
  { id: 1, code: 'MIE001', name: 'Indomie Goreng Spesial 85g', category_id: 1, category: 'Makanan & Mie Instan', cost_price: 2800, selling_price: 3500, stock: 120, min_stock: 15, unit: 'bks' },
  { id: 2, code: 'MIE002', name: 'Indomie Kuah Ayam Bawang 75g', category_id: 1, category: 'Makanan & Mie Instan', cost_price: 2700, selling_price: 3500, stock: 80, min_stock: 15, unit: 'bks' },
  { id: 3, code: 'MIN001', name: 'Le Minerale 600ml', category_id: 2, category: 'Minuman & Kopi', cost_price: 2200, selling_price: 3500, stock: 75, min_stock: 10, unit: 'btl' },
  { id: 4, code: 'MIN002', name: 'Teh Botol Sosro Kotak 250ml', category_id: 2, category: 'Minuman & Kopi', cost_price: 3200, selling_price: 4500, stock: 45, min_stock: 10, unit: 'ktk' },
  { id: 5, code: 'MIN003', name: 'Ultra Milk Cokelat 250ml', category_id: 2, category: 'Minuman & Kopi', cost_price: 5200, selling_price: 7000, stock: 30, min_stock: 8, unit: 'ktk' },
  { id: 6, code: 'SNK001', name: 'Chitato Sapi Panggang 68g', category_id: 3, category: 'Snack & Biskuit', cost_price: 8800, selling_price: 11500, stock: 25, min_stock: 5, unit: 'bks' },
  { id: 7, code: 'SNK002', name: 'Oreo Vanilla 133g', category_id: 3, category: 'Snack & Biskuit', cost_price: 7800, selling_price: 10000, stock: 22, min_stock: 5, unit: 'bks' },
  { id: 8, code: 'SNK003', name: 'SilverQueen Cashew 58g', category_id: 3, category: 'Snack & Biskuit', cost_price: 12500, selling_price: 16500, stock: 18, min_stock: 5, unit: 'pcs' },
  { id: 9, code: 'SMB001', name: 'Beras Ramos Premium 5kg', category_id: 4, category: 'Sembako & Dapur', cost_price: 64000, selling_price: 74500, stock: 14, min_stock: 5, unit: 'sak' },
  { id: 10, code: 'SMB002', name: 'Minyak Goreng Bimoli Pouch 2L', category_id: 4, category: 'Sembako & Dapur', cost_price: 32000, selling_price: 38000, stock: 4, min_stock: 6, unit: 'pch' },
  { id: 11, code: 'SMB003', name: 'Gulaku Gula Pasir Premium 1kg', category_id: 4, category: 'Sembako & Dapur', cost_price: 14500, selling_price: 17500, stock: 3, min_stock: 5, unit: 'bks' },
  { id: 12, code: 'PRW001', name: 'Sabun Mandi Lifebuoy Total 10 85g', category_id: 5, category: 'Perawatan Diri & Kebersihan', cost_price: 3400, selling_price: 4500, stock: 40, min_stock: 8, unit: 'pcs' },
  { id: 13, code: 'PRW002', name: 'Pepsodent Fresh Cool 120g', category_id: 5, category: 'Perawatan Diri & Kebersihan', cost_price: 9500, selling_price: 13000, stock: 20, min_stock: 5, unit: 'pcs' }
];

// TRANSACTIONS (5 hari terakhir)
const TRANSACTIONS = [
  { id: 1, invoice: 'KSK-20261104-0001', date: '2026-11-04 10:15', user_id: 3, user_name: 'Siti Rahma', items: [{ product: 'Indomie Goreng Spesial 85g', qty: 3, price: 3500 }, { product: 'Le Minerale 600ml', qty: 2, price: 3500 }], subtotal: 17500, discount: 0, total: 17500, paid: 20000, change: 2500, payment: 'cash' },
  { id: 2, invoice: 'KSK-20261104-0002', date: '2026-11-04 14:30', user_id: 4, user_name: 'Dimas Aditya', items: [{ product: 'Chitato Sapi Panggang 68g', qty: 1, price: 11500 }, { product: 'Ultra Milk Cokelat 250ml', qty: 2, price: 7000 }], subtotal: 25500, discount: 0, total: 25500, paid: 30000, change: 4500, payment: 'qris' },
  { id: 3, invoice: 'KSK-20261105-0001', date: '2026-11-05 10:15', user_id: 3, user_name: 'Siti Rahma', items: [{ product: 'Indomie Goreng Spesial 85g', qty: 3, price: 3500 }, { product: 'Le Minerale 600ml', qty: 2, price: 3500 }], subtotal: 17500, discount: 0, total: 17500, paid: 20000, change: 2500, payment: 'cash' },
  { id: 4, invoice: 'KSK-20261105-0002', date: '2026-11-05 14:30', user_id: 3, user_name: 'Siti Rahma', items: [{ product: 'Chitato Sapi Panggang 68g', qty: 1, price: 11500 }, { product: 'Ultra Milk Cokelat 250ml', qty: 2, price: 7000 }], subtotal: 25500, discount: 0, total: 25500, paid: 30000, change: 4500, payment: 'cash' },
  { id: 5, invoice: 'KSK-20261106-0001', date: '2026-11-06 10:15', user_id: 4, user_name: 'Dimas Aditya', items: [{ product: 'Indomie Goreng Spesial 85g', qty: 3, price: 3500 }, { product: 'Le Minerale 600ml', qty: 2, price: 3500 }], subtotal: 17500, discount: 0, total: 17500, paid: 20000, change: 2500, payment: 'qris' },
  { id: 6, invoice: 'KSK-20261106-0002', date: '2026-11-06 14:30', user_id: 3, user_name: 'Siti Rahma', items: [{ product: 'Chitato Sapi Panggang 68g', qty: 1, price: 11500 }, { product: 'Ultra Milk Cokelat 250ml', qty: 2, price: 7000 }], subtotal: 25500, discount: 0, total: 25500, paid: 30000, change: 4500, payment: 'cash' },
  { id: 7, invoice: 'KSK-20261107-0001', date: '2026-11-07 10:15', user_id: 4, user_name: 'Dimas Aditya', items: [{ product: 'Indomie Goreng Spesial 85g', qty: 3, price: 3500 }, { product: 'Le Minerale 600ml', qty: 2, price: 3500 }], subtotal: 17500, discount: 0, total: 17500, paid: 20000, change: 2500, payment: 'cash' },
  { id: 8, invoice: 'KSK-20261107-0002', date: '2026-11-07 14:30', user_id: 3, user_name: 'Siti Rahma', items: [{ product: 'Chitato Sapi Panggang 68g', qty: 1, price: 11500 }, { product: 'Ultra Milk Cokelat 250ml', qty: 2, price: 7000 }], subtotal: 25500, discount: 0, total: 25500, paid: 30000, change: 4500, payment: 'qris' },
  { id: 9, invoice: 'KSK-20261108-0001', date: '2026-11-08 10:15', user_id: 3, user_name: 'Siti Rahma', items: [{ product: 'Indomie Goreng Spesial 85g', qty: 3, price: 3500 }, { product: 'Le Minerale 600ml', qty: 2, price: 3500 }], subtotal: 17500, discount: 0, total: 17500, paid: 20000, change: 2500, payment: 'cash' },
  { id: 10, invoice: 'KSK-20261108-0002', date: '2026-11-08 14:30', user_id: 4, user_name: 'Dimas Aditya', items: [{ product: 'Chitato Sapi Panggang 68g', qty: 1, price: 11500 }, { product: 'Ultra Milk Cokelat 250ml', qty: 2, price: 7000 }], subtotal: 25500, discount: 0, total: 25500, paid: 30000, change: 4500, payment: 'cash' }
];
