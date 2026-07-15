@extends('layouts.master')
@section('title', $pengaturan->nama_aplikasi . ' | Pengaturan')

@section('content')
<div class="container-fluid">

    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pengaturan</li>
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
        <h1 class="h3 mb-0 text-gray-800">Pengaturan</h1>
    </div>

    <form action="{{ route('admin.pengaturan.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <!-- Form Utama -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4 border-bottom-{{ $pengaturan->tema }}">
                    <div class="card-header py-3 d-flex justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Data Pengaturan</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="nama_aplikasi">Nama Aplikasi</label>
                                <input type="text" name="nama_aplikasi" id="nama_aplikasi" class="form-control" value="{{ old('nama_aplikasi', $pengaturan->nama_aplikasi ?? '') }}">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="ikon_sidebar">Ikon Sidebar</label>
                                <input type="text" name="ikon_sidebar" id="ikon_sidebar" class="form-control" value="{{ old('ikon_sidebar', $pengaturan->ikon_sidebar ?? '') }}" oninput="this.value = this.value.toLowerCase()">
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="tema">Warna Sidebar</label>
                                <input type="text" name="tema" id="tema" class="form-control" value="{{ old('tema', $pengaturan->tema ?? '') }}" oninput="this.value = this.value.toLowerCase()">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="footer">Footer</label>
                                <input type="text" name="footer" id="footer" class="form-control" value="{{ old('footer', $pengaturan->footer ?? '') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="text-left ml-4 mb-5">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>

            <!-- Form Logo -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4 border-bottom-{{ $pengaturan->tema }}">
                    <div class="card-header py-3 d-flex justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Logo Aplikasi</h6>
                    </div>
                    <div class="card-body text-center">
                        @php
                            $logoPath = $pengaturan->logo ?? 'assets/img/logo-login/logo.png';
                            $fullPath = public_path($logoPath);
                        @endphp

                        @if(file_exists($fullPath))
                            <img src="{{ asset($logoPath) }}?v={{ time() }}" alt="Logo Aplikasi" class="img-fluid mb-3" width="150">
                        @else
                            <img src="{{ asset('assets/img/logo-login/logo.png') }}" alt="Logo Default" class="img-fluid mb-3" width="150">
                        @endif

                        <div class="form-group text-center">
                            <label for="logo" class="d-block mb-2">Upload Logo</label>
                            <div class="custom-file" style="max-width: 250px; margin: 0 auto;">
                                <input type="file" class="custom-file-input" id="logo" name="logo" aria-describedby="logo">
                                <label class="custom-file-label text-left" for="logo">Pilih file</label>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </form>

</div>
@endsection
