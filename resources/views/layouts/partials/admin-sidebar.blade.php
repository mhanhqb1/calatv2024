<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <img src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/img/AdminLTELogo.png"
             alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Admin Panel</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="https://via.placeholder.com/160" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.places.index') }}" class="nav-link {{ Request::is('admin/places/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Places</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.place_cates.index') }}" class="nav-link {{ Request::is('admin/place-cates/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Categories</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
