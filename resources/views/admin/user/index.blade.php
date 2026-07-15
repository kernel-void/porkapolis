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
        <div class="card-header py-3 d-flex justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Data User</h6>
            <br>
        </div>

        <div class="card-body">
            <div class="table-responsive pt-2">
                <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Status</th>
                            <th>Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $key => $user)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $user->name }} </td>
                            <td class="font-weight-bold">{{ $user->username }}</td>
                            <td>{{ $user->bypass }}</td>
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
                            @php
                                $roles = [
                                    1 => 'Admin',
                                    2 => 'Kasir',
                                    3 => 'Owner'
                                ];
                            @endphp
                            <td>{{ $roles[$user->role_id] }}</td>
                            <td>
                                @can('user.update')
                                    <a href="{{ route('admin.user.edit', $user->id_users) }}" class="btn btn-warning btn-circle btn-sm">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
