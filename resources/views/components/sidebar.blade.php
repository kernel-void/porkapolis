<ul class="navbar-nav bg-gradient-{{ $pengaturan->tema }} sidebar sidebar-dark accordion {{ $isMobile ? 'toggled' : '' }}" id="accordionSidebar">
    <a class="sidebar-brand d-flex flex-column align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
        <div class="sidebar-brand-icon">
            <i class="fas {{ $pengaturan->ikon_sidebar }}"></i>
        </div>
        <div class="sidebar-brand-text mx-3">{{ $pengaturan->nama_aplikasi }}</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-chart-line"></i>
            <span>Dashboard</span></a>
    </li>

    <hr class="sidebar-divider">

    @can('menu.view')
        <li class="nav-item {{ request()->routeIs('admin.menu.index', 'admin.menu.create', 'admin.menu.edit') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.menu.index') }}">
                <i class="fas fa-fw fa-hamburger"></i>
                <span>Menu</span></a>
        </li>

        <hr class="sidebar-divider">
    @endcan

    @canany(['pemasukan.view', 'pengeluaran.view'])
        <!-- Heading -->
        <div class="sidebar-heading">
            Data
        </div>
    @endcanany

    @can('pemasukan.view')
        <li class="nav-item {{ request()->routeIs('admin.pemasukan.index', 'admin.pemasukan.create', 'admin.pemasukan.edit') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.pemasukan.index') }}">
                <i class="fas fa-fw fa-money-bill-alt"></i>
                <span>Data Pemasukan</span></a>
        </li>
    @endcan

    @can('pengeluaran.view')
        <li class="nav-item {{ request()->routeIs('admin.pengeluaran.index','admin.pengeluaran.create', 'admin.pengeluaran.edit', 'admin.pengeluaran.payment') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.pengeluaran.index') }}">
                <i class="fas fa-fw fa-hand-holding-usd"></i>
                <span>Data Pengeluaran</span></a>
        </li>
    @endcan

    @canany(['user.view', 'pengaturan.view'])
        <hr class="sidebar-divider">
    @endcanany

    @can('user.view')
        <li class="nav-item {{ request()->routeIs('admin.user.index', 'admin.user.edit') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.user.index') }}">
                <i class="fas fa-fw fa-users"></i>
                <span>User</span></a>
        </li>
    @endcan

    @can('pengaturan.view')
        <li class="nav-item {{ request()->routeIs('admin.pengaturan') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.pengaturan') }}">
                <i class="fas fa-fw fa-cog"></i>
                <span>Pengaturan</span></a>
        </li>
    @endcan

    @can('role.view')
        <li class="nav-item {{ request()->routeIs('admin.role.index') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.role.index') }}">
                <i class="fas fa-fw fa-user-shield"></i>
                <span>Kelola Permission</span></a>
        </li>
    @endcan

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>