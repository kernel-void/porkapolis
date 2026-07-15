@php
    $role_id = Auth::user()->role_id ?? null;
@endphp

@if($role_id == 1)
    @include('components.sidebar-admin')
@elseif($role_id == 2)
    @include('components.sidebar-kasir')
@elseif($role_id == 3)
    @include('components.sidebar-owner')
@endif