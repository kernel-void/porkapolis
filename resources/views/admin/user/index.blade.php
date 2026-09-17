@extends('layouts.master')

@section('title', $pengaturan->nama_aplikasi . ' | Data User')
@section('content')
<div class="container-fluid">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pengguna</li>
    </ol>
    </nav>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-times-circle"></i> {{ session('error') }}
            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data User</h1>
    </div>
    
    <div class="card shadow mb-4 border-bottom-{{ $pengaturan->tema }}">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Data User</h6>
            <div class="d-flex align-items-center">
                {{-- Desktop: Tombol Biasa --}}
                <div class="d-none d-md-flex gap-1">
                    @can('user.create')
                        <button class="btn btn-primary btn-sm mr-1" data-bs-toggle="modal" data-bs-target="#createUserModal">
                            <i class="fas fa-plus"></i> Tambah User
                        </button>
                    @endcan
                    @can('user.export')
                        <button type="submit" form="printForm" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-print"></i> Export PDF
                        </button>
                    @endcan
                    @can('user.restore')
                        <button class="btn btn-secondary btn-sm ml-1" data-bs-toggle="modal" data-bs-target="#trashUserModal">
                            <i class="fas fa-trash-restore"></i> Data Terhapus ({{ $trashed->count() }})
                        </button>
                    @endcan
                </div>

                {{-- Mobile: tombol tunggal kalau cuma 1 aksi, selain itu hamburger --}}
                @php
                    $mobileActions = collect([
                        auth()->user()->can('user.create'),
                        auth()->user()->can('user.export'),
                        auth()->user()->can('user.restore'),
                    ])->filter()->count();
                @endphp

                @if ($mobileActions === 1)
                    <div class="d-md-none">
                        @if (auth()->user()->can('user.create'))
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createUserModal">
                                <i class="fas fa-plus"></i> Tambah User
                            </button>
                        @elseif (auth()->user()->can('user.export'))
                            <button type="submit" form="printForm" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-print"></i> Export PDF
                            </button>
                        @else
                            <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#trashUserModal">
                                <i class="fas fa-trash-restore"></i> Data Terhapus ({{ $trashed->count() }})
                            </button>
                        @endif
                    </div>
                @elseif ($mobileActions > 1)
                    <div class="dropdown no-arrow d-md-none">
                        <a class="dropdown-toggle" href="#" role="button" id="mobileMenuButton"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-bars fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="mobileMenuButton">
                            <div class="dropdown-header">Opsi:</div>
                            @can('user.create')
                                <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#createUserModal">
                                    <i class="fas fa-plus fa-sm fa-fw mr-2 text-gray-400"></i> Tambah User
                                </button>
                            @endcan
                            @can('user.export')
                                <button type="submit" form="printForm" class="dropdown-item">
                                    <i class="fas fa-print fa-sm fa-fw mr-2 text-gray-400"></i> Export PDF
                                </button>
                            @endcan
                            @can('user.restore')
                                <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#trashUserModal">
                                    <i class="fas fa-trash-restore fa-sm fa-fw mr-2 text-gray-400"></i> Data Terhapus ({{ $trashed->count() }})
                                </button>
                            @endcan
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="card-body">
            <form id="printForm" action="{{ route('admin.user.exportPdf') }}" method="POST" target="_blank">
                @csrf
            </form>
            <div class="table-responsive pt-2">
                <table class="table table-bordered text-center" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            @can('user.export')
                                <th>
                                    <input type="checkbox" id="checkAll" class="mr-1">
                                    No
                                </th>
                            @else
                                <th>No</th>
                            @endcan
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Status</th>
                            <th>Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $key => $user)
                        <tr>
                            @can('user.export')
                                <td>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <input type="checkbox" name="user_ids[]" value="{{ $user->id_users }}" form="printForm" class="row-check mr-2">
                                        <span>{{ $users->firstItem() + $key }}</span>
                                    </div>
                                </td>
                            @else
                                <td>{{ $users->firstItem() + $key }}</td>
                            @endcan
                            <td>{{ $user->name }} </td>
                            <td class="font-weight-bold">{{ $user->username }}</td>
                            <td>
                                @if($user->is_online)
                                    <span class="badge bg-success text-light">
                                        <i class="fas fa-circle" style="color: #ffffff;"></i> ONLINE
                                    </span><br>
                                @else
                                    <small class="badge bg-secondary text-light">
                                        Terakhir aktif: {{ $user->last_seen_text }}
                                    </small><br>
                                @endif
                            
                                <small>
                                    {{ $user->online_ip ?? '-' }} |
                                    <span title="{{ $user->user_agent }}">
                                        {{ $user->device_info }}
                                    </span>
                                </small>
                            </td>                            
                            <td>{{ ucfirst($roleLabels[$user->role_id] ?? $user->role_id) }}</td>
                            <td>
                                @can('user.update')
                                    <a href="{{ route('admin.user.edit', $user->id_users) }}" class="btn btn-warning btn-circle btn-sm">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                @endcan
                                @can('user.delete')
                                    <button class="btn btn-danger btn-circle btn-sm deleteUserBtn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteUserModal"
                                        data-url="{{ route('admin.user.destroy', $user->id_users) }}"
                                        data-name="{{ $user->name }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

