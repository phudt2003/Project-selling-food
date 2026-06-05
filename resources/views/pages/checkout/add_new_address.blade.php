@extends('welcome')

@section('title', 'Fresh | Thêm địa chỉ')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ URL::to('/') }}">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ URL::to('/checkout') }}">Thanh toán</a></li>
                <li class="breadcrumb-item active" aria-current="page">Thêm địa chỉ</li>
            </ol>
        </nav>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 mb-2">Thêm địa chỉ mới</h1>
                <p class="text-secondary mb-4">Vui lòng điền đầy đủ thông tin để giao hàng chính xác.</p>

                <form action="{{ URL::to('/save-new-address') }}" method="POST" class="row g-3">
                    @csrf

                    <div class="col-12">
                        <label for="shipping_name" class="form-label">Họ và tên người nhận</label>
                        <input id="shipping_name" type="text" name="shipping_name" class="form-control" required autocomplete="name">
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="shipping_phone" class="form-label">Số điện thoại</label>
                        <input id="shipping_phone" type="tel" name="shipping_phone" class="form-control" required autocomplete="tel">
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="shipping_email" class="form-label">Email</label>
                        <input id="shipping_email" type="email" name="shipping_email" class="form-control" required autocomplete="email">
                    </div>

                    <div class="col-12">
                        <label for="shipping_address" class="form-label">Địa chỉ chi tiết</label>
                        <textarea id="shipping_address" name="shipping_address" rows="4" class="form-control" required autocomplete="street-address"></textarea>
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input id="is_default" type="checkbox" name="is_default" value="1" class="form-check-input">
                            <label for="is_default" class="form-check-label">Đặt làm địa chỉ mặc định</label>
                        </div>
                    </div>

                    <div class="col-12 d-grid d-md-flex justify-content-md-end gap-2">
                        <a href="{{ URL::to('/checkout') }}" class="btn btn-outline-secondary">Trở về</a>
                        <button type="submit" class="btn btn-success">Lưu địa chỉ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
