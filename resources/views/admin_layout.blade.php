<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('admin_title', 'Admin Dashboard')</title>
    <link href="{{ asset('frontend/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/css/font-awesome.css') }}" rel="stylesheet">
    <style>
        :root {
            --admin-sidebar-width: 280px;
        }

        body {
            background: #f6f8fb;
            overflow-x: hidden;
        }

        .admin-shell {
            min-height: 100vh;
        }

        .admin-sidebar {
            width: var(--admin-sidebar-width);
            max-width: calc(100vw - 1.5rem);
        }

        .admin-sidebar .nav-link {
            color: #475467;
            border-radius: .75rem;
            font-weight: 500;
        }

        .admin-sidebar .nav-link:hover,
        .admin-sidebar .nav-link:focus,
        .admin-sidebar .nav-link.active {
            color: #0f5132;
            background: #e8f5ee;
        }

        .admin-sidebar .nav-link i {
            width: 1.25rem;
            text-align: center;
        }

        .admin-content {
            min-width: 0;
        }

        @media (min-width: 992px) {
            .admin-sidebar {
                position: fixed;
                inset: 0 auto 0 0;
                z-index: 1040;
                height: 100vh;
                transform: none !important;
                visibility: visible !important;
            }

            .admin-sidebar .offcanvas-body {
                height: calc(100vh - 64px);
                overflow-y: auto;
            }

            .admin-content {
                margin-left: var(--admin-sidebar-width);
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }

            .admin-content main {
                flex: 1 0 auto;
            }
        }

        .panel,
        .table-agile-info {
            background: #fff;
            border: 0;
            border-radius: 1rem;
            box-shadow: 0 .5rem 1.5rem rgba(15, 23, 42, .06);
            overflow: hidden;
        }

        .panel-heading {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #eef2f7;
            font-size: 1rem;
            font-weight: 700;
            color: #101828;
            background: #fff;
        }

        .panel-body,
        .w3-res-tb,
        .panel-footer {
            padding: 1.25rem;
        }

        .panel-footer {
            border-top: 1px solid #eef2f7;
            background: #fff;
        }

        .table {
            vertical-align: middle;
        }

        .table th {
            white-space: nowrap;
            color: #667085;
            font-size: .8125rem;
            text-transform: uppercase;
            letter-spacing: .02em;
        }

        .table td,
        .table th,
        .card,
        .panel {
            overflow-wrap: anywhere;
        }

        .admin-product-img {
            width: 72px;
            height: 72px;
            object-fit: cover;
            border-radius: .75rem;
        }

        .styling-edit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 2rem;
            border-radius: .5rem;
            text-decoration: none;
        }

        .btn-default {
            --bs-btn-color: #344054;
            --bs-btn-bg: #fff;
            --bs-btn-border-color: #d0d5dd;
            --bs-btn-hover-bg: #f9fafb;
            --bs-btn-hover-border-color: #98a2b3;
        }

        .label,
        .badge-status {
            display: inline-block;
            padding: .35em .65em;
            border-radius: 999px;
            font-size: .75rem;
            font-weight: 700;
        }

        .label-success { color: #0f5132; background: #d1e7dd; }
        .label-warning { color: #664d03; background: #fff3cd; }
        .label-danger { color: #842029; background: #f8d7da; }
    </style>
</head>
<body>
@php
    $adminName = Session::get('admin_name') ?: 'Admin';
    $currentPath = request()->path();
    $isActive = fn ($paths) => collect((array) $paths)->contains(fn ($path) => request()->is($path));
@endphp

<div class="admin-shell">
    <aside class="admin-sidebar offcanvas-lg offcanvas-start bg-white border-end" tabindex="-1" id="adminSidebar" aria-labelledby="adminSidebarLabel">
        <div class="offcanvas-header border-bottom">
            <h1 class="offcanvas-title h5 fw-bold text-success" id="adminSidebarLabel">Fresh Admin</h1>
            <button type="button" class="btn-close d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#adminSidebar" aria-label="Đóng menu"></button>
        </div>

        <div class="offcanvas-body d-flex flex-column p-3">
            <nav class="nav nav-pills flex-column gap-1" aria-label="Điều hướng quản trị">
                <a class="nav-link {{ $isActive(['dashboard']) ? 'active' : '' }}" href="{{ URL::to('/dashboard') }}">
                    <i class="fa fa-dashboard me-2"></i>Tổng quan
                </a>

                <div class="mt-3 mb-1 px-3 small text-uppercase text-secondary fw-semibold">Bán hàng</div>
                <a class="nav-link {{ $isActive(['manage-order', 'view-order/*']) ? 'active' : '' }}" href="{{ URL::to('/manage-order') }}">
                    <i class="fa fa-shopping-bag me-2"></i>Đơn hàng
                </a>
                <a class="nav-link {{ $isActive(['manage-payment', 'view-payment/*']) ? 'active' : '' }}" href="{{ URL::to('/manage-payment') }}">
                    <i class="fa fa-credit-card me-2"></i>Thanh toán
                </a>

                <div class="mt-3 mb-1 px-3 small text-uppercase text-secondary fw-semibold">Danh mục</div>
                <a class="nav-link {{ $isActive(['add-category-product']) ? 'active' : '' }}" href="{{ URL::to('/add-category-product') }}">
                    <i class="fa fa-plus-circle me-2"></i>Thêm danh mục
                </a>
                <a class="nav-link {{ $isActive(['all-category-product', 'edit-category-product/*']) ? 'active' : '' }}" href="{{ URL::to('/all-category-product') }}">
                    <i class="fa fa-list me-2"></i>Danh sách danh mục
                </a>

                <div class="mt-3 mb-1 px-3 small text-uppercase text-secondary fw-semibold">Sản phẩm</div>
                <a class="nav-link {{ $isActive(['add-product']) ? 'active' : '' }}" href="{{ URL::to('/add-product') }}">
                    <i class="fa fa-plus-circle me-2"></i>Thêm sản phẩm
                </a>
                <a class="nav-link {{ $isActive(['all-product', 'edit-product/*']) ? 'active' : '' }}" href="{{ URL::to('/all-product') }}">
                    <i class="fa fa-cubes me-2"></i>Danh sách sản phẩm
                </a>

                <div class="mt-3 mb-1 px-3 small text-uppercase text-secondary fw-semibold">Phân tích</div>
                <a class="nav-link {{ $isActive(['statistics-orders']) ? 'active' : '' }}" href="{{ URL::to('/statistics-orders') }}">
                    <i class="fa fa-bar-chart me-2"></i>Đơn hàng
                </a>
                <a class="nav-link {{ $isActive(['statistics-revenue']) ? 'active' : '' }}" href="{{ URL::to('/statistics-revenue') }}">
                    <i class="fa fa-line-chart me-2"></i>Doanh thu
                </a>
                <a class="nav-link {{ $isActive(['statistics-products']) ? 'active' : '' }}" href="{{ URL::to('/statistics-products') }}">
                    <i class="fa fa-pie-chart me-2"></i>Sản phẩm
                </a>
            </nav>

            <div class="mt-auto pt-3 border-top">
                <a class="btn btn-outline-danger w-100" href="{{ URL::to('/logout') }}">
                    <i class="fa fa-sign-out me-2"></i>Đăng xuất
                </a>
            </div>
        </div>
    </aside>

    <div class="admin-content">
        <header class="sticky-top bg-white border-bottom">
            <div class="container-fluid px-3 px-lg-4">
                <div class="d-flex align-items-center justify-content-between gap-3 py-3">
                    <div class="d-flex align-items-center gap-3">
                        <button class="btn btn-outline-secondary d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar" aria-controls="adminSidebar">
                            <i class="fa fa-bars"></i>
                            <span class="visually-hidden">Mở menu</span>
                        </button>
                        <div>
                            <p class="small text-secondary mb-0">Bảng điều khiển</p>
                            <h2 class="h5 mb-0">@yield('page_heading', 'Quản trị')</h2>
                        </div>
                    </div>

                    <div class="dropdown">
                        <button class="btn btn-light border d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ asset('backend/images/2.png') }}" class="rounded-circle" width="32" height="32" alt="{{ $adminName }}">
                            <span class="d-none d-sm-inline">{{ $adminName }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li><a class="dropdown-item" href="{{ URL::to('/dashboard') }}">Tổng quan</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="{{ URL::to('/logout') }}">Đăng xuất</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>

        <main class="container-fluid px-3 px-lg-4 py-4">
            @yield('admin_content')
        </main>

        <footer class="border-top bg-white py-3">
            <div class="container-fluid px-3 px-lg-4 small text-secondary">
                Designed by Trọng Phú, Hoàng Anh
            </div>
        </footer>
    </div>
</div>

<script src="{{ asset('frontend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
@stack('admin_scripts')
</body>
</html>
