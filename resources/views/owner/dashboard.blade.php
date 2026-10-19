@extends('layouts.app')

@section('title', 'Dashboard Owner & Analitik')
@section('page_title', 'Dashboard Owner & Analitik Bisnis')

@section('content')
<div class="container-fluid px-0">
    <!-- Top Stat KPI Cards -->
    <div class="row g-3 mb-4">
        <!-- Pendapatan Hari Ini -->
        <div class="col-xl-3 col-sm-6">
            <div class="card card-custom stat-card border-0 bg-white shadow-sm">
                <div class="text-muted fw-semibold" style="font-size: 0.85rem;">Pendapatan Hari Ini</div>
                <h3 class="fw-bold text-dark mt-2 mb-1">Rp {{ number_format($revenueToday, 0, ',', '.') }}</h3>
                <div class="text-success small fw-medium">
                    <i class="bi bi-arrow-up-right"></i> {{ $transactionsToday }} transaksi hari ini
                </div>
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
        </div>

        <!-- Pendapatan Bulan Ini -->
        <div class="col-xl-3 col-sm-6">
            <div class="card card-custom stat-card border-0 bg-white shadow-sm">
                <div class="text-muted fw-semibold" style="font-size: 0.85rem;">Pendapatan Bulan Ini</div>
                <h3 class="fw-bold text-dark mt-2 mb-1">Rp {{ number_format($revenueThisMonth, 0, ',', '.') }}</h3>
                <div class="text-primary small fw-medium">
                    <i class="bi bi-wallet2"></i> Akumulasi bulan berjalan
                </div>
                <div class="stat-icon bg-primary-subtle text-primary">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
            </div>
        </div>

        <!-- Total Transaksi -->
        <div class="col-xl-3 col-sm-6">
            <div class="card card-custom stat-card border-0 bg-white shadow-sm">
                <div class="text-muted fw-semibold" style="font-size: 0.85rem;">Total Transaksi Sukses</div>
                <h3 class="fw-bold text-dark mt-2 mb-1">{{ number_format($totalTransactions) }}</h3>
                <div class="text-muted small fw-medium">
                    <i class="bi bi-receipt"></i> Total pesanan selesai
                </div>
                <div class="stat-icon bg-indigo-subtle text-primary" style="background-color: #ede9fe; color: #7c3aed;">
                    <i class="bi bi-cart-check"></i>
                </div>
            </div>
        </div>

        <!-- Peringatan Stok Menipis -->
        <div class="col-xl-3 col-sm-6">
            <div class="card card-custom stat-card border-0 bg-white shadow-sm">
                <div class="text-muted fw-semibold" style="font-size: 0.85rem;">Peringatan Stok Menipis</div>
                <h3 class="fw-bold {{ $lowStockCount > 0 ? 'text-danger' : 'text-success' }} mt-2 mb-1">
                    {{ $lowStockCount }} <span style="font-size: 1rem; font-weight: normal;">Produk</span>
                </h3>
                <div class="text-muted small fw-medium">
                    <i class="bi bi-box-seam"></i> Dari total {{ $totalProducts }} master produk
                </div>
                <div class="stat-icon {{ $lowStockCount > 0 ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }}">
                    <i class="bi bi-exclamation-octagon"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-3 mb-4">
        <!-- Tren Penjualan 7 Hari Terakhir -->
        <div class="col-lg-8">
            <div class="card card-custom p-4 h-100 shadow-sm border-0">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Tren Penjualan (7 Hari Terakhir)</h6>
                        <span class="text-muted" style="font-size: 0.82rem;">Grafik omzet pendapatan harian toko</span>
                    </div>
                    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-semibold">
                        Real-time Data
                    </span>
                </div>
                <div style="height: 290px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Metode Pembayaran -->
        <div class="col-lg-4">
            <div class="card card-custom p-4 h-100 shadow-sm border-0">
                <div class="mb-3">
                    <h6 class="fw-bold text-dark mb-1">Metode Pembayaran</h6>
                    <span class="text-muted" style="font-size: 0.82rem;">Distribusi cara bayar pelanggan</span>
                </div>
                <div style="height: 220px; display: flex; align-items: center; justify-content: center;">
                    <canvas id="paymentChart"></canvas>
                </div>
                <div class="mt-3 pt-2 border-top">
                    <div class="d-flex justify-content-around text-center">
                        @foreach($paymentMethods as $pm)
                            <div>
                                <span class="badge bg-light text-dark text-uppercase border">{{ $pm->payment_method }}</span>
                                <div class="fw-bold mt-1 text-dark" style="font-size: 0.9rem;">{{ $pm->count }}x</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables Row: Top Products & Recent Transactions -->
    <div class="row g-3">
        <!-- Top 5 Best Selling Products -->
        <div class="col-lg-5">
            <div class="card card-custom p-3 shadow-sm border-0 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3 px-2 pt-2">
                    <h6 class="fw-bold text-dark mb-0">5 Produk Terlaris</h6>
                    <span class="badge bg-light text-muted border">Berdasarkan Kuantitas</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Produk</th>
                                <th class="text-center">Terjual</th>
                                <th class="text-end">Total Omzet</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $idx => $tp)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-primary text-white rounded-circle" style="width: 22px; height: 22px; display: inline-flex; align-items: center; justify-content: center;">{{ $idx + 1 }}</span>
                                            <span class="fw-semibold text-dark">{{ $tp->product_name }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center fw-bold text-success">{{ $tp->total_qty }} pcs</td>
                                    <td class="text-end fw-semibold">Rp {{ number_format($tp->total_sales, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Belum ada transaksi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="col-lg-7">
            <div class="card card-custom p-3 shadow-sm border-0 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3 px-2 pt-2">
                    <div>
                        <h6 class="fw-bold text-dark mb-0">Transaksi Terkini</h6>
                        <span class="text-muted" style="font-size: 0.8rem;">Pesanan kasir yang baru saja diproses</span>
                    </div>
                    <a href="{{ route('owner.transactions') }}" class="btn btn-sm btn-outline-primary fw-semibold">
                        Lihat Semua <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                        <thead class="table-light">
                            <tr>
                                <th>No Invoice</th>
                                <th>Waktu</th>
                                <th>Kasir</th>
                                <th>Metode</th>
                                <th class="text-end">Total Tagihan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $rt)
                                <tr>
                                    <td>
                                        <span class="badge bg-dark-subtle text-dark font-monospace">{{ $rt->invoice_code }}</span>
                                    </td>
                                    <td class="text-muted" style="font-size: 0.8rem;">
                                        {{ $rt->created_at->format('d/m H:i') }}
                                    </td>
                                    <td>
                                        <span class="fw-medium text-dark">{{ $rt->user->name ?? 'Kasir' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border text-uppercase" style="font-size: 0.72rem;">
                                            {{ $rt->payment_method }}
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold text-dark">
                                        Rp {{ number_format($rt->total_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">Belum ada transaksi</td>
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

@section('scripts')
<script>
    // Revenue 7 Days Chart
    const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
    const revenueLabels = {!! json_encode($chartDates) !!};
    const revenueData = {!! json_encode($chartRevenues) !!};

    new Chart(ctxRevenue, {
        type: 'line',
        data: {
            labels: revenueLabels,
            datasets: [{
                label: 'Omzet Penjualan (Rp)',
                data: revenueData,
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                borderWidth: 3,
                tension: 0.35,
                fill: true,
                pointBackgroundColor: '#2563eb',
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + (value >= 1000 ? (value / 1000) + 'k' : value);
                        }
                    },
                    grid: { color: 'rgba(0, 0, 0, 0.05)' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

    // Payment Methods Doughnut Chart
    const ctxPayment = document.getElementById('paymentChart').getContext('2d');
    @php
        $pmLabels = $paymentMethods->pluck('payment_method')->map(fn($v) => strtoupper($v))->toArray();
        $pmCounts = $paymentMethods->pluck('count')->toArray();
    @endphp
    new Chart(ctxPayment, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($pmLabels) !!},
            datasets: [{
                data: {!! json_encode($pmCounts) !!},
                backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 12, padding: 12 }
                }
            },
            cutout: '65%'
        }
    });
</script>
@endsection
