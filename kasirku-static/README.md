# 🏪 KASIRKU - Point of Sales System (Static Version)

Aplikasi Point of Sales (POS) berbasis web untuk manajemen kasir, gudang, dan laporan penjualan.

## 🎯 Fitur

### 👑 Owner / Manajer
- Dashboard analytics dengan grafik pendapatan
- Laporan total revenue, profit, dan transaksi
- Top 5 produk terlaris
- Peringatan stok rendah (low stock alerts)

### 📦 Admin Gudang
- CRUD Produk (Create, Read, Update, Delete)
- Manajemen kategori produk
- Filter dan pencarian produk
- Monitor stok produk

### 💰 Kasir
- Point of Sale (POS) interface
- Keranjang belanja interaktif
- Multiple metode pembayaran (Tunai, QRIS, Debit)
- Pencarian produk real-time
- Generate invoice otomatis

## 🔑 Kredensial Login

### Owner / Manajer
```
Email: owner@kasirku.com
Password: password
```

### Admin Gudang
```
Email: gudang@kasirku.com
Password: password
```

### Kasir
```
Email: kasir@kasirku.com
Password: password
```

## 🚀 Cara Menggunakan

### Local Development
1. Buka folder `kasirku-static`
2. Buka file `index.html` di browser
3. Login dengan salah satu kredensial di atas

### Deploy ke Vercel
```bash
cd kasirku-static
vercel --prod
```

### Deploy ke Netlify
1. Drag & drop folder `kasirku-static` ke https://app.netlify.com/drop
2. Atau gunakan Netlify CLI:
```bash
cd kasirku-static
netlify deploy --prod
```

## 📁 Struktur Folder

```
kasirku-static/
├── index.html          # Login page
├── owner.html          # Owner dashboard
├── gudang.html         # Gudang dashboard
├── kasir.html          # Kasir POS
└── js/
    ├── data.js         # Dummy data (users, products, transactions)
    ├── auth.js         # Authentication logic
    ├── owner.js        # Owner dashboard logic
    ├── gudang.js       # Gudang CRUD logic
    └── kasir.js        # POS logic
```

## 💾 Data Storage

- **LocalStorage** digunakan untuk menyimpan:
  - Session login user
  - Keranjang belanja
  - Products (setelah CRUD)
  - Transactions (setelah checkout)

- Data akan **persist** di browser masing-masing user
- Untuk reset data, hapus localStorage via browser DevTools

## 🛠️ Tech Stack

- **HTML5** - Struktur
- **CSS3** + **Bootstrap 5** - Styling
- **Vanilla JavaScript** - Logic
- **Chart.js** - Grafik analytics
- **LocalStorage** - Data persistence

## 📊 Dummy Data

### Products
- 13 produk dari 5 kategori berbeda
- Indomie, Le Minerale, Chitato, Oreo, Beras, dll
- Complete dengan harga beli, harga jual, dan stok

### Transactions
- 10 transaksi historical (5 hari terakhir)
- Untuk populate grafik dashboard owner

## ⚠️ Limitations

- ❌ Data tidak tersinkronisasi antar device/browser
- ❌ Tidak ada real database backend
- ❌ Session hanya di localStorage (tidak secure)
- ❌ Multi-user tidak real-time

## ✅ Perfect For

- ✅ Demo presentasi tugas kuliah
- ✅ Prototype aplikasi POS
- ✅ Testing UI/UX design
- ✅ Portfolio project

## 📝 Notes

- **Deadline friendly** - Deploy hanya butuh 10 detik!
- **Offline capable** - Bisa jalan tanpa internet setelah load pertama
- **Mobile responsive** - Support semua device
- **No backend needed** - Pure frontend

## 🎓 Developed By

Tugas Pemrograman Web - Point of Sales System

---

**Last Updated:** 8 November 2026
**Version:** 1.0.0 (Static)
