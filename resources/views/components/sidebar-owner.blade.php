@php
    use App\Models\Setting;
    $pengaturan = Setting::first();
@endphp

<ul class="navbar-nav bg-gradient-{{ $pengaturan->tema }} sidebar sidebar-dark accordion {{ $isMobile ? 'toggled' : '' }}" id="accordionSidebar">
    <a class="sidebar-brand d-flex flex-column align-items-center justify-content-center" href="{{ route('owner.dashboard') }}">
        <div class="sidebar-brand-icon">
            <i class="fas {{ $pengaturan->ikon_sidebar }}"></i>
        </div>
        <div class="sidebar-brand-text mx-3">{{ $pengaturan->nama_aplikasi }}</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('owner.dashboard') }}">
            <i class="fas fa-fw fa-chart-line"></i>
            <span>Dashboard</span></a>
    </li>

    <hr class="sidebar-divider">

    {{-- Tampilan User --}}
    <li class="nav-item {{ request()->routeIs('owner.menu.index', 'owner.menu.create', 'owner.menu.edit') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('owner.menu.index') }}">
            <i class="fas fa-fw fa-hamburger"></i>
            <span>Menu</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Data
    </div>

    {{-- Tampilan User --}}
    <li class="nav-item {{ request()->routeIs('owner.pemasukan.index', 'owner.pemasukan.create', 'owner.pemasukan.edit') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('owner.pemasukan.index') }}">
            <i class="fas fa-fw fa-money-bill-alt"></i>
            <span>Data Pemasukan</span></a>
    </li>
    
    
    <li class="nav-item {{ request()->routeIs('owner.pengeluaran.index','owner.pengeluaran.create', 'owner.pengeluaran.edit', 'owner.pengeluaran.payment') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('owner.pengeluaran.index') }}">
            <i class="fas fa-fw fa-hand-holding-usd"></i>
            <span>Data Pengeluaran</span></a>
    </li>

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>