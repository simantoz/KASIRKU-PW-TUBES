<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - KasirKu POS</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --accent-emerald: #059669;
            --sidebar-bg: #0f172a;
            --sidebar-color: #94a3b8;
            --sidebar-active-bg: #1e293b;
            --bg-light: #f8fafc;
            --card-border: #e2e8f0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light);
            color: #334155;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background-color: var(--sidebar-bg);
            color: #e2e8f0;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #fff;
            text-decoration: none;
        }

        .sidebar-brand .brand-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.2rem;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .sidebar-menu {
            list-style: none;
            padding: 1rem 0.75rem;
            margin: 0;
            flex-grow: 1;
        }

        .sidebar-heading {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
            padding: 0.75rem 0.75rem 0.25rem;
            margin-top: 0.5rem;
        }

        .nav-item {
            margin-bottom: 0.25rem;
        }

        .nav-link-custom {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.7rem 0.85rem;
            color: var(--sidebar-color);
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .nav-link-custom i {
            font-size: 1.15rem;
        }

        .nav-link-custom:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.06);
        }

        .nav-link-custom.active {
            color: #fff;
            background-color: var(--primary-color);
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
        }

        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(0, 0, 0, 0.2);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #10b981, #059669);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
        }

        /* Main Content Layout */
        .main-wrapper {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        /* Top Navbar */
        .top-navbar {
            background-color: #fff;
            border-bottom: 1px solid var(--card-border);
            padding: 0.85rem 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .page-content {
            padding: 1.75rem;
            flex-grow: 1;
        }

        /* Card Custom Styling */
        .card-custom {
            background: #fff;
            border: 1px solid var(--card-border);
            border-radius: 12px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }

        .card-custom:hover {
            box-shadow: 0 4px 12px -2px rgba(0, 0, 0, 0.08);
        }

        .stat-card {
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .stat-card .stat-icon {
            position: absolute;
            right: 1.25rem;
            top: 1.25rem;
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .btn-primary-custom {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: #fff;
            font-weight: 600;
            padding: 0.55rem 1.15rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .btn-primary-custom:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            color: #fff;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .badge-role {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            padding: 0.25rem 0.6rem;
            border-radius: 6px;
        }

        .badge-role-owner {
            background-color: #fef3c7;
            color: #b45309;
        }

        .badge-role-gudang {
            background-color: #e0e7ff;
            color: #4338ca;
        }

        .badge-role-kasir {
            background-color: #d1fae5;
            color: #047857;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                margin-left: -260px;
            }
            .sidebar.show {
                margin-left: 0;
            }
            .main-wrapper {
                margin-left: 0;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <a href="{{ url('/') }}" class="sidebar-brand">
            <div class="brand-icon">
                <i class="bi bi-shop"></i>
            </div>
            <span>KasirKu POS</span>
        </a>

        <ul class="sidebar-menu">
            @php
                $user = Auth::user();
                $role = $user->role ?? '';
            @endphp

            @if($role === 'owner')
                <div class="sidebar-heading">Menu Owner / Manajer</div>
                <li class="nav-item">
                    <a href="{{ route('owner.dashboard') }}" class="nav-link-custom {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard Utama</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('owner.transactions') }}" class="nav-link-custom {{ request()->routeIs('owner.transactions') ? 'active' : '' }}">
                        <i class="bi bi-receipt-cutoff"></i>
                        <span>Riwayat Penjualan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('owner.print_report') }}" class="nav-link-custom {{ request()->routeIs('owner.print_report') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-pdf"></i>
                        <span>Laporan Keuangan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('owner.users.index') }}" class="nav-link-custom {{ request()->routeIs('owner.users.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span>Manajemen Pegawai</span>
                    </a>
                </li>
            @elseif($role === 'gudang')
                <div class="sidebar-heading">Menu Admin Gudang</div>
                <li class="nav-item">
                    <a href="{{ route('gudang.dashboard') }}" class="nav-link-custom {{ request()->routeIs('gudang.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2"></i>
                        <span>Dashboard Gudang</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('gudang.products.index') }}" class="nav-link-custom {{ request()->routeIs('gudang.products.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam"></i>
                        <span>Master Data Barang</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('gudang.categories.index') }}" class="nav-link-custom {{ request()->routeIs('gudang.categories.*') ? 'active' : '' }}">
                        <i class="bi bi-tags"></i>
                        <span>Kategori Barang</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('gudang.stock.index') }}" class="nav-link-custom {{ request()->routeIs('gudang.stock.*') ? 'active' : '' }}">
                        <i class="bi bi-truck"></i>
                        <span>Stok Masuk (Supplier)</span>
                    </a>
                </li>
            @elseif($role === 'kasir')
                <div class="sidebar-heading">Menu Kasir (Front-Office)</div>
                <li class="nav-item">
                    <a href="{{ route('kasir.pos') }}" class="nav-link-custom {{ request()->routeIs('kasir.pos') ? 'active' : '' }}">
                        <i class="bi bi-calculator"></i>
                        <span>Kasir POS (Transaksi)</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('kasir.transactions') }}" class="nav-link-custom {{ request()->routeIs('kasir.transactions') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i>
                        <span>Riwayat Shift Saya</span>
                    </a>
                </li>
            @endif
        </ul>

        <div class="sidebar-footer">
            <div class="user-profile mb-2">
                <div class="user-avatar">
                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <div class="text-white fw-bold text-truncate" style="font-size: 0.85rem;">{{ $user->name ?? 'User' }}</div>
                    <span class="badge badge-role badge-role-{{ $role }}">{{ ucfirst($role) }}</span>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="mt-2">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm w-100 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Keluar (Logout)</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="main-wrapper">
        <!-- Top Navbar -->
        <header class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light d-lg-none" id="sidebarToggle" type="button">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <h5 class="mb-0 fw-bold text-dark">@yield('page_title', 'Dashboard')</h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="d-none d-md-flex align-items-center gap-2 text-muted" style="font-size: 0.85rem;">
                    <i class="bi bi-calendar3"></i>
                    <span>{{ Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
                    <span class="mx-1">•</span>
                    <i class="bi bi-clock"></i>
                    <span id="liveClock">{{ Carbon\Carbon::now()->format('H:i') }} WIB</span>
                </div>
                <span class="badge badge-role badge-role-{{ $role }} d-none d-sm-inline-block">
                    Level: {{ ucfirst($role) }}
                </span>
            </div>
        </header>

        <!-- Main Body Content -->
        <main class="page-content">
            <!-- Flash Notification Alerts -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 shadow-sm border-0" role="alert">
                    <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center gap-2 shadow-sm border-0" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-5 text-warning"></i>
                    <div>{{ session('warning') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 shadow-sm border-0" role="alert">
                    <i class="bi bi-x-circle-fill fs-5 text-danger"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show d-flex align-items-center gap-2 shadow-sm border-0" role="alert">
                    <i class="bi bi-info-circle-fill fs-5 text-info"></i>
                    <div>{{ session('info') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- jQuery 3.7.1 -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap 5.3 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.all.min.js"></script>
    <!-- Chart.js (for Owner & Gudang analytics) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

    <script>
        // Setup CSRF header for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Toggle Sidebar on mobile
        $('#sidebarToggle').on('click', function() {
            $('#sidebar').toggleClass('show');
        });

        // Realtime Clock
        function updateClock() {
            const now = new Date();
            const h = String(now.getHours()).padStart(2, '0');
            const m = String(now.getMinutes()).padStart(2, '0');
            const s = String(now.getSeconds()).padStart(2, '0');
            $('#liveClock').text(`${h}:${m}:${s} WIB`);
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>

    @yield('scripts')
</body>
</html>
