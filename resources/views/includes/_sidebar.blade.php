<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/dashboard" class="brand-link">
        <span class="brand-text font-weight-light">SUPER NOTARIS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">


        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                {{-- Master data --}}
                <li class="nav-item {{ request()->is('master-data*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('master-data*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-database"></i>
                        <p>
                            Master Data
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="display: {{ request()->is('master-data*') ? '' : 'none' }};">
                        <li class="nav-item">
                            <a href="{{ route('master-data.brand.index') }}"
                                class="nav-link {{ request()->is('master-data/brand*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Brand</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('master-data.cabang.index') }}"
                                class="nav-link {{ request()->is('master-data/cabang*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Cabang</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('master-data.gudang.index') }}"
                                class="nav-link {{ request()->is('master-data/gudang*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Gudang</p>
                            </a>
                        </li>

                        <li class="nav-item {{ request()->is('master-data/produk*') ? 'menu-open' : '' }}">
                            <a href="#"
                                class="nav-link {{ request()->is('master-data/produk*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Produk
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview"
                                style="display: {{ request()->is('master-data/produk*') ? '' : 'none' }};">
                                <li class="nav-item">
                                    <a href="{{ route('master-data.kategori.index') }}"
                                        class="nav-link {{ request()->is('master-data/produk/kategori*') ? 'active' : '' }}">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Kategori</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('master-data.satuan.index') }}"
                                        class="nav-link {{ request()->is('master-data/produk/satuan*') ? 'active' : '' }}">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Satuan</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('master-data.item.index') }}"
                                        class="nav-link {{ request()->is('master-data/produk/item*') ? 'active' : '' }}">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Item</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('master-data.item.index') }}"
                                class="nav-link {{ request()->is('master-data/treatment*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Treatment</p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- Master data end --}}

                <li class="nav-item">
                    <a href="../calendar.html" class="nav-link">
                        <i class="fas fa-cubes nav-icon"></i>
                        <p>
                            Treatment
                            <span class="badge badge-info right">2</span>
                        </p>
                    </a>
                </li>

                @can('user-list')
                    <li class="nav-item">
                        <a href="{{ route('user.index') }}" class="nav-link {{ request()->is('user*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                User
                            </p>
                        </a>
                    </li>
                @endcan

                @can('role-permission-list')
                    <li class="nav-item">
                        <a href="{{ route('role-permission.index') }}"
                            class="nav-link {{ request()->is('role-permission*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>
                                Role & Permission
                            </p>
                        </a>
                    </li>
                @endcan



                {{-- Laporan --}}
                <li class="nav-item {{ request()->is('laporan*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('laporan*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-database"></i>
                        <p>
                            Laporan
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="display: {{ request()->is('laporan*') ? '' : 'none' }};">
                        <li class="nav-item">
                            <a href=""
                                class="nav-link {{ request()->is('laporan/stok-produk*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Stok Produk</p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- Master data end --}}
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
