@extends('admin_layout')

@section('admin_title', 'Admin | Tổng quan')
@section('page_heading', 'Tổng quan')

@section('admin_content')
<div class="d-grid gap-4">
    <div>
        <p class="text-secondary mb-1">Chào mừng bạn trở lại</p>
        <h1 class="h3 fw-bold mb-0">Bảng điều khiển Fresh Admin</h1>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-secondary mb-1">Đơn hàng</p>
                    <h2 class="h4 fw-bold mb-0">Quản lý nhanh</h2>
                    <a href="{{ URL::to('/manage-order') }}" class="btn btn-outline-success btn-sm mt-3">Xem đơn hàng</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-secondary mb-1">Sản phẩm</p>
                    <h2 class="h4 fw-bold mb-0">Danh mục hàng</h2>
                    <a href="{{ URL::to('/all-product') }}" class="btn btn-outline-success btn-sm mt-3">Xem sản phẩm</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-secondary mb-1">Thanh toán</p>
                    <h2 class="h4 fw-bold mb-0">Theo dõi giao dịch</h2>
                    <a href="{{ URL::to('/manage-payment') }}" class="btn btn-outline-success btn-sm mt-3">Xem thanh toán</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-secondary mb-1">Thống kê</p>
                    <h2 class="h4 fw-bold mb-0">Báo cáo bán hàng</h2>
                    <a href="{{ URL::to('/statistics-revenue') }}" class="btn btn-outline-success btn-sm mt-3">Xem báo cáo</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h2 class="h5 fw-bold mb-2">Công việc thường dùng</h2>
            <p class="text-secondary">Truy cập nhanh các thao tác quản trị chính mà không cần tìm trong menu.</p>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ URL::to('/add-product') }}" class="btn btn-success">Thêm sản phẩm</a>
                <a href="{{ URL::to('/add-category-product') }}" class="btn btn-outline-secondary">Thêm danh mục</a>
                <a href="{{ URL::to('/statistics-orders') }}" class="btn btn-outline-secondary">Thống kê đơn hàng</a>
            </div>
        </div>
    </div>
</div>
@endsection
