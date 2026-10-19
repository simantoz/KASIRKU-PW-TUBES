@extends('layouts.app')

@section('title', 'Manajemen Akun Pegawai')
@section('page_title', 'Manajemen Akun Pegawai (Hak Akses Multi-Role)')

@section('content')
<div class="container-fluid px-0">
    <div class="card card-custom p-4 mb-4 border-0 shadow-sm bg-white">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h5 class="fw-bold text-dark mb-1">Daftar Akun Pengguna</h5>
                <p class="text-muted mb-0 small">Kelola hak akses untuk Owner, Admin Gudang, dan Kasir toko.</p>
            </div>
            <button type="button" class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="bi bi-person-plus-fill me-1"></i> Tambah Pegawai Baru
            </button>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card card-custom p-0 border-0 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Nama Pegawai</th>
                        <th>Email</th>
                        <th>No. Telepon</th>
                        <th>Hak Akses (Role)</th>
                        <th>Status Akun</th>
                        <th>Dibuat Pada</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        <tr>
                            <td class="ps-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar" style="width: 34px; height: 34px; font-size: 0.85rem;">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $u->name }}</div>
                                        @if($u->id === Auth::id())
                                            <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.68rem;">Akun Anda</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-dark">{{ $u->email }}</td>
                            <td class="text-muted">{{ $u->phone ?? '-' }}</td>
                            <td>
                                <span class="badge badge-role badge-role-{{ $u->role }}">
                                    {{ ucfirst($u->role) }}
                                </span>
                            </td>
                            <td>
                                @if($u->is_active)
                                    <span class="badge bg-success-subtle text-success">
                                        <i class="bi bi-check-circle me-1"></i> Aktif
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger">
                                        <i class="bi bi-x-circle me-1"></i> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="text-muted small">{{ $u->created_at->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-primary" onclick="editUser({{ json_encode($u) }})">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    @if($u->id !== Auth::id())
                                        <button type="button" class="btn btn-outline-danger" onclick="confirmDelete({{ $u->id }}, '{{ $u->name }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        <form id="delete-form-{{ $u->id }}" action="{{ route('owner.users.destroy', $u->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top d-flex justify-content-end">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Modal Tambah Pegawai -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('owner.users.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-light">
                    <h6 class="modal-title fw-bold">Tambah Akun Pegawai</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Rian Pratama" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Alamat Email</label>
                        <input type="email" name="email" class="form-control" placeholder="pegawai@kasirku.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Kata Sandi (Minimal 6 Karakter)</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Tingkatan Hak Akses (Role)</label>
                        <select name="role" class="form-select" required>
                            <option value="kasir">Kasir (Front-Office / Transaksi)</option>
                            <option value="gudang">Admin Gudang (Inventory & Master Barang)</option>
                            <option value="owner">Owner / Manajer (Pemilik Toko)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nomor Telepon (WhatsApp)</label>
                        <input type="text" name="phone" class="form-control" placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="createIsActive" checked value="1">
                        <label class="form-check-label fw-semibold small" for="createIsActive">Akun Aktif (Bisa Login)</label>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom">Simpan Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Pegawai -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-light">
                    <h6 class="modal-title fw-bold">Edit Akun Pegawai</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nama Lengkap</label>
                        <input type="text" name="name" id="editName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Alamat Email</label>
                        <input type="email" name="email" id="editEmail" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Kata Sandi Baru (Kosongkan jika tidak diganti)</label>
                        <input type="password" name="password" id="editPassword" class="form-control" placeholder="••••••••" minlength="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Tingkatan Hak Akses (Role)</label>
                        <select name="role" id="editRole" class="form-select" required>
                            <option value="kasir">Kasir (Front-Office / Transaksi)</option>
                            <option value="gudang">Admin Gudang (Inventory & Master Barang)</option>
                            <option value="owner">Owner / Manajer (Pemilik Toko)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nomor Telepon (WhatsApp)</label>
                        <input type="text" name="phone" id="editPhone" class="form-control">
                    </div>
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="editIsActive" value="1">
                        <label class="form-check-label fw-semibold small" for="editIsActive">Akun Aktif (Bisa Login)</label>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom">Perbarui Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function editUser(user) {
        $('#editUserForm').attr('action', `/owner/users/${user.id}`);
        $('#editName').val(user.name);
        $('#editEmail').val(user.email);
        $('#editPassword').val('');
        $('#editRole').val(user.role);
        $('#editPhone').val(user.phone || '');
        $('#editIsActive').prop('checked', user.is_active == 1);

        const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
        modal.show();
    }

    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Hapus Akun Pegawai?',
            text: `Apakah Anda yakin ingin menghapus akun pegawai "${name}"? Tindakan ini tidak dapat dibatalkan.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    }
</script>
@endsection
