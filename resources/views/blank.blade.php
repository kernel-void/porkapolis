@extends('layouts.master')

@section('title', '404 Page Not Found')
@section('content')
<div class="container-fluid">

    <!-- 404 Error Text -->
    <div class="text-center">
        <div class="error mx-auto" data-text="404">404</div>
        <p class="lead text-gray-800 mb-5">Page Not Found</p>
        <p class="text-gray-500 mb-0">Halaman yang Anda cari tidak tersedia. Silakan kembali ke halaman sebelumnya.</p>
        
        @php
            $previousUrl = url()->previous();
            $currentUrl = url()->current();
        @endphp

        <a href="{{ $previousUrl !== $currentUrl ? $previousUrl : getDashboardRoute() }}">&larr; Kembali</a>
    </div>
</div>
@endsection
