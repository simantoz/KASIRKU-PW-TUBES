@extends('layouts.app')

@section('title', 'Riwayat Shift Kasir')
@section('page_title', 'Riwayat Shift & Transaksi Kasir')

@section('content')
<div class="container-fluid px-0">
    <!-- Shift Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card card-custom stat-card border-0 bg-white shadow-sm">
                <div class="text-muted fw-semibold small">Total Transaksi Shift Hari Ini</div>
                <h3 class="fw-bold text-dark mt-2 mb-1">{{ number_format($shiftSummary->count ?? 0) }} <span style="font-size: 1rem; font-weight: normal;">Transaksi</span></h3>
                <div class="text-success small fw-medium">
                    <i class="bi bi-clock-history"></i> Transaksi oleh akun {{ Auth::user()->name }}
                </div>
                <div class="stat-icon bg-primary-subtle text-primary">
                    <i class="bi bi-receipt"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-custom stat-card border-0 bg-white shadow-sm">
                <div class="text-muted fw-semibold small">Total Omzet Kasir Shift Hari Ini</div>
                <h3 class="fw-bold text-success mt-2 mb-1">Rp {{ number_format($shiftSummary->total ?? 0, 0, ',', '.') }}</h3>
                <div class="text-muted small fw-medium">
                    <i class="bi bi-cash-coin"></i> Total uang yang diproses pada shift ini
                </div>
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card card-custom p-3 mb-4 border-0 shadow-sm bg-white">
        <form method="GET" action="{{ route('kasir.transactions') }}" class="row g-2 align-items-center">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white text-muted"><i class="bi bi-calendar3"></i></span>
                    <input type="date" name="date" class="form-control" value="{{ request('date', \Carbon\Carbon::today()->format('Y-m-d')) }}">
                </div>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary-custom flex-grow-1">
                    <i class="bi bi-funnel"></i> Tampilkan
                </button>
                <a href="{{ route('kasir.transactions') }}" class="btn btn-light border" title="Hari Ini">
                    Hari Ini
                </a>
            </div>
            <div class="col-md-5 text-md-end text-muted small">
                <i class="bi bi-info-circle me-1"></i> Anda hanya melihat riwayat transaksi yang Anda layani sendiri.
            </div>
        </form>
    </div>

    <!-- Shift Transactions Table -->
    <div class="card card-custom p-0 border-0 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">No. Invoice</th>
                        <th>Waktu Transaksi</th>
                        <th>Nama Pelanggan</th>
                        <th>Metode Bayar</th>
                        <th>Jumlah Item</th>
                        <th class="text-end">Total Bayar</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($myTransactions as $t)
                        <tr>
                            <td class="ps-3">
                                <span class="badge bg-dark-subtle text-dark font-monospace fw-semibold">{{ $t->invoice_code }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $t->created_at->format('d/m/Y') }}</div>
                                <div class="text-muted small">{{ $t->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td>
                                <span class="text-dark">{{ $t->customer_name }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border text-uppercase" style="font-size: 0.75rem;">
                                    {{ $t->payment_method }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary">
                                    {{ $t->details->sum('qty') }} pcs ({{ $t->details->count() }} macam)
                                </span>
                            </td>
                            <td class="text-end fw-bold text-success">
                                Rp {{ number_format($t->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                <a href="{{ route('kasir.receipt', $t->id) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-printer"></i> Cetak Struk
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-clock-history fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                Belum ada transaksi yang diproses pada tanggal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top d-flex justify-content-end">
            {{ $myTransactions->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
