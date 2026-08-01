<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Inventaris Dewi Catering')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.highcharts.com/highcharts.js"></script>

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
        }
        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }
        #sidebar {
            min-width: 250px;
            max-width: 250px;
            background: #2c3e50;
            color: #fff;
            transition: all 0.3s;
            min-height: 100vh;
        }
        #sidebar .sidebar-header {
            padding: 20px;
            background: #1a252f;
        }
        #sidebar ul.components {
            padding: 20px 0;
        }
        #sidebar ul li a {
            padding: 12px 20px;
            font-size: 0.95em;
            display: block;
            color: #b8c7ce;
            text-decoration: none;
        }
        #sidebar ul li a:hover, #sidebar ul li.active > a {
            color: #fff;
            background: #34495e;
        }
        #sidebar ul ul a {
            font-size: 0.85em !important;
            padding-left: 40px !important;
            background: #22313f;
        }
        #content {
            width: 100%;
            padding: 20px;
            min-height: 100vh;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <!-- Sidebar Navigasi -->
    <nav id="sidebar" class="no-print">
        <div class="sidebar-header text-center">
            <h4 class="fw-bold mb-0 text-white"><i class="fa-solid fa-utensils me-2"></i>Dewi Catering</h4>
            <small class="text-muted">Inventaris System</small>
        </div>

        <ul class="list-unstyled components">
            <li class="{{ request()->is('/') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}"><i class="fa-solid fa-gauge me-2"></i> Dashboard</a>
            </li>

            <!-- Menu Produk -->
            <li>
                <a href="#menuProduk" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle d-flex justify-content-between align-items-center">
                    <span><i class="fa-solid fa-tag me-2"></i> Produk</span>
                </a>
                <ul class="collapse list-unstyled {{ request()->is('products*') || request()->is('produk*') ? 'show' : '' }}" id="menuProduk">
                    <li class="{{ request()->is('products/create') || request()->is('produk/create') ? 'active' : '' }}">
                        <a href="{{ route('products.create') }}">Tambah Produk</a>
                    </li>
                    <li class="{{ request()->is('products') || request()->is('produk') ? 'active' : '' }}">
                        <a href="{{ route('products.index') }}">Lihat Produk</a>
                    </li>
                </ul>
            </li>

            <!-- Menu Supplier -->
            <li class="{{ request()->is('suppliers*') || request()->is('supplier*') ? 'active' : '' }}">
                <a href="{{ route('suppliers.index') }}"><i class="fa-solid fa-truck me-2"></i> Supplier</a>
            </li>

            <!-- Menu Purchase Order -->
            <li class="{{ request()->is('purchase-orders*') || request()->is('po*') || request()->is('pesanan*') ? 'active' : '' }}">
                <a href="{{ route('purchase-orders.index') }}"><i class="fa-solid fa-cart-shopping me-2"></i> Purchase Order (PO)</a>
            </li>

            <!-- Menu Laporan -->
            <li class="{{ request()->is('laporan*') ? 'active' : '' }}">
                <a href="{{ route('laporan.index') }}"><i class="fa-solid fa-file-invoice-dollar me-2"></i> Laporan Pembelian</a>
            </li>

            <!-- Menu Pengguna -->
            <li class="{{ request()->is('users*') ? 'active' : '' }}">
                <a href="{{ route('users.index') }}"><i class="fa-solid fa-users me-2"></i> Manajemen Pengguna</a>
            </li>
        </ul>
    </nav>

    <!-- Page Content -->
    <div id="content">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4 rounded no-print">
            <div class="container-fluid">
                <span class="navbar-text fw-semibold text-secondary">
                    Halo, {{ Auth::user()->name ?? 'User' }} ({{ strtoupper(Auth::user()->role ?? 'staf') }})
                </span>
                <div class="ms-auto">
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Flash Message Notification -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show no-print" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show no-print" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>