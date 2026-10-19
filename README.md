# KasirKu - Sistem Point of Sales (POS) Berbasis Web

> **Tugas Besar Mata Kuliah Pemrograman Web**  
> Program Studi S1 Teknologi Informasi — Fakultas Informatika  
> **Telkom University Jakarta (2026)**

---

## 📌 Deskripsi Aplikasi
**KasirKu** adalah aplikasi web Point of Sales (POS) yang dirancang untuk mendigitalisasi proses transaksi penjualan, pengelolaan master barang, dan manajemen inventaris toko/ritel secara terpusat dan *real-time*.

Aplikasi ini dibangun sesuai dengan spesifikasi dokumen laporan kelompok dan memenuhi seluruh ketentuan tugas besar dari dosen pengampu.

---

## 🎯 Pemenuhan Ketentuan Tugas Besar

| No | Ketentuan Dosen | Implementasi pada KasirKu |
|---|---|---|
| 1 | **Topik Bebas** | Sistem Kasir & Manajemen Toko (**Point of Sales / POS**) |
| 2 | **Database & Implementasi CRUD** | Database terintegrasi dengan CRUD Master Barang, CRUD Kategori, CRUD Manajemen Akun Pegawai, Log Stok Masuk, dan Transaksi Penjualan. |
| 3 | **Minimal 3 Level Pengguna (Multi-Role)** | **1. Owner / Manajer** (Pemilik Toko)<br>**2. Admin Gudang** (Inventory & Back-Office)<br>**3. Kasir** (Front-Office / Operator Transaksi) |
| 4 | **Materi Pemrograman Web** | • **HTML5**: Semantik dan struktur formulir terstandar.<br>• **CSS & Bootstrap 5.3**: Tampilan antarmuka modern, responsif, dan elegan.<br>• **JavaScript & jQuery**: Interaksi keranjang belanja (*cart*), kalkulasi uang kembalian instan, dan *shortcuts* nominal.<br>• **AJAX / JSON**: Pencarian produk *live* tanpa *reload* halaman, filter kategori dinamis, dan proses *checkout* pembayaran.<br>• **PHP & Laravel 12**: Arsitektur MVC, Eloquent ORM, Migrations, Seeders, Route Groups, dan Role Middleware. |

---

## 👥 Hak Akses 3 Role Pengguna

### 1. 👑 Owner / Manajer (Pemilik Toko)
- **Fokus**: Pengawasan, analitik keuangan, dan manajemen SDM.
- **Fitur Utama**:
  - **Dashboard Eksekutif**: Grafik tren pendapatan 7 hari terakhir (Chart.js), distribusi metode bayar (Cash vs QRIS vs Transfer), dan Top 5 produk terlaris.
  - **Riwayat Penjualan**: Filter transaksi berdasarkan rentang tanggal, kasir, dan metode pembayaran.
  - **Detail Transaksi**: Pop-up rincian invoice via AJAX.
  - **Export Laporan**: Ekspor data laporan penjualan ke format **CSV / Excel**.
  - **Cetak Laporan PDF**: Format cetak laporan keuangan resmi dengan kolom tanda tangan pengesahan.
  - **Manajemen Pegawai (CRUD)**: Menambah, mengubah hak akses, mengaktifkan/menonaktifkan, dan menghapus akun Kasir atau Admin Gudang.
- **Batasan**: Tidak melakukan transaksi kasir langsung dan tidak menginput stok barang masuk langsung.

### 2. 📦 Admin Gudang (Inventory)
- **Fokus**: Manajemen barang masuk dan inventaris fisik (*back-office*).
- **Fitur Utama**:
  - **Dashboard Gudang**: Ringkasan total SKU, total unit fisik, dan notifikasi peringatan stok kritis.
  - **Master Data Barang (CRUD)**: Kelola kode barcode/SKU, nama produk, kategori, harga beli/modal, harga jual, stok minimum, dan satuan.
  - **Kategori Barang (CRUD)**: Kelola pengelompokan produk.
  - **Stok Masuk (Supplier Restock)**: Input penerimaan pasokan barang dari supplier yang secara otomatis menambah stok gudang dan kasir.
  - **Peringatan Stok Menipis**: Banner dan tabel penanda produk yang stoknya sudah mencapai batas minimum (&le; min_stock).
