@extends('layouts.app')

@section('title', 'Riwayat Shift Kasir')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: #fff; font-size: 1.6rem;">Riwayat Transaksi Saya</h2>
            <div style="color: #8892b0; font-size: 0.85rem;">Transaksi yang Anda layani pada shift ini</div>
        </div>
        <a href="{{ route('kasir.pos') }}" class="btn btn-primary-custom d-flex align-items-center gap-2">
            <i class="bi bi-calculator"></i>
            <span>Buka Kasir POS</span>
        </a>
    </div>

    <!-- Shift KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="stat-card">
                <div style="color: #8892b0; font-size: 0.82rem; font-weight: 600;">Total Transaksi Hari Ini</div>
                <h3 class="fw-bold mt-2 mb-1" style="color: #fff;">{{ number_format($shiftSummary->count ?? 0) }} <span style="font-size: 0.9rem; font-weight: normal; color: #8892b0;">Pesanan</span></h3>
                <div class="small fw-semibold" style="color: #34d399;">
                    <i class="bi bi-check-circle"></i> Berhasil diproses
                </div>
                <div class="stat-icon" style="background: rgba(16, 185, 129, 0.15); color: #34d399;">
                    <i class="bi bi-receipt"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="stat-card">
                <div style="color: #8892b0; font-size: 0.82rem; font-weight: 600;">Total Omzet Kasir Shift Ini</div>
                <h3 class="fw-bold mt-2 mb-1" style="color: #ea7c69;">Rp {{ number_format($shiftSummary->total ?? 0, 0, ',', '.') }}</h3>
                <div class="small fw-semibold" style="color: #8892b0;">
                    Uang masuk shift {{ Auth::user()->name }}
                </div>
                <div class="stat-icon" style="background: rgba(234, 124, 105, 0.15); color: #ea7c69;">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card-custom p-3 mb-4">
        <form method="GET" action="{{ route('kasir.transactions') }}" class="row g-2 align-items-center">
            <div class="col-md-4">
                <input type="date" name="date" class="form-control" value="{{ request('date', \Carbon\Carbon::today()->format('Y-m-d')) }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary-custom flex-grow-1">Tampilkan</button>
                <a href="{{ route('kasir.transactions') }}" class="btn" style="background: #2d303e; color: #fff; border-radius: 12px;">Hari Ini</a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="card-custom p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-dark-custom mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">No. Invoice</th>
                        <th>Waktu</th>
                        <th>Pelanggan</th>
                        <th>Metode Bayar</th>
                        <th>Item</th>
                        <th class="text-end">Total Bayar</th>
                        <th class="text-center pe-4">Struk</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($myTransactions as $t)
                        <tr>
                            <td class="ps-4"><span class="badge" style="background: #2d303e; color: #fff;">{{ $t->invoice_code }}</span></td>
                            <td>
                                <div class="text-white">{{ $t->created_at->format('d/m/Y') }}</div>
                                <small style="color: #8892b0;">{{ $t->created_at->format('H:i') }} WIB</small>
                            </td>
                            <td class="text-white">{{ $t->customer_name }}</td>
                            <td><span class="badge text-uppercase" style="background: rgba(234, 124, 105, 0.15); color: #ea7c69;">{{ $t->payment_method }}</span></td>
                            <td><span class="badge" style="background: #2d303e; color: #fff;">{{ $t->details->sum('qty') }} pcs</span></td>
                            <td class="text-end fw-bold" style="color: #ea7c69;">Rp {{ number_format($t->total_amount, 0, ',', '.') }}</td>
                            <td class="text-center pe-4">
                                <a href="{{ route('kasir.receipt', $t->id) }}" target="_blank" class="btn btn-sm" style="background: #2d303e; color: #ea7c69; border-radius: 8px;">
                                    <i class="bi bi-printer"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5" style="color: #8892b0;">Belum ada transaksi pada tanggal ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top d-flex justify-content-end" style="border-color: rgba(255,255,255,0.08) !important;">
            {{ $myTransactions->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
