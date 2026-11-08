@extends('layouts.app')

@section('title', 'Katalog Menu & Barang')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: #fff; font-size: 1.6rem;">Katalog Menu & Master Barang</h2>
            <div style="color: #8892b0; font-size: 0.85rem;">Kelola menu restoran, harga jual kasir, dan persediaan</div>
        </div>
        <button type="button" class="btn btn-primary-custom d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addProductModal">
            <i class="bi bi-plus-circle"></i>
            <span>Tambah Menu Baru</span>
        </button>
    </div>

    <!-- Search & Filter Card -->
    <div class="card-custom p-3 mb-4">
        <form method="GET" action="{{ route('gudang.products.index') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Cari menu atau kode SKU..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="category_id" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="stock_status" class="form-select">
                    <option value="">Semua Stok</option>
                    <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Stok Menipis</option>
                    <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Stok Habis</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary-custom flex-grow-1">Filter</button>
                <a href="{{ route('gudang.products.index') }}" class="btn" style="background: #2d303e; color: #fff; border-radius: 12px;">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="card-custom p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-dark-custom mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Kode</th>
                        <th>Nama Menu</th>
                        <th>Kategori</th>
                        <th class="text-end">Harga Beli</th>
                        <th class="text-end">Harga Jual</th>
                        <th class="text-center">Sisa Stok</th>
                        <th class="text-center">Min</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                        <tr>
                            <td class="ps-4"><span class="badge" style="background: #2d303e; color: #fff;">{{ $p->code }}</span></td>
                            <td><div class="fw-bold text-white">{{ $p->name }}</div></td>
                            <td><span class="badge" style="background: rgba(234, 124, 105, 0.15); color: #ea7c69;">{{ $p->category->name ?? '-' }}</span></td>
                            <td class="text-end" style="color: #8892b0;">Rp {{ number_format($p->cost_price, 0, ',', '.') }}</td>
                            <td class="text-end fw-bold text-white">Rp {{ number_format($p->selling_price, 0, ',', '.') }}</td>
                            <td class="text-center">
                                @if($p->stock <= 0)
                                    <span class="badge" style="background: rgba(239, 68, 68, 0.2); color: #f87171;">Habis</span>
                                @elseif($p->stock <= $p->min_stock)
                                    <span class="badge" style="background: rgba(245, 158, 11, 0.2); color: #fbbf24;">{{ $p->stock }} {{ $p->unit }}</span>
                                @else
                                    <span class="badge" style="background: rgba(16, 185, 129, 0.15); color: #34d399;">{{ $p->stock }} {{ $p->unit }}</span>
                                @endif
                            </td>
                            <td class="text-center" style="color: #8892b0;">{{ $p->min_stock }}</td>
                            <td class="text-center pe-4">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn" style="background: #2d303e; color: #fff;" onclick="editProduct({{ json_encode($p) }})">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button type="button" class="btn" style="background: #2d303e; color: #f87171;" onclick="confirmDeleteProduct({{ $p->id }}, '{{ $p->name }}')">
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
                            <td colspan="8" class="text-center py-5" style="color: #8892b0;">Tidak ada menu yang sesuai kriteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top d-flex justify-content-end" style="border-color: rgba(255,255,255,0.08) !important;">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Modal Tambah Menu -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content dark-modal shadow">
            <form action="{{ route('gudang.products.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-white">Tambah Menu Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold" style="color: #8892b0;">Kode / SKU</label>
                            <input type="text" name="code" class="form-control" placeholder="BRG001" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold" style="color: #8892b0;">Kategori</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold" style="color: #8892b0;">Nama Menu Makanan / Minuman</label>
                            <input type="text" name="name" class="form-control" placeholder="Special Cheese Burger" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold" style="color: #8892b0;">Harga Modal (Rp)</label>
                            <input type="number" name="cost_price" class="form-control" placeholder="0" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold" style="color: #8892b0;">Harga Jual (Rp)</label>
                            <input type="number" name="selling_price" class="form-control" placeholder="0" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold" style="color: #8892b0;">Stok Awal</label>
                            <input type="number" name="stock" class="form-control" placeholder="0" min="0" value="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold" style="color: #8892b0;">Batas Min. Stok</label>
                            <input type="number" name="min_stock" class="form-control" placeholder="5" min="0" value="5" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold" style="color: #8892b0;">Satuan</label>
                            <input type="text" name="unit" class="form-control" value="pcs" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary-custom">Simpan Menu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Menu -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content dark-modal shadow">
            <form id="editProductForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-white">Edit Data Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold" style="color: #8892b0;">Kode / SKU</label>
                            <input type="text" name="code" id="editCode" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold" style="color: #8892b0;">Kategori</label>
                            <select name="category_id" id="editCategoryId" class="form-select" required>
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold" style="color: #8892b0;">Nama Menu</label>
                            <input type="text" name="name" id="editName" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold" style="color: #8892b0;">Harga Modal (Rp)</label>
                            <input type="number" name="cost_price" id="editCostPrice" class="form-control" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold" style="color: #8892b0;">Harga Jual (Rp)</label>
                            <input type="number" name="selling_price" id="editSellingPrice" class="form-control" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold" style="color: #8892b0;">Stok Saat Ini</label>
                            <input type="number" name="stock" id="editStock" class="form-control" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold" style="color: #8892b0;">Min. Stok</label>
                            <input type="number" name="min_stock" id="editMinStock" class="form-control" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold" style="color: #8892b0;">Satuan</label>
                            <input type="text" name="unit" id="editUnit" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary-custom">Perbarui Menu</button>
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

        const modal = new bootstrap.Modal(document.getElementById('editProductModal'));
        modal.show();
    }

    function confirmDeleteProduct(id, name) {
        Swal.fire({
            title: 'Hapus Menu?',
            text: `Hapus "${name}"?`,
            icon: 'warning',
            background: '#252836',
            color: '#fff',
            showCancelButton: true,
            confirmButtonColor: '#ea7c69',
            confirmButtonText: 'Ya, Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-prod-${id}`).submit();
            }
        });
    }
</script>
@endsection