- **Batasan**: Tidak memiliki akses ke meja transaksi kasir dan tidak dapat melihat rekapitulasi omzet toko.

### 3. 💳 Kasir (Operator Front-Office)
- **Fokus**: Pelayanan transaksi pelanggan di kasir.
- **Fitur Utama**:
  - **Antarmuka Kasir POS**:
    - Pencarian barang *real-time* via nama atau scan barcode (AJAX).
    - Filter kategori barang via pill buttons.
    - Katalog produk visual dengan indikator ketersediaan stok.
  - **Keranjang Belanja (*Cart*) Dinamis**:
    - Tambah item, tambah/kurang kuantitas (+ / -), dan hapus item.
    - Validasi stok otomatis (mencegah pembelian melebihi stok yang ada).
  - **Kalkulasi & Pembayaran**:
    - Pilihan metode bayar: **Tunai (Cash)**, **QRIS**, dan **Transfer Bank**.
    - Tombol cepat pecahan uang (*shortcuts* Uang Pas, +10k, +20k, +50k, +100k).
    - Perhitungan uang kembalian secara otomatis dan instan.
  - **Cetak Struk Transaksi**:
    - Format struk thermal standar POS (58mm / 80mm).
    - Pop-up struk otomatis setelah pembayaran berhasil dengan tombol cetak langsung (*Direct Print*).
  - **Riwayat Shift Saya**:
    - Rekapitulasi transaksi khusus yang dilayani oleh kasir bersangkutan pada shift hari tersebut.
- **Batasan**: Tidak dapat mengubah harga/master barang dan tidak dapat mengakses omzet keseluruhan toko.

---

## 🔑 Akun Demo (Untuk Dosen & Penilai)

Pada halaman login (`http://127.0.0.1:8000/login`), tersedia tombol **"Klik Cepat Demo Akun"** sehingga dosen/penilai tidak perlu mengetik manual:

| Role | Email | Password | Hak Akses |
|---|---|---|---|
| **Owner / Manajer** | `owner@kasirku.com` | `password` | Akses penuh dashboard analitik, laporan, & kelola akun |
| **Admin Gudang** | `gudang@kasirku.com` | `password` | Akses inventaris, master barang, kategori, & stok masuk |
| **Kasir 1** | `kasir@kasirku.com` | `password` | Akses meja POS transaksi, cart, & struk kasir |
| **Kasir 2** | `kasir2@kasirku.com` | `password` | Akses meja POS shift alternatif |

---

## 🚀 Cara Menjalankan Aplikasi

### 1. Kebutuhan Sistem
- PHP 8.2+
- Composer
- Browser modern (Chrome, Edge, Firefox, Opera)

### 2. Langkah Menjalankan
Buka terminal / PowerShell di direktori proyek ini:

```bash
# 1. Jalankan migrasi dan seeder database awal (jika belum)
php artisan migrate:fresh --seed

# 2. Jalankan server lokal Laravel
php artisan serve
```

Aplikasi akan berjalan di: **`http://127.0.0.1:8000`**

*(Catatan: Konfigurasi default menggunakan SQLite yang sudah siap pakai tanpa perlu konfigurasi MySQL tambahan. Jika ingin menggunakan MySQL XAMPP, cukup sesuaikan baris `DB_CONNECTION=mysql` pada file `.env`).*

---

## 🗄️ Skema Desain Database
1. `users`: Menyimpan data akun pengguna dan role (`owner`, `gudang`, `kasir`).
2. `categories`: Menyimpan kategori produk.
3. `products`: Menyimpan master data produk (SKU, nama, harga modal, harga jual, stok, min_stock, unit).
4. `stock_mutations`: Mencatat riwayat stok masuk dari supplier.
5. `transactions`: Menyimpan data penjualan (invoice, kasir_id, pelanggan, subtotal, total, bayar, kembalian, metode bayar).
6. `transaction_details`: Menyimpan rincian setiap produk dalam transaksi penjualan.
