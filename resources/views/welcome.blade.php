<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Fresh - cửa hàng thực phẩm sạch">
    <title>@yield('title', 'Fresh')</title>

    <link rel="shortcut icon" href="{{ asset('frontend/images/favicon.ico') }}">
    <link href="{{ asset('frontend/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        body {
            overflow-x: hidden;
        }

        .min-w-0 {
            min-width: 0;
        }

        .table th,
        .table td,
        .card,
        .list-group-item {
            overflow-wrap: anywhere;
        }

        .mobile-bottom-nav {
            display: none;
        }

        @media (max-width: 767.98px) {
            body {
                padding-bottom: 74px;
            }

            .mobile-bottom-nav {
                display: grid;
                grid-template-columns: repeat(4, minmax(0, 1fr));
                position: fixed;
                right: 0;
                bottom: 0;
                left: 0;
                z-index: 1040;
                background: #fff;
                border-top: 1px solid rgba(0, 0, 0, .12);
                box-shadow: 0 -6px 18px rgba(15, 23, 42, .08);
            }

            .mobile-bottom-nav a {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 4px;
                min-width: 0;
                min-height: 64px;
                padding: 8px 4px;
                color: #495057;
                font-size: 12px;
                line-height: 1.15;
                text-align: center;
                text-decoration: none;
                white-space: nowrap;
            }

            .mobile-bottom-nav a:hover,
            .mobile-bottom-nav a:focus {
                color: #198754;
            }

            .mobile-bottom-nav svg {
                width: 22px;
                height: 22px;
                flex: 0 0 auto;
            }
        }
    </style>
</head>
<body class="bg-light">
@php
    $customerId = Session::get('customer_id');
    $shippingId = Session::get('shipping_id');
    $checkoutUrl = $customerId
        ? ($shippingId ? URL::to('/checkout') : URL::to('/payment'))
        : URL::to('/login-checkout');

    $parentCategories = DB::table('tbl_category_product')
        ->where('category_status', '1')
        ->where('parent_id', 0)
        ->orderBy('category_id', 'asc')
        ->get();
@endphp

<header class="bg-white border-bottom sticky-top">
    <div class="border-bottom small text-secondary d-none d-md-block">
        <div class="container py-2">
            <div class="d-flex flex-column flex-md-row justify-content-between gap-2">
                <div class="d-flex flex-wrap gap-3">
                    <span>Hotline: +2 95 01 88 821</span>
                    <span>Email: info@domain.com</span>
                </div>
                <div class="d-flex flex-wrap gap-3">
                    <a class="link-secondary text-decoration-none" href="{{ route('wishlist.index') }}">Yêu thích</a>
                    <a class="link-secondary text-decoration-none" href="{{ URL::to('/show-cart') }}">Giỏ hàng</a>
                    @if($customerId)
                        <a class="link-secondary text-decoration-none" href="{{ URL::to('/lich-su-don-hang') }}">Đơn hàng</a>
                        <a class="link-secondary text-decoration-none" href="{{ URL::to('/logout-checkout') }}">Đăng xuất</a>
                    @else
                        <a class="link-secondary text-decoration-none" href="{{ URL::to('/login-checkout') }}">Đăng nhập</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg bg-white" aria-label="Thanh điều hướng chính">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-success" href="{{ URL::to('/') }}">
                <img src="{{ asset('frontend/images/fresh.png') }}" class="img-fluid" style="max-height:48px" alt="Fresh">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                    aria-controls="mainNavbar" aria-expanded="false" aria-label="Mở menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-3 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ URL::to('/trang-chu') }}">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ URL::to('/khuyen-mai-soc') }}">Khuyến mãi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ $checkoutUrl }}">Thanh toán</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ URL::to('/show-cart') }}">Giỏ hàng</a>
                    </li>
                </ul>

                <form class="d-flex gap-2" action="{{ URL::to('/tim-kiem') }}" method="POST" role="search">
                    @csrf
                    <label class="visually-hidden" for="site-search">Tìm kiếm sản phẩm</label>
                    <input id="site-search" class="form-control" type="search" name="keywords_submit"
                           placeholder="Tìm kiếm sản phẩm" aria-label="Tìm kiếm sản phẩm">
                    <button class="btn btn-success text-nowrap" type="submit">Tìm kiếm</button>
                </form>
            </div>
        </div>
    </nav>
