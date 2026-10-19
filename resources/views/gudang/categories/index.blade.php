@extends('layouts.app')

@section('title', 'Kategori Barang')
@section('page_title', 'Master Kategori Barang')

@section('content')
<div class="container-fluid px-0">
    <div class="card card-custom p-4 mb-4 border-0 shadow-sm bg-white">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h5 class="fw-bold text-dark mb-1">Daftar Kategori Produk</h5>
                <p class="text-muted mb-0 small">Kategorisasi memudahkan kasir mencari produk dan pengelompokan barang gudang.</p>
            </div>
            <button type="button" class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="bi bi-plus-circle me-1"></i> Tambah Kategori Baru
            </button>
        </div>
    </div>

    <!-- Category Table -->
    <div class="card card-custom p-0 border-0 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="width: 50px;">No</th>
                        <th>Nama Kategori</th>
                        <th>Slug (URL Code)</th>
                        <th>Deskripsi</th>
                        <th class="text-center">Jumlah Produk</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $idx => $cat)
                        <tr>
                            <td class="ps-3 text-muted">{{ $categories->firstItem() + $idx }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $cat->name }}</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-muted font-monospace border">{{ $cat->slug }}</span>
                            </td>
                            <td class="text-muted small">
                                {{ $cat->description ?: '-' }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary-subtle text-primary fw-semibold px-2 py-1">
                                    {{ $cat->products_count }} Produk
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-primary" onclick="editCategory({{ json_encode($cat) }})">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-outline-danger" onclick="confirmDeleteCategory({{ $cat->id }}, '{{ $cat->name }}', {{ $cat->products_count }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <form id="delete-cat-{{ $cat->id }}" action="{{ route('gudang.categories.destroy', $cat->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                Belum ada kategori yang dibuat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top d-flex justify-content-end">
            {{ $categories->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Modal Tambah Kategori -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('gudang.categories.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-light">
                    <h6 class="modal-title fw-bold">Tambah Kategori Baru</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nama Kategori</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Makanan Instan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Deskripsi Singkat (Opsional)</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Keterangan jenis produk..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Kategori -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-light">
                    <h6 class="modal-title fw-bold">Edit Kategori</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nama Kategori</label>
                        <input type="text" name="name" id="editCatName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Deskripsi Singkat (Opsional)</label>
                        <textarea name="description" id="editCatDesc" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom">Perbarui Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function editCategory(cat) {
        $('#editCategoryForm').attr('action', `/gudang/categories/${cat.id}`);
        $('#editCatName').val(cat.name);
        $('#editCatDesc').val(cat.description || '');

        const modal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
        modal.show();
    }

    function confirmDeleteCategory(id, name, productsCount) {
        if (productsCount > 0) {
            Swal.fire({
                icon: 'error',
                title: 'Tidak Bisa Menghapus',
                text: `Kategori "${name}" masih memiliki ${productsCount} produk terkait. Pindahkan atau hapus produk terlebih dahulu.`
            });
            return;
        }

        Swal.fire({
            title: 'Hapus Kategori?',
            text: `Apakah Anda yakin ingin menghapus kategori "${name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-cat-${id}`).submit();
            }
        });
    }
</script>
@endsection
