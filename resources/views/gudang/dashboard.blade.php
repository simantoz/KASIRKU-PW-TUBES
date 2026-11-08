@extends('layouts.app')

@section('title', 'Dashboard Gudang')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: #fff; font-size: 1.6rem;">Dashboard Gudang</h2>
            <div style="color: #8892b0; font-size: 0.85rem;">Status stok menu dan logistik penerimaan</div>
        </div>
        <a href="{{ route('gudang.stock.index') }}" class="btn btn-primary-custom d-flex align-items-center gap-2">
            <i class="bi bi-truck"></i>
            <span>Stok Masuk Baru</span>
        </a>
    </div>

    <!-- KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card">
                <div style="color: #8892b0; font-size: 0.82rem; font-weight: 600;">Total Menu Terdaftar</div>
                <h3 class="fw-bold mt-2 mb-1" style="color: #fff;">{{ number_format($totalProducts) }}</h3>
                <div class="small fw-semibold" style="color: #60a5fa;">
                    <i class="bi bi-boxes"></i> Master produk
                </div>
                <div class="stat-icon" style="background: rgba(59, 130, 246, 0.15); color: #60a5fa;">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="stat-card">
                <div style="color: #8892b0; font-size: 0.82rem; font-weight: 600;">Total Kuantitas Fisik</div>
                <h3 class="fw-bold mt-2 mb-1" style="color: #fff;">{{ number_format($totalStock) }} <span style="font-size: 0.9rem; font-weight: normal; color: #8892b0;">Unit</span></h3>
                <div class="small fw-semibold" style="color: #34d399;">
                    <i class="bi bi-check2-all"></i> Total stok tersedia
                </div>
                <div class="stat-icon" style="background: rgba(16, 185, 129, 0.15); color: #34d399;">
                    <i class="bi bi-layers"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="stat-card">
                <div style="color: #8892b0; font-size: 0.82rem; font-weight: 600;">Stok Menipis</div>
                <h3 class="fw-bold mt-2 mb-1" style="color: {{ $lowStockCount > 0 ? '#f87171' : '#34d399' }};">
                    {{ $lowStockCount }} <span style="font-size: 0.9rem; font-weight: normal; color: #8892b0;">Produk</span>
                </h3>
                <div class="small fw-semibold" style="color: #8892b0;">
                    Perlu restock segera
                </div>
                <div class="stat-icon" style="background: {{ $lowStockCount > 0 ? 'rgba(239, 68, 68, 0.15)' : 'rgba(16, 185, 129, 0.15)' }}; color: {{ $lowStockCount > 0 ? '#f87171' : '#34d399' }};">
                    <i class="bi bi-bell-fill"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="stat-card">
                <div style="color: #8892b0; font-size: 0.82rem; font-weight: 600;">Kategori Menu</div>
                <h3 class="fw-bold mt-2 mb-1" style="color: #fff;">{{ number_format($totalCategories) }}</h3>
                <div class="small fw-semibold" style="color: #fbbf24;">
                    <i class="bi bi-tag"></i> Klasifikasi
                </div>
                <div class="stat-icon" style="background: rgba(245, 158, 11, 0.15); color: #fbbf24;">
                    <i class="bi bi-tags"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables -->
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card-custom p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-white">Stok Kritis</h5>
                    <span class="badge" style="background: rgba(239, 68, 68, 0.15); color: #f87171;">{{ $lowStockCount }} Item</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-dark-custom mb-0">
                        <thead>
                            <tr>
                                <th>Menu</th>
                                <th>Kategori</th>
                                <th class="text-center">Sisa</th>
                                <th class="text-center">Batas Min</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockProducts as $lp)
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-white">{{ $lp->name }}</div>
                                        <small style="color: #8892b0;">{{ $lp->code }}</small>
                                    </td>
                                    <td><span class="badge" style="background: #2d303e; color: #fff;">{{ $lp->category->name ?? '-' }}</span></td>
                                    <td class="text-center">
                                        <span class="badge" style="background: rgba(239, 68, 68, 0.2); color: #f87171;">
                                            {{ $lp->stock }} {{ $lp->unit }}
                                        </span>
                                    </td>
                                    <td class="text-center" style="color: #8892b0;">{{ $lp->min_stock }} {{ $lp->unit }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4" style="color: #34d399;">
                                        <i class="bi bi-check-circle fs-3 d-block mb-1"></i>
                                        Semua stok aman!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card-custom p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-white">Stok Masuk Terkini</h5>
                    <a href="{{ route('gudang.stock.index') }}" class="btn btn-sm" style="background: #2d303e; color: #ea7c69; border-radius: 10px;">
                        Lihat Semua <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-dark-custom mb-0">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Menu</th>
                                <th>Supplier</th>
                                <th class="text-center">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentRestocks as $rr)
                                <tr>
                                    <td style="color: #8892b0; font-size: 0.8rem;">{{ $rr->created_at->format('d/m H:i') }}</td>
                                    <td class="text-white">{{ $rr->product->name ?? '-' }}</td>
                                    <td style="color: #8892b0;">{{ $rr->supplier_name ?? '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge fw-bold" style="background: rgba(16, 185, 129, 0.15); color: #34d399;">
                                            +{{ $rr->qty }} {{ $rr->product->unit ?? 'pcs' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4" style="color: #8892b0;">Belum ada riwayat stok masuk.</td>
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
