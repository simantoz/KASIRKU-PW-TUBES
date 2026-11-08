@extends('layouts.app')

@section('title', 'Dashboard Owner')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: #fff; font-size: 1.6rem;">Dashboard Owner</h2>
            <div style="color: #8892b0; font-size: 0.85rem;">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</div>
        </div>
        <a href="{{ route('owner.print_report') }}" target="_blank" class="btn btn-primary-custom d-flex align-items-center gap-2">
            <i class="bi bi-file-earmark-pdf"></i>
            <span>Cetak Laporan</span>
        </a>
    </div>

    <!-- KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card">
                <div style="color: #8892b0; font-size: 0.82rem; font-weight: 600;">Pendapatan Hari Ini</div>
                <h3 class="fw-bold mt-2 mb-1" style="color: #fff;">Rp {{ number_format($revenueToday, 0, ',', '.') }}</h3>
                <div class="small fw-semibold" style="color: #34d399;">
                    <i class="bi bi-arrow-up-right"></i> {{ $transactionsToday }} transaksi
                </div>
                <div class="stat-icon" style="background: rgba(16, 185, 129, 0.15); color: #34d399;">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="stat-card">
                <div style="color: #8892b0; font-size: 0.82rem; font-weight: 600;">Pendapatan Bulan Ini</div>
                <h3 class="fw-bold mt-2 mb-1" style="color: #fff;">Rp {{ number_format($revenueThisMonth, 0, ',', '.') }}</h3>
                <div class="small fw-semibold" style="color: #60a5fa;">
                    <i class="bi bi-calendar-check"></i> Akumulasi bulan ini
                </div>
                <div class="stat-icon" style="background: rgba(59, 130, 246, 0.15); color: #60a5fa;">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="stat-card">
                <div style="color: #8892b0; font-size: 0.82rem; font-weight: 600;">Total Transaksi</div>
                <h3 class="fw-bold mt-2 mb-1" style="color: #fff;">{{ number_format($totalTransactions) }}</h3>
                <div class="small fw-semibold" style="color: #a78bfa;">
                    <i class="bi bi-receipt"></i> Selesai
                </div>
                <div class="stat-icon" style="background: rgba(167, 139, 250, 0.15); color: #a78bfa;">
                    <i class="bi bi-cart-check"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="stat-card">
                <div style="color: #8892b0; font-size: 0.82rem; font-weight: 600;">Peringatan Stok</div>
                <h3 class="fw-bold mt-2 mb-1" style="color: {{ $lowStockCount > 0 ? '#f87171' : '#34d399' }};">
                    {{ $lowStockCount }} <span style="font-size: 0.9rem; font-weight: normal; color: #8892b0;">Produk</span>
                </h3>
                <div class="small fw-semibold" style="color: #8892b0;">
                    Total {{ $totalProducts }} menu
                </div>
                <div class="stat-icon" style="background: {{ $lowStockCount > 0 ? 'rgba(239, 68, 68, 0.15)' : 'rgba(16, 185, 129, 0.15)' }}; color: {{ $lowStockCount > 0 ? '#f87171' : '#34d399' }};">
                    <i class="bi bi-exclamation-octagon"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card-custom p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold mb-0 text-white">Tren Penjualan (7 Hari Terakhir)</h5>
                        <div style="color: #8892b0; font-size: 0.8rem;">Grafik omzet penjualan harian</div>
                    </div>
                </div>
                <div style="height: 280px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-custom p-4 h-100">
                <h5 class="fw-bold mb-1 text-white">Metode Pembayaran</h5>
                <div style="color: #8892b0; font-size: 0.8rem; margin-bottom: 1rem;">Distribusi transaksi kasir</div>
                <div style="height: 200px; display: flex; align-items: center; justify-content: center;">
                    <canvas id="paymentChart"></canvas>
                </div>
                <div class="mt-3 pt-3 border-top" style="border-color: rgba(255,255,255,0.08) !important;">
                    <div class="d-flex justify-content-around text-center">
                        @foreach($paymentMethods as $pm)
                            <div>
                                <span class="badge py-1 px-2" style="background: #2d303e; color: #ea7c69; text-transform: uppercase;">{{ $pm->payment_method }}</span>
                                <div class="fw-bold mt-1 text-white" style="font-size: 0.85rem;">{{ $pm->count }}x</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables -->
    <div class="row g-3">
        <div class="col-lg-5">
            <div class="card-custom p-4 h-100">
                <h5 class="fw-bold mb-3 text-white">5 Menu Terlaris</h5>
                <div class="table-responsive">
                    <table class="table table-dark-custom mb-0">
                        <thead>
                            <tr>
                                <th>Menu</th>
                                <th class="text-center">Terjual</th>
                                <th class="text-end">Omzet</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $idx => $tp)
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-white">{{ $tp->product_name }}</div>
                                    </td>
                                    <td class="text-center fw-bold" style="color: #34d399;">{{ $tp->total_qty }} pcs</td>
                                    <td class="text-end fw-semibold">Rp {{ number_format($tp->total_sales, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4" style="color: #8892b0;">Belum ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card-custom p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-white">Pesanan Terkini</h5>
                    <a href="{{ route('owner.transactions') }}" class="btn btn-sm" style="background: #2d303e; color: #ea7c69; border-radius: 10px;">
                        Lihat Semua <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-dark-custom mb-0">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Waktu</th>
                                <th>Kasir</th>
                                <th>Metode</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $rt)
                                <tr>
                                    <td><span class="badge" style="background: #2d303e; color: #fff;">{{ $rt->invoice_code }}</span></td>
                                    <td style="color: #8892b0; font-size: 0.8rem;">{{ $rt->created_at->format('d/m H:i') }}</td>
                                    <td>{{ $rt->user->name ?? 'Kasir' }}</td>
                                    <td><span class="badge text-uppercase" style="background: rgba(234, 124, 105, 0.15); color: #ea7c69;">{{ $rt->payment_method }}</span></td>
                                    <td class="text-end fw-bold text-white">Rp {{ number_format($rt->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4" style="color: #8892b0;">Belum ada transaksi</td>
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
    const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctxRevenue, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartDates) !!},
            datasets: [{
                label: 'Omzet',
                data: {!! json_encode($chartRevenues) !!},
                borderColor: '#ea7c69',
                backgroundColor: 'rgba(234, 124, 105, 0.15)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#ea7c69',
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                    ticks: { color: '#8892b0' }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#8892b0' }
                }
            }
        }
    });

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
                backgroundColor: ['#ea7c69', '#34d399', '#60a5fa', '#fbbf24'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#8892b0', boxWidth: 10 }
                }
            },
            cutout: '70%'
        }
    });
</script>
@endsection
