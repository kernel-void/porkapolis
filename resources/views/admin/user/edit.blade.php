@extends('layouts.master')

@section('title', $pengaturan->nama_aplikasi . ' | Ubah User')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">Pengguna</a></li>
        <li class="breadcrumb-item active" aria-current="page">Ubah Pengguna</li>
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
        <h1 class="h3 mb-0 text-gray-800">Ubah Data</h1>
    </div>

    <div class="card shadow mb-4 border-bottom-{{ $pengaturan->tema }}">
        <div class="card-header py-3 d-flex justify-content-center">
            <h6 class="m-0 font-weight-bold text-primary">Data User</h6>
        </div>
    
        <form action="{{ route('admin.user.update', $user->id_users) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="container d-flex flex-column col-12 col-md-5 justify-content-center">
                <div class="mt-2">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $user->name) }}">
                </div>

                <div class="mt-2">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" id="username" value="{{ old('username', $user->username) }}">
                </div>

                <div class="mt-2">
                    <label for="password" class="form-label">Password</label>
                    <input type="text" name="password" class="form-control" id="password" placeholder="Kosongkan jika tidak ingin mengganti" autocomplete="new-password">
                </div>

                <div class="mt-2 pb-4">
                    <label for="role_id" class="form-label">Role</label>
                    <select name="role_id" id="role_id" class="form-control">
                        <option value="1" {{ $user->role_id == 1 ? 'selected' : '' }}>Admin</option>
                        <option value="2" {{ $user->role_id == 2 ? 'selected' : '' }}>Kasir</option>
                        <option value="3" {{ $user->role_id == 3 ? 'selected' : '' }}>Owner</option>
                    </select>
                </div>
            </div>

            <div class="container d-flex flex-column col-12 justify-content-start">
                <div class="d-flex mb-5 bg-gray-200">
                    <div class="mb-4 mt-4 text-center w-75">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('admin.user.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection
