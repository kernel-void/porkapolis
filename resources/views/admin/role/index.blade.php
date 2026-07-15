@extends('layouts.master')

@section('title', $pengaturan->nama_aplikasi . ' | Kelola Permission Role')
@section('content')
<div class="container-fluid">

    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Kelola Permission</li>
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
        <h1 class="h3 mb-0 text-gray-800">Kelola Permission Role</h1>
    </div>

    <ul class="nav nav-tabs mb-3" id="roleTabs" role="tablist">
        @foreach ($roles as $key => $role)
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $key == 0 ? 'active' : '' }}"
                    id="tab-{{ $role->name }}"
                    data-bs-toggle="tab"
                    data-bs-target="#pane-{{ $role->name }}"
                    type="button" role="tab">
                    {{ ucfirst($role->name) }}
                </button>
            </li>
        @endforeach
    </ul>

    <div class="tab-content" id="roleTabsContent">
        @foreach ($roles as $key => $role)
        <div class="tab-pane fade {{ $key == 0 ? 'show active' : '' }}" id="pane-{{ $role->name }}" role="tabpanel">

            <div class="card shadow mb-4 border-bottom-{{ $pengaturan->tema }}">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Permission untuk Role: {{ ucfirst($role->name) }}
                    </h6>
                    <span class="badge bg-secondary">{{ $role->permissions->count() }} izin aktif</span>
                </div>

                <form action="{{ route('admin.role.update', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <div class="row">
                            @foreach ($grouped as $moduleName => $modulePermissions)
                                <div class="col-md-4 mb-4">
                                    <div class="border rounded p-3 h-100">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <strong class="text-uppercase text-muted" style="font-size: 0.8rem;">
                                                {{ $moduleName }}
                                            </strong>
                                            <div>
                                                <a href="#" class="small select-all" data-target="module-{{ $role->name }}-{{ $moduleName }}">Pilih Semua</a>
                                                /
                                                <a href="#" class="small unselect-all" data-target="module-{{ $role->name }}-{{ $moduleName }}">Kosongkan</a>
                                            </div>
                                        </div>

                                        @foreach ($modulePermissions as $permission)
                                            <div class="form-check">
                                                <input class="form-check-input module-{{ $role->name }}-{{ $moduleName }}"
                                                    type="checkbox"
                                                    name="permissions[]"
                                                    value="{{ $permission->name }}"
                                                    id="perm-{{ $role->name }}-{{ $permission->id }}"
                                                    {{ $role->permissions->contains('name', $permission->name) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="perm-{{ $role->name }}-{{ $permission->id }}">
                                                    {{ explode('.', $permission->name)[1] ?? $permission->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan untuk {{ ucfirst($role->name) }}
                        </button>
                    </div>
                </form>
            </div>

        </div>
        @endforeach
    </div>

</div>
@endsection

@section('scripts')
<script>
document.addEventListener('click', function (e) {
    const selectAllBtn = e.target.closest('.select-all');
    const unselectAllBtn = e.target.closest('.unselect-all');

    if (selectAllBtn) {
        e.preventDefault();
        const target = selectAllBtn.dataset.target;
        document.querySelectorAll('.' + CSS.escape(target)).forEach(cb => cb.checked = true);
    }

    if (unselectAllBtn) {
        e.preventDefault();
        const target = unselectAllBtn.dataset.target;
        document.querySelectorAll('.' + CSS.escape(target)).forEach(cb => cb.checked = false);
    }
});
</script>
@endsection