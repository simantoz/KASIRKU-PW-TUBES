@extends('layouts.app')

@section('title', 'Dashboard Gudang & Inventaris')
@section('page_title', 'Dashboard Gudang & Manajemen Inventaris')

@section('content')
<div class="container-fluid px-0">
    <!-- Top Stat KPI Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Barang -->
        <div class="col-xl-3 col-sm-6">
            <div class="card card-custom stat-card border-0 bg-white shadow-sm">
                <div class="text-muted fw-semibold small">Total Jenis Produk (SKU)</div>
                <h3 class="fw-bold text-dark mt-2 mb-1">{{ number_format($totalProducts) }}</h3>
                <div class="text-primary small fw-medium">
                    <i class="bi bi-boxes"></i> Terdaftar di katalog
                </div>
                <div class="stat-icon bg-primary-subtle text-primary">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
        </div>

        <!-- Total Stok Fisik -->
        <div class="col-xl-3 col-sm-6">
            <div class="card card-custom stat-card border-0 bg-white shadow-sm">
                <div class="text-muted fw-semibold small">Total Kuantitas Fisik</div>
                <h3 class="fw-bold text-dark mt-2 mb-1">{{ number_format($totalStock) }} <span style="font-size: 1rem; font-weight: normal;">Unit</span></h3>
                <div class="text-success small fw-medium">
                    <i class="bi bi-check2-all"></i> Total seluruh stok gudang
                </div>
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="bi bi-layers"></i>
                </div>
            </div>
        </div>

        <!-- Peringatan Stok Menipis -->
        <div class="col-xl-3 col-sm-6">
            <div class="card card-custom stat-card border-0 bg-white shadow-sm">
                <div class="text-muted fw-semibold small">Peringatan Stok Menipis</div>
                <h3 class="fw-bold {{ $lowStockCount > 0 ? 'text-danger' : 'text-success' }} mt-2 mb-1">
                    {{ $lowStockCount }} <span style="font-size: 1rem; font-weight: normal;">Produk</span>
                </h3>
                <div class="text-danger small fw-medium">
                    <i class="bi bi-exclamation-triangle"></i> Membutuhkan restock
                </div>
                <div class="stat-icon {{ $lowStockCount > 0 ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }}">
                    <i class="bi bi-bell-fill"></i>
                </div>
            </div>
        </div>

        <!-- Total Kategori -->
        <div class="col-xl-3 col-sm-6">
            <div class="card card-custom stat-card border-0 bg-white shadow-sm">
                <div class="text-muted fw-semibold small">Kategori Barang</div>
                <h3 class="fw-bold text-dark mt-2 mb-1">{{ number_format($totalCategories) }}</h3>
                <div class="text-muted small fw-medium">
                    <i class="bi bi-tag"></i> Klasifikasi produk
                </div>
                <div class="stat-icon bg-warning-subtle text-warning">
                    <i class="bi bi-tags"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Alert Banner & Action -->
    @if($lowStockCount > 0)
        <div class="card card-custom border-danger border-opacity-25 bg-danger-subtle p-3 mb-4 shadow-sm">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-danger text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-danger mb-0">Peringatan: Terdapat {{ $lowStockCount }} produk dengan stok kritis!</h6>
                        <small class="text-danger-emphasis">Segera input stok masuk dari supplier sebelum stok di meja kasir habis.</small>
                    </div>
                </div>
                <a href="{{ route('gudang.stock.index') }}" class="btn btn-danger btn-sm fw-semibold">
                    <i class="bi bi-truck me-1"></i> Buka Form Stok Masuk
                </a>
            </div>
        </div>
    @endif

    <div class="row g-3">
        <!-- Low Stock Items Table -->
        <div class="col-lg-6">
            <div class="card card-custom p-3 shadow-sm border-0 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3 px-2 pt-2">
                    <div>
                        <h6 class="fw-bold text-dark mb-0">Daftar Produk Stok Kritis</h6>
                        <small class="text-muted">Stok saat ini &le; batas minimum stok</small>
                    </div>
                    <span class="badge bg-danger-subtle text-danger">{{ $lowStockCount }} Item</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th>Kategori</th>
                                <th class="text-center">Sisa Stok</th>
                                <th class="text-center">Min. Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockProducts as $lp)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $lp->name }}</div>
                                        <small class="text-muted font-monospace">{{ $lp->code }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $lp->category->name ?? '-' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-danger text-white px-2 py-1">
                                            {{ $lp->stock }} {{ $lp->unit }}
                                        </span>
                                    </td>
                                    <td class="text-center text-muted">
                                        {{ $lp->min_stock }} {{ $lp->unit }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-success py-4">
                                        <i class="bi bi-check-circle fs-3 d-block mb-1"></i>
                                        Semua stok barang dalam kondisi aman!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Restocks Table -->
        <div class="col-lg-6">
            <div class="card card-custom p-3 shadow-sm border-0 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3 px-2 pt-2">
                    <div>
                        <h6 class="fw-bold text-dark mb-0">Riwayat Stok Masuk Terkini</h6>
                        <small class="text-muted">Penerimaan barang dari supplier</small>
                    </div>
                    <a href="{{ route('gudang.stock.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                        <thead class="table-light">
                            <tr>
                                <th>Waktu</th>
                                <th>Barang</th>
                                <th>Supplier</th>
                                <th class="text-center">Masuk</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentRestocks as $rr)
                                <tr>
                                    <td class="text-muted small">
                                        {{ $rr->created_at->format('d/m H:i') }}
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $rr->product->name ?? 'Produk Dihapus' }}</div>
                                    </td>
                                    <td class="text-muted small">
                                        {{ $rr->supplier_name ?? '-' }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success-subtle text-success fw-bold">
                                            +{{ $rr->qty }} {{ $rr->product->unit ?? 'pcs' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Belum ada riwayat stok masuk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
