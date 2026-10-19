@extends('layouts.app')

@section('title', 'Master Data Barang')
@section('page_title', 'Master Data Barang & Inventaris')

@section('content')
<div class="container-fluid px-0">
    <!-- Header & Action -->
    <div class="card card-custom p-4 mb-4 border-0 shadow-sm bg-white">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h5 class="fw-bold text-dark mb-1">Katalog & Stok Barang</h5>
                <p class="text-muted mb-0 small">Kelola data master barang, barcode/SKU, penetapan harga jual, dan stok gudang.</p>
            </div>
            <button type="button" class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="bi bi-plus-circle me-1"></i> Tambah Barang Baru
            </button>
        </div>
    </div>

    <!-- Search & Filter Card -->
    <div class="card card-custom p-3 mb-4 border-0 shadow-sm">
        <form method="GET" action="{{ route('gudang.products.index') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama barang atau kode barcode..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="category_id" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="stock_status" class="form-select">
                    <option value="">Semua Kondisi Stok</option>
                    <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Stok Menipis (&le; Min)</option>
                    <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Stok Habis (0)</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary-custom flex-grow-1">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                <a href="{{ route('gudang.products.index') }}" class="btn btn-light border" title="Reset">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="card card-custom p-0 border-0 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Kode/SKU</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th class="text-end">Harga Beli</th>
                        <th class="text-end">Harga Jual</th>
                        <th class="text-center">Sisa Stok</th>
                        <th class="text-center">Min. Stok</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                        @php
                            $isLow = $p->stock <= $p->min_stock;
                            $isOut = $p->stock <= 0;
                            $profit = $p->selling_price - $p->cost_price;
                        @endphp
                        <tr>
                            <td class="ps-3">
                                <span class="badge bg-light text-dark font-monospace border fw-semibold">{{ $p->code }}</span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $p->name }}</div>
                                <small class="text-muted">Margin Profit: <span class="text-success fw-semibold">+Rp {{ number_format($profit, 0, ',', '.') }}</span></small>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                    {{ $p->category->name ?? '-' }}
                                </span>
                            </td>
                            <td class="text-end text-muted">
                                Rp {{ number_format($p->cost_price, 0, ',', '.') }}
                            </td>
                            <td class="text-end fw-bold text-dark">
                                Rp {{ number_format($p->selling_price, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                @if($isOut)
                                    <span class="badge bg-danger text-white px-2 py-1">Habis (0)</span>
                                @elseif($isLow)
                                    <span class="badge bg-danger text-white px-2 py-1" title="Peringatan: Stok menipis!">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $p->stock }} {{ $p->unit }}
                                    </span>
                                @else
                                    <span class="badge bg-success-subtle text-success fw-bold px-2 py-1">
                                        {{ $p->stock }} {{ $p->unit }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-center text-muted">
                                {{ $p->min_stock }} {{ $p->unit }}
                            </td>
                            <td>
                                @if($p->is_active)
                                    <span class="badge bg-success-subtle text-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-primary" onclick="editProduct({{ json_encode($p) }})">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger" onclick="confirmDeleteProduct({{ $p->id }}, '{{ $p->name }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <form id="delete-prod-{{ $p->id }}" action="{{ route('gudang.products.destroy', $p->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                <i class="bi bi-box-seam fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                Tidak ada data barang yang sesuai kriteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top d-flex justify-content-end">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Modal Tambah Barang -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('gudang.products.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-light">
                    <h6 class="modal-title fw-bold">Tambah Data Barang Baru</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Kode Barcode / SKU</label>
                            <input type="text" name="code" class="form-control" placeholder="Contoh: BRG001 atau scan barcode" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Kategori</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Nama Barang</label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: Indomie Goreng Spesial 85g" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Harga Beli / Modal (Rp)</label>
                            <input type="number" name="cost_price" class="form-control" placeholder="0" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Harga Jual Kasir (Rp)</label>
                            <input type="number" name="selling_price" class="form-control" placeholder="0" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Stok Awal</label>
                            <input type="number" name="stock" class="form-control" placeholder="0" min="0" value="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Batas Peringatan (Min. Stok)</label>
                            <input type="number" name="min_stock" class="form-control" placeholder="5" min="0" value="5" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Satuan / Unit</label>
                            <input type="text" name="unit" class="form-control" placeholder="pcs / bks / btl" value="pcs" required>
                        </div>
                        <div class="col-12 mt-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="prodIsActive" checked value="1">
                                <label class="form-check-label fw-semibold small" for="prodIsActive">Barang Aktif (Tampil di kasir)</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom">Simpan Data Barang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Barang -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <form id="editProductForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-light">
                    <h6 class="modal-title fw-bold">Edit Data Barang</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Kode Barcode / SKU</label>
                            <input type="text" name="code" id="editCode" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Kategori</label>
                            <select name="category_id" id="editCategoryId" class="form-select" required>
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Nama Barang</label>
                            <input type="text" name="name" id="editName" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Harga Beli / Modal (Rp)</label>
                            <input type="number" name="cost_price" id="editCostPrice" class="form-control" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Harga Jual Kasir (Rp)</label>
                            <input type="number" name="selling_price" id="editSellingPrice" class="form-control" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Stok Saat Ini</label>
                            <input type="number" name="stock" id="editStock" class="form-control" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Batas Peringatan (Min. Stok)</label>
                            <input type="number" name="min_stock" id="editMinStock" class="form-control" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Satuan / Unit</label>
                            <input type="text" name="unit" id="editUnit" class="form-control" required>
                        </div>
                        <div class="col-12 mt-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="editIsActive" value="1">
                                <label class="form-check-label fw-semibold small" for="editIsActive">Barang Aktif (Tampil di kasir)</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom">Perbarui Data Barang</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function editProduct(prod) {
        $('#editProductForm').attr('action', `/gudang/products/${prod.id}`);
        $('#editCode').val(prod.code);
        $('#editCategoryId').val(prod.category_id);
        $('#editName').val(prod.name);
        $('#editCostPrice').val(prod.cost_price);
        $('#editSellingPrice').val(prod.selling_price);
        $('#editStock').val(prod.stock);
        $('#editMinStock').val(prod.min_stock);
        $('#editUnit').val(prod.unit);
        $('#editIsActive').prop('checked', prod.is_active == 1);

        const modal = new bootstrap.Modal(document.getElementById('editProductModal'));
        modal.show();
    }

    function confirmDeleteProduct(id, name) {
        Swal.fire({
            title: 'Hapus Barang?',
            text: `Apakah Anda yakin ingin menghapus "${name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-prod-${id}`).submit();
            }
        });
    }
</script>
@endsection
