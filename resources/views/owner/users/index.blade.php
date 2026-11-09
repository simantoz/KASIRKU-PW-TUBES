@extends('layouts.app')

@section('title', 'Manajemen Pegawai')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: #fff; font-size: 1.6rem;">Manajemen Akun Pegawai</h2>
            <div style="color: #8892b0; font-size: 0.85rem;">Hak akses Owner, Admin Gudang, dan Kasir</div>
        </div>
        <button type="button" class="btn btn-primary-custom d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="bi bi-person-plus-fill"></i>
            <span>Tambah Pegawai</span>
        </button>
    </div>

    <!-- Table -->
    <div class="card-custom p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-dark-custom mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Nama Pegawai</th>
                        <th>Email</th>
                        <th>No. Telepon</th>
                        <th>Role Akses</th>
                        <th>Status</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 34px; height: 34px; background: #2d303e; color: #ea7c69;">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-white">{{ $u->name }}</div>
                                        @if($u->id === Auth::id())
                                            <span class="badge" style="background: rgba(234, 124, 105, 0.2); color: #ea7c69; font-size: 0.65rem;">Akun Anda</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-white">{{ $u->email }}</td>
                            <td style="color: #8892b0;">{{ $u->phone ?? '-' }}</td>
                            <td>
                                <span class="badge badge-role badge-role-{{ $u->role }}">{{ ucfirst($u->role) }}</span>
                            </td>
                            <td>
                                @if($u->is_active)
                                    <span class="badge" style="background: rgba(16, 185, 129, 0.15); color: #34d399;">Aktif</span>
                                @else
                                    <span class="badge" style="background: rgba(239, 68, 68, 0.15); color: #f87171;">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn" style="background: #2d303e; color: #fff;" title="Edit" onclick="editUser({{ json_encode($u) }})">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    @if($u->id !== Auth::id())
                                        @if($u->is_active)
                                            <form id="toggle-form-{{ $u->id }}" action="{{ route('owner.users.toggle_status', $u->id) }}" method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                            <button type="button" class="btn" style="background: #2d303e; color: #fbbf24;" title="Nonaktifkan" onclick="confirmToggle({{ $u->id }}, '{{ $u->name }}', false)">
                                                <i class="bi bi-toggle-on"></i>
                                            </button>
                                        @else
                                            <form id="toggle-form-{{ $u->id }}" action="{{ route('owner.users.toggle_status', $u->id) }}" method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                            <button type="button" class="btn" style="background: #2d303e; color: #34d399;" title="Aktifkan" onclick="confirmToggle({{ $u->id }}, '{{ $u->name }}', true)">
                                                <i class="bi bi-toggle-off"></i>
                                            </button>
                                        @endif
                                        <button type="button" class="btn" style="background: #2d303e; color: #f87171;" title="Hapus" onclick="confirmDelete({{ $u->id }}, '{{ $u->name }}')">
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
        <div class="p-3 border-top d-flex justify-content-end" style="border-color: rgba(255,255,255,0.08) !important;">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Modal Tambah Pegawai -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content dark-modal shadow">
            <form action="{{ route('owner.users.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-white">Tambah Pegawai Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold" style="color: #8892b0;">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" placeholder="Rian Pratama" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold" style="color: #8892b0;">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="pegawai@kasirku.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold" style="color: #8892b0;">Kata Sandi</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold" style="color: #8892b0;">Role Hak Akses</label>
                        <select name="role" class="form-select" required>
                            <option value="kasir">Kasir (Front-Office / Transaksi)</option>
                            <option value="gudang">Admin Gudang (Inventory & Menu)</option>
                            <option value="owner">Owner / Manajer</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold" style="color: #8892b0;">No. WhatsApp</label>
                        <input type="text" name="phone" class="form-control" placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="addIsActive" checked>
                        <label class="form-check-label small" style="color: #8892b0;" for="addIsActive">
                            Aktifkan akun ini langsung setelah dibuat
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary-custom">Simpan Pegawai</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Pegawai -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content dark-modal shadow">
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-white">Edit Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold" style="color: #8892b0;">Nama Lengkap</label>
                        <input type="text" name="name" id="editName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold" style="color: #8892b0;">Email</label>
                        <input type="email" name="email" id="editEmail" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold" style="color: #8892b0;">Kata Sandi Baru (Kosongkan bila tidak diganti)</label>
                        <input type="password" name="password" id="editPassword" class="form-control" minlength="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold" style="color: #8892b0;">Role Hak Akses</label>
                        <select name="role" id="editRole" class="form-select" required>
                            <option value="kasir">Kasir (Front-Office / Transaksi)</option>
                            <option value="gudang">Admin Gudang (Inventory & Menu)</option>
                            <option value="owner">Owner / Manajer</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold" style="color: #8892b0;">No. WhatsApp</label>
                        <input type="text" name="phone" id="editPhone" class="form-control">
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="editIsActive">
                        <label class="form-check-label small" style="color: #8892b0;" for="editIsActive">
                            Akun Aktif (bisa login)
                        </label>
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
    function editUser(user) {
        $('#editUserForm').attr('action', `/owner/users/${user.id}`);
        $('#editName').val(user.name);
        $('#editEmail').val(user.email);
        $('#editPassword').val('');
        $('#editRole').val(user.role);
        $('#editPhone').val(user.phone || '');
        $('#editIsActive').prop('checked', user.is_active == 1 || user.is_active === true || user.is_active === '1');

        const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
        modal.show();
    }

    function confirmToggle(id, name, activate) {
        const action = activate === false ? 'menonaktifkan' : 'mengaktifkan';
        const statusText = activate === false ? 'Nonaktifkan' : 'Aktifkan';
        Swal.fire({
            title: `${statusText} Pegawai?`,
            text: `Yakin ingin ${action} akun "${name}"?`,
            icon: 'question',
            background: '#252836',
            color: '#fff',
            showCancelButton: true,
            confirmButtonColor: '#ea7c69',
            confirmButtonText: `Ya, ${statusText}`
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`toggle-form-${id}`).submit();
            }
        });
    }

    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Hapus Pegawai?',
            text: `Hapus akun "${name}"?`,
            icon: 'warning',
            background: '#252836',
            color: '#fff',
            showCancelButton: true,
            confirmButtonColor: '#ea7c69',
            confirmButtonText: 'Ya, Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    }
</script>
@endsection