</header>

<nav class="mobile-bottom-nav" aria-label="Thanh điều hướng nhanh trên mobile">
    <a href="{{ route('wishlist.index') }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"/>
        </svg>
        <span>Yêu thích</span>
    </a>

    <a href="{{ URL::to('/show-cart') }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <circle cx="9" cy="21" r="1"/>
            <circle cx="20" cy="21" r="1"/>
            <path d="M1 1h4l2.7 13.4a2 2 0 0 0 2 1.6h8.9a2 2 0 0 0 2-1.6L22 6H6"/>
        </svg>
        <span>Giỏ hàng</span>
    </a>

    @if($customerId)
        <a href="{{ URL::to('/lich-su-don-hang') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M6 2h9l5 5v15H6z"/>
                <path d="M14 2v6h6"/>
                <path d="M9 13h6"/>
                <path d="M9 17h6"/>
            </svg>
            <span>Đơn hàng</span>
        </a>
        <a href="{{ URL::to('/logout-checkout') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                <path d="M16 17l5-5-5-5"/>
                <path d="M21 12H9"/>
            </svg>
            <span>Đăng xuất</span>
        </a>
    @else
        <a href="{{ $checkoutUrl }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <rect x="2" y="5" width="20" height="14" rx="2"/>
                <path d="M2 10h20"/>
            </svg>
            <span>Thanh toán</span>
        </a>
        <a href="{{ URL::to('/login-checkout') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                <path d="M10 17l5-5-5-5"/>
                <path d="M15 12H3"/>
            </svg>
            <span>Đăng nhập</span>
        </a>
    @endif
</nav>

<section class="bg-white border-bottom">
    <div class="container py-4 py-lg-5">
        <div id="homeCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="0" class="active"
                        aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>

            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="row align-items-center g-4">
                        <div class="col-12 col-lg-6">
                            <p class="text-success fw-semibold mb-2">FRESH</p>
                            <h1 class="display-6 fw-bold mb-3">Thực phẩm sạch cho bữa ăn an tâm</h1>
                            <p class="lead text-secondary mb-0">Nguồn hàng rõ ràng, giàu dinh dưỡng và phù hợp cho gia đình hiện đại.</p>
                        </div>
                        <div class="col-12 col-lg-6">
                            <img src="{{ asset('frontend/images/slider1.png') }}" class="img-fluid" alt="Rau củ sạch Fresh">
                        </div>
                    </div>
                </div>

                <div class="carousel-item">
                    <div class="row align-items-center g-4">
                        <div class="col-12 col-lg-6">
                            <p class="text-success fw-semibold mb-2">AN TOÀN</p>
                            <h1 class="display-6 fw-bold mb-3">Nguồn gốc minh bạch, chất lượng ổn định</h1>
                            <p class="lead text-secondary mb-0">Sản phẩm được chọn lọc để giảm thời gian mua sắm và tăng sự tin cậy.</p>
                        </div>
                        <div class="col-12 col-lg-6">
                            <img src="{{ asset('frontend/images/slider2.png') }}" class="img-fluid" alt="Thực phẩm tươi an toàn">
                        </div>
                    </div>
                </div>

                <div class="carousel-item">
                    <div class="row align-items-center g-4">
                        <div class="col-12 col-lg-6">
                            <p class="text-success fw-semibold mb-2">BỀN VỮNG</p>
                            <h1 class="display-6 fw-bold mb-3">Lối sống xanh bắt đầu từ căn bếp</h1>
                            <p class="lead text-secondary mb-0">Ưu tiên sản phẩm tươi ngon, hỗ trợ thói quen tiêu dùng lành mạnh.</p>
                        </div>
                        <div class="col-12 col-lg-6">
                            <img src="{{ asset('frontend/images/slider3.png') }}" class="img-fluid" alt="Lối sống xanh với Fresh">
                        </div>
                    </div>
                </div>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Trước</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Sau</span>
            </button>
        </div>
    </div>