@can('user.create')
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.user.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createUserModalLabel">Tambah User</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="create_name">Nama</label>
                        <input type="text" name="name" id="create_name" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="create_username">Username</label>
                        <input type="text" name="username" id="create_username" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="create_password">Password</label>
                        <input type="text" name="password" id="create_password" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="create_role_id">Role</label>
                        <select name="role_id" id="create_role_id" class="form-control" required>
                            <option value="1">Admin</option>
                            <option value="2">Kasir</option>
                            <option value="3">Owner</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan

@can('user.delete')
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="deleteUserForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteUserModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <p>Yakin ingin menghapus user <strong id="deleteUserName"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan

@can('user.restore')
<div class="modal fade" id="trashUserModal" tabindex="-1" aria-labelledby="trashUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="trashUserModalLabel">User Terhapus</h5>
                <button type="button" class="close" data-bs-dismiss="modal"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                @if ($trashed->isEmpty())
                    <p class="text-center text-muted mb-0">Tidak ada data terhapus.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered text-center" width="100%">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Dihapus</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trashed as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->username }}</td>
                                    <td>{{ ucfirst($roleLabels[$item->role_id] ?? $item->role_id) }}</td>
                                    <td>{{ $item->deleted_at->translatedFormat('d F Y, H:i') }}</td>
                                    <td>
                                        <form action="{{ route('admin.user.restore', $item->id_users) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-trash-restore"></i> Pulihkan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endcan

@can('user.export')
    @include('components.no-selection-modal')
@endcan
@endsection

@section('scripts')
<script>
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.deleteUserBtn');
    if (!btn) return;

    document.getElementById('deleteUserForm').action = btn.dataset.url;
    document.getElementById('deleteUserName').textContent = btn.dataset.name;
});

// Checklist semua (hanya ada kalau user punya izin export)
const checkAllEl = document.getElementById('checkAll');
if (checkAllEl) {
    checkAllEl.addEventListener('change', function () {
        document.querySelectorAll('.row-check').forEach(cb => {
            cb.checked = this.checked;
        });
    });
}

// Cegah export kalau tidak ada yang dipilih
const printFormEl = document.getElementById('printForm');
if (printFormEl) {
    printFormEl.addEventListener('submit', function (e) {
        const checked = document.querySelectorAll('.row-check:checked');
        if (checked.length === 0) {
            e.preventDefault();
            const modalEl = document.getElementById('noSelectionModal');
            if (modalEl) new bootstrap.Modal(modalEl).show();
        }
    });
}
</script>
@endsection
