@extends('welcome')

@section('title', 'Fresh | Chọn địa chỉ')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-4">
                    <div>
                        <p class="text-success fw-semibold mb-1">Thanh toán</p>
                        <h1 class="h4 mb-0">Địa chỉ của tôi</h1>
                    </div>
                    <a href="{{ URL::to('/add_new_address') }}" class="btn btn-outline-success">Thêm địa chỉ mới</a>
                </div>

                @if(Session::has('customer_id'))
                    <div id="address-list-container" class="list-group mb-4">
                        @if(isset($all_shipping_info) && !$all_shipping_info->isEmpty())
                            @foreach($all_shipping_info as $address)
                                <div class="list-group-item">
                                    <div class="d-flex flex-column flex-md-row gap-3">
                                        <div class="form-check flex-grow-1">
                                            <input class="form-check-input" type="radio" name="shipping_id"
                                                   value="{{ $address->shipping_id }}"
                                                   id="address_{{ $address->shipping_id }}"
                                                   {{ $address->is_default ? 'checked' : '' }}>
                                            <label class="form-check-label w-100" for="address_{{ $address->shipping_id }}">
                                                <span class="fw-semibold d-block">
                                                    {{ $address->shipping_name }} (+84) {{ $address->shipping_phone }}
                                                    @if($address->is_default)
                                                        <span class="badge text-bg-success ms-2">Mặc định</span>
                                                    @endif
                                                </span>
                                                <span class="text-secondary d-block mt-1">{{ $address->shipping_address }}</span>
                                            </label>
                                        </div>

                                        <div class="d-flex gap-2 align-items-start">
                                            <a href="{{ URL::to('/edit-address/'.$address->shipping_id) }}" class="btn btn-outline-secondary btn-sm">Cập nhật</a>
                                            <form action="{{ URL::to('/delete-address/'.$address->shipping_id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                                        onclick="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này không?')">
                                                    Xóa
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="list-group-item text-center text-secondary py-4">
                                Bạn chưa có địa chỉ nào được lưu. Vui lòng thêm địa chỉ mới.
                            </div>
                        @endif
                    </div>

                    <form action="{{ URL::to('/order-place') }}" method="POST" id="confirm-address-form">
                        @csrf
                        <input type="hidden" name="shipping_id" id="selected_shipping_id">
                        <div class="d-grid d-md-flex justify-content-md-end gap-2">
                            <a href="{{ URL::to('/show-cart') }}" class="btn btn-outline-secondary">Hủy</a>
                            <button type="submit" class="btn btn-success">Xác nhận</button>
                        </div>
                    </form>

                    <script>
                        document.getElementById('confirm-address-form').addEventListener('submit', function(event) {
                            const selectedRadio = document.querySelector('#address-list-container input[name="shipping_id"]:checked');
                            if (selectedRadio) {
                                document.getElementById('selected_shipping_id').value = selectedRadio.value;
                                return;
                            }
                            alert('Vui lòng chọn một địa chỉ giao hàng.');
                            event.preventDefault();
                        });
                    </script>
                @else
                    <div class="alert alert-warning mb-0">Vui lòng đăng nhập để chọn địa chỉ giao hàng.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
