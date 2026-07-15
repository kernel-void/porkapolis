@php
    $role_id = Auth::user()->role_id ?? null;
@endphp

@if($role_id == 1)
    @include('components.topbar-admin')
@elseif($role_id == 2)
    @include('components.topbar-kasir')
@elseif($role_id == 3)
    @include('components.topbar-owner')
@endif