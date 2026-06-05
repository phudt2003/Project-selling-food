@extends('welcome')

@section('title', 'Fresh | Cập nhật địa chỉ')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 mb-4">Cập nhật địa chỉ</h1>

                <form action="{{ URL::to('/update-address/'.$shipping_info->shipping_id) }}" method="POST" class="row g-3">
                    @csrf

                    <div class="col-12">
                        <label for="shipping_name" class="form-label">Họ và tên</label>
                        <input id="shipping_name" type="text" name="shipping_name" value="{{ $shipping_info->shipping_name }}" class="form-control" required>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="shipping_phone" class="form-label">Số điện thoại</label>
                        <input id="shipping_phone" type="tel" name="shipping_phone" value="{{ $shipping_info->shipping_phone }}" class="form-control" required>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="shipping_email" class="form-label">Email</label>
                        <input id="shipping_email" type="email" name="shipping_email" value="{{ $shipping_info->shipping_email }}" class="form-control" required>
                    </div>

                    <div class="col-12">
                        <label for="shipping_address" class="form-label">Địa chỉ</label>
                        <input id="shipping_address" type="text" name="shipping_address" value="{{ $shipping_info->shipping_address }}" class="form-control" required>
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input id="is_default" type="checkbox" name="is_default" class="form-check-input" {{ $shipping_info->is_default ? 'checked' : '' }}>
                            <label for="is_default" class="form-check-label">Đặt làm địa chỉ mặc định</label>
                        </div>
                    </div>

                    <div class="col-12 d-grid d-md-flex justify-content-md-end gap-2">
                        <a href="{{ URL::to('/checkout') }}" class="btn btn-outline-secondary">Hủy</a>
                        <button type="submit" class="btn btn-success">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