</section>

<main class="container py-4 py-lg-5">
    <div class="row g-4">
        <aside class="col-12 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white fw-semibold">Danh mục</div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action" href="{{ URL::to('/khuyen-mai-soc') }}">
                        Khuyến mãi sốc
                    </a>

                    @foreach($parentCategories as $parent)
                        @php
                            $children = DB::table('tbl_category_product')
                                ->where('parent_id', $parent->category_id)
                                ->where('category_status', '1')
                                ->orderBy('category_id', 'asc')
                                ->get();
                        @endphp

                        @if($children->isNotEmpty())
                            <div class="accordion accordion-flush" id="categoryAccordion{{ $parent->category_id }}">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed py-3" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#category{{ $parent->category_id }}"
                                                aria-expanded="false"
                                                aria-controls="category{{ $parent->category_id }}">
                                            {{ $parent->category_name }}
                                        </button>
                                    </h2>
                                    <div id="category{{ $parent->category_id }}" class="accordion-collapse collapse">
                                        <div class="list-group list-group-flush">
                                            @foreach($children as $child)
                                                <a class="list-group-item list-group-item-action ps-4"
                                                   href="{{ URL::to('/danh-muc-san-pham/'.$child->category_id) }}">
                                                    {{ $child->category_name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <a class="list-group-item list-group-item-action"
                               href="{{ URL::to('/danh-muc-san-pham/'.$parent->category_id) }}">
                                {{ $parent->category_name }}
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </aside>

        <section class="col-12 col-lg-9">
            @yield('content')
        </section>
    </div>
</main>

<footer class="bg-white border-top">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-12 col-lg-4">
                <h2 class="h4 fw-bold text-success">FRESH</h2>
                <p class="text-secondary mb-0">Cam kết mang đến thực phẩm sạch, an toàn và chất lượng cao cho mọi gia đình.</p>
            </div>
            <div class="col-12 col-sm-6 col-lg-2">
                <h3 class="h6 fw-semibold">Dịch vụ</h3>
                <ul class="list-unstyled small d-grid gap-2 mb-0">
                    <li><a class="link-secondary text-decoration-none" href="#">Hỗ trợ trực tuyến</a></li>
                    <li><a class="link-secondary text-decoration-none" href="#">Tình trạng đơn hàng</a></li>
                    <li><a class="link-secondary text-decoration-none" href="#">Câu hỏi thường gặp</a></li>
                </ul>
            </div>
            <div class="col-12 col-sm-6 col-lg-2">
                <h3 class="h6 fw-semibold">Chính sách</h3>
                <ul class="list-unstyled small d-grid gap-2 mb-0">
                    <li><a class="link-secondary text-decoration-none" href="#">Bảo mật</a></li>
                    <li><a class="link-secondary text-decoration-none" href="#">Hoàn tiền</a></li>
                    <li><a class="link-secondary text-decoration-none" href="#">Thanh toán</a></li>
                </ul>
            </div>
            <div class="col-12 col-lg-4">
                <h3 class="h6 fw-semibold">Nhận tin mới</h3>
                <form class="row g-2">
                    <div class="col-12 col-sm">
                        <label class="visually-hidden" for="newsletter-email">Email</label>
                        <input id="newsletter-email" type="email" class="form-control" placeholder="Email của bạn">
                    </div>
                    <div class="col-12 col-sm-auto">
                        <button type="submit" class="btn btn-success w-100">Đăng ký</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="border-top py-3">
        <div class="container d-flex flex-column flex-md-row justify-content-between gap-2 small text-secondary">
            <span>Copyright © 2025 FRESH Inc. All rights reserved.</span>
            <span>Designed by Trọng Phú, Hoàng Anh</span>
        </div>
    </div>
</footer>

<script src="{{ asset('frontend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
