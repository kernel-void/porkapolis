@php
    use App\Models\Setting;
    $pengaturan = Setting::first();
@endphp

<ul class="navbar-nav bg-gradient-{{ $pengaturan->tema }} sidebar sidebar-dark accordion {{ $isMobile ? 'toggled' : '' }}" id="accordionSidebar">
    <a class="sidebar-brand d-flex flex-column align-items-center justify-content-center" href="{{ route('kasir.dashboard') }}">
        <div class="sidebar-brand-icon">
            <i class="fas {{ $pengaturan->ikon_sidebar }}"></i>
        </div>
        <div class="sidebar-brand-text mx-3">{{ $pengaturan->nama_aplikasi }}</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->routeIs('kasir.dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('kasir.dashboard') }}">
            <i class="fas fa-fw fa-chart-line"></i>
            <span>Dashboard</span></a>
    </li>

    <hr class="sidebar-divider">    
    
    <li class="nav-item {{ request()->routeIs('kasir.menu.index','kasir.menu.create', 'kasir.menu.edit', 'kasir.menu.payment') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('kasir.menu.index') }}">
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
    <li class="nav-item {{ request()->routeIs('kasir.pemasukan.index', 'kasir.pemasukan.create', 'kasir.pemasukan.edit') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('kasir.pemasukan.index') }}">
            <i class="fas fa-fw fa-money-bill-alt"></i>
            <span>Data Pemasukan</span></a>
    </li>
    
    
    <li class="nav-item {{ request()->routeIs('kasir.pengeluaran.index','kasir.pengeluaran.create', 'kasir.pengeluaran.edit', 'kasir.pengeluaran.payment') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('kasir.pengeluaran.index') }}">
            <i class="fas fa-fw fa-hand-holding-usd"></i>
            <span>Data Pengeluaran</span></a>
    </li>

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>