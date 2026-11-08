@extends('layouts.app')

@section('title', 'Kategori Menu')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: #fff; font-size: 1.6rem;">Kategori Barang</h2>
            <div style="color: #8892b0; font-size: 0.85rem;">Burger, Noodles, Drinks, Desserts, dsb.</div>
        </div>
        <button type="button" class="btn btn-primary-custom d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="bi bi-plus-circle"></i>
            <span>Tambah Kategori</span>
        </button>
    </div>

    <!-- Table -->
    <div class="card-custom p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-dark-custom mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 60px;">No</th>
                        <th>Nama Kategori</th>
                        <th>Slug Code</th>
                        <th>Deskripsi</th>
                        <th class="text-center">Jumlah Menu</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $idx => $cat)
                        <tr>
                            <td class="ps-4" style="color: #8892b0;">{{ $categories->firstItem() + $idx }}</td>
                            <td><div class="fw-bold text-white">{{ $cat->name }}</div></td>
                            <td><span class="badge" style="background: #2d303e; color: #fff;">{{ $cat->slug }}</span></td>
                            <td style="color: #8892b0;">{{ $cat->description ?: '-' }}</td>
                            <td class="text-center">
                                <span class="badge" style="background: rgba(234, 124, 105, 0.15); color: #ea7c69;">
                                    {{ $cat->products_count }} Menu
                                </span>
                            </td>
                            <td class="text-center pe-4">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn" style="background: #2d303e; color: #fff;" onclick="editCategory({{ json_encode($cat) }})">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button type="button" class="btn" style="background: #2d303e; color: #f87171;" onclick="confirmDeleteCategory({{ $cat->id }}, '{{ $cat->name }}', {{ $cat->products_count }})">
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
                            <td colspan="6" class="text-center py-5" style="color: #8892b0;">Belum ada kategori yang dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top d-flex justify-content-end" style="border-color: rgba(255,255,255,0.08) !important;">
            {{ $categories->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Modal Tambah Kategori -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content dark-modal shadow">
            <form action="{{ route('gudang.categories.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-white">Tambah Kategori Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold" style="color: #8892b0;">Nama Kategori</label>
                        <input type="text" name="name" class="form-control" placeholder="Burger, Drinks, dsb." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold" style="color: #8892b0;">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary-custom">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Kategori -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content dark-modal shadow">
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-white">Edit Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold" style="color: #8892b0;">Nama Kategori</label>
                        <input type="text" name="name" id="editCatName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold" style="color: #8892b0;">Deskripsi</label>
                        <textarea name="description" id="editCatDesc" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary-custom">Perbarui</button>
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
                title: 'Tidak Bisa Dihapus',
                text: `Kategori "${name}" masih memiliki ${productsCount} menu.`,
                background: '#252836',
                color: '#fff',
                confirmButtonColor: '#ea7c69'
            });
            return;
        }

        Swal.fire({
            title: 'Hapus Kategori?',
            text: `Hapus kategori "${name}"?`,
            icon: 'warning',
            background: '#252836',
            color: '#fff',
            showCancelButton: true,
            confirmButtonColor: '#ea7c69',
            confirmButtonText: 'Ya, Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-cat-${id}`).submit();
            }
        });
    }
</script>
@endsection
