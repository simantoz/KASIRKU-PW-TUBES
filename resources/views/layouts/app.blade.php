<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - KasirKu</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        :root {
            --bg-main: #1f1d2b;
            --bg-sidebar: #1f1d2b;
            --bg-card: #252836;
            --bg-card-subtle: #2d303e;
            --bg-input: #2d303e;
            --primary: #ea7c69;
            --primary-hover: #e06550;
            --text-main: #ffffff;
            --text-muted: #8892b0;
            --text-light: #abb2bf;
            --border-color: rgba(255, 255, 255, 0.08);
            --sidebar-w: 96px;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-main);
            color: var(--text-main);
            margin: 0;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Slim Vertical Sidebar like reference */
        .sidebar {
            width: var(--sidebar-w);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--bg-sidebar);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1.5rem 0;
            z-index: 1000;
            border-right: 1px solid var(--border-color);
        }

        .sidebar-brand {
            width: 56px;
            height: 56px;
            background: rgba(234, 124, 105, 0.2);
            border-radius: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            text-decoration: none;
            margin-bottom: 2rem;
            transition: all 0.2s ease;
        }

        .sidebar-brand i {
            font-size: 1.5rem;
        }

        .sidebar-brand-text {
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: -0.2px;
            margin-top: 2px;
            color: #fff;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            align-items: center;
            width: 100%;
            flex: 1;
        }

        .nav-item {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .nav-link-custom {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            text-decoration: none;
            font-size: 0.68rem;
            font-weight: 600;
            transition: all 0.2s ease;
            position: relative;
        }

        .nav-link-custom i {
            font-size: 1.35rem;
            margin-bottom: 2px;
        }

        .nav-link-custom:hover {
            color: #fff;
            background: rgba(234, 124, 105, 0.15);
        }

        .nav-link-custom.active {
            background: var(--primary);
            color: #ffffff;
            box-shadow: 0 8px 24px rgba(234, 124, 105, 0.4);
        }

        .sidebar-footer {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            width: 100%;
            padding: 0 1rem;
        }

        .user-avatar-btn {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            background: #2d303e;
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 0.95rem;
        }

        .logout-btn {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            background: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: all 0.2s ease;
        }

        .logout-btn:hover {
            color: #ff4949;
            border-color: rgba(255, 73, 73, 0.4);
            background: rgba(255, 73, 73, 0.1);
        }

        /* Hamburger: hanya tampil di layar kecil */
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 0.75rem;
            left: 0.75rem;
            z-index: 1100;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            color: var(--text-main);
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.35);
        }

        /* Main Wrapper */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .page-content {
            padding: 1.75rem 2rem;
            flex: 1;
        }

        /* Dark Card Custom */
        .card-custom {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            color: var(--text-main);
        }

        .stat-card {
            border-radius: 16px;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
        }

        .stat-card .stat-icon {
            position: absolute;
            right: 1.25rem;
            top: 1.25rem;
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        /* Primary Button */
        .btn-primary-custom {
            background-color: var(--primary);
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 0.65rem 1.4rem;
            border-radius: 12px;
            transition: all 0.2s ease;
            box-shadow: 0 6px 18px rgba(234, 124, 105, 0.3);
        }

        .btn-primary-custom:hover {
            background-color: var(--primary-hover);
            color: #fff;
            box-shadow: 0 8px 24px rgba(234, 124, 105, 0.45);
        }

        .badge-role {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            padding: 0.25rem 0.65rem;
            border-radius: 8px;
        }
        .badge-role-owner { background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); }
        .badge-role-gudang { background: rgba(59, 130, 246, 0.2); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); }
        .badge-role-kasir { background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }

        /* Tables in Dark Theme */
        .table {
            color: var(--text-main);
            border-color: var(--border-color);
        }
        .table-dark-custom {
            background: transparent;
            color: var(--text-main);
        }
        .table-dark-custom thead th {
            background-color: rgba(255, 255, 255, 0.03);
            color: var(--text-muted);
            border-bottom: 1px solid var(--border-color);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 1rem 0.75rem;
        }
        .table-dark-custom tbody td {
            background-color: transparent;
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 0.75rem;
            vertical-align: middle;
            color: var(--text-light);
        }
        .table-dark-custom tbody tr:hover td {
            background-color: rgba(255, 255, 255, 0.02);
            color: #fff;
        }

        /* Form Controls */
        .form-control, .form-select {
            background-color: var(--bg-input);
            border: 1px solid var(--border-color);
            color: #fff;
            border-radius: 12px;
            padding: 0.65rem 1rem;
        }
        .form-control:focus, .form-select:focus {
            background-color: var(--bg-input);
            border-color: var(--primary);
            color: #fff;
            box-shadow: 0 0 0 3px rgba(234, 124, 105, 0.25);
        }
        .form-control::placeholder {
            color: #64748b;
        }

        /* Modal Dark */
        .modal-content {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            color: var(--text-main);
        }
        .modal-header {
            border-bottom: 1px solid var(--border-color);
        }
        .modal-footer {
            border-top: 1px solid var(--border-color);
        }
        .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #393c49;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary);
        }

        @media (max-width: 991.98px) {
            .sidebar {
                margin-left: calc(-1 * var(--sidebar-w));
            }
            .sidebar.show {
                margin-left: 0;
            }
            .sidebar-toggle {
                display: flex;
            }
            .main-wrapper {
                margin-left: 0;
            }
            .page-content {
                padding: 4.5rem 1rem 1rem;
            }
        }

        @media (max-width: 575.98px) {
            .page-content {
                padding: 4.25rem 0.75rem 0.75rem;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Hamburger untuk sidebar di layar kecil -->
    <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Buka menu">
        <i class="bi bi-list"></i>
    </button>

    @php
        $user = Auth::user();
        $role = $user->role ?? '';
    @endphp

    <!-- Slim Sidebar -->
    <aside class="sidebar" id="sidebar">
        <a href="{{ url('/') }}" class="sidebar-brand" title="KasirKu">
            <i class="bi bi-shop"></i>
            <span class="sidebar-brand-text">KasirKu</span>
        </a>

        <ul class="sidebar-menu">
            @if($role === 'owner')
                <li class="nav-item">
                    <a href="{{ route('owner.dashboard') }}" class="nav-link-custom {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}" title="Dashboard">
                        <i class="bi bi-house-door-fill"></i>
                        <span>Home</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('owner.transactions') }}" class="nav-link-custom {{ request()->routeIs('owner.transactions*') ? 'active' : '' }}" title="Riwayat Penjualan">
                        <i class="bi bi-clock-history"></i>
                        <span>History</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('owner.purchases.index') }}" class="nav-link-custom {{ request()->routeIs('owner.purchases*') ? 'active' : '' }}" title="Barang Masuk">
                        <i class="bi bi-truck"></i>
                        <span>Restock</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('owner.print_report') }}" class="nav-link-custom {{ request()->routeIs('owner.print_report') ? 'active' : '' }}" title="Laporan">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                        <span>Report</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('owner.users.index') }}" class="nav-link-custom {{ request()->routeIs('owner.users.*') ? 'active' : '' }}" title="Pegawai">
                        <i class="bi bi-people"></i>
                        <span>Staff</span>
                    </a>
                </li>
            @elseif($role === 'gudang')
                <li class="nav-item">
                    <a href="{{ route('gudang.dashboard') }}" class="nav-link-custom {{ request()->routeIs('gudang.dashboard') ? 'active' : '' }}" title="Dashboard">
                        <i class="bi bi-house-door-fill"></i>
                        <span>Home</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('gudang.products.index') }}" class="nav-link-custom {{ request()->routeIs('gudang.products.*') ? 'active' : '' }}" title="Katalog Menu">
                        <i class="bi bi-journal-text"></i>
                        <span>Menu</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('gudang.categories.index') }}" class="nav-link-custom {{ request()->routeIs('gudang.categories.*') ? 'active' : '' }}" title="Kategori">
                        <i class="bi bi-tags"></i>
                        <span>Category</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('gudang.purchases.create') }}" class="nav-link-custom {{ request()->routeIs('gudang.purchases.create') ? 'active' : '' }}" title="Input Barang Masuk">
                        <i class="bi bi-plus-square-fill"></i>
                        <span>PO In</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('gudang.purchases.index') }}" class="nav-link-custom {{ request()->routeIs('gudang.purchases.index') || request()->routeIs('gudang.stock.*') ? 'active' : '' }}" title="Riwayat Barang Masuk">
                        <i class="bi bi-truck"></i>
                        <span>Restock</span>
                    </a>
                </li>
            @elseif($role === 'kasir')
                <li class="nav-item">
                    <a href="{{ route('kasir.pos') }}" class="nav-link-custom {{ request()->routeIs('kasir.pos') ? 'active' : '' }}" title="Kasir Order">
                        <i class="bi bi-house-door-fill"></i>
                        <span>Home</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('kasir.transactions') }}" class="nav-link-custom {{ request()->routeIs('kasir.transactions') ? 'active' : '' }}" title="Riwayat Transaksi">
                        <i class="bi bi-clock-history"></i>
                        <span>History</span>
                    </a>
                </li>
            @endif
        </ul>

        <div class="sidebar-footer">
            <div class="user-avatar-btn" title="{{ $user->name ?? 'User' }} ({{ ucfirst($role) }})">
                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn" title="Keluar">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="main-wrapper">
        <main class="page-content">
            @if(session('success'))
                <div class="alert alert-dismissible fade show d-flex align-items-center gap-2 border-0 mb-3" style="background: rgba(16, 185, 129, 0.15); color: #34d399; border-radius: 14px;" role="alert">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-dismissible fade show d-flex align-items-center gap-2 border-0 mb-3" style="background: rgba(245, 158, 11, 0.15); color: #fbbf24; border-radius: 14px;" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                    <div>{{ session('warning') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-dismissible fade show d-flex align-items-center gap-2 border-0 mb-3" style="background: rgba(239, 68, 68, 0.15); color: #f87171; border-radius: 14px;" role="alert">
                    <i class="bi bi-x-circle-fill fs-5"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Sidebar toggle untuk layar kecil
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        if (sidebar && sidebarToggle) {
            sidebarToggle.addEventListener('click', () => sidebar.classList.toggle('show'));
            // Tutup sidebar saat klik konten
            document.querySelector('.page-content').addEventListener('click', () => sidebar.classList.remove('show'));
        }
    </script>
    @yield('scripts')
</body>
</html>
