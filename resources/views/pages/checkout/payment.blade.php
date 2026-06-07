@extends('welcome')

@section('title', 'Fresh | Thanh toán')

@section('content')
@php
    $content = Cart::content();
@endphp

<div class="d-grid gap-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ URL::to('/') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Thanh toán</li>
        </ol>
    </nav>

    <div>
        <p class="text-success fw-semibold mb-1">Kiểm tra đơn hàng</p>
        <h1 class="h3 fw-bold mb-0">Thanh toán giỏ hàng</h1>
    </div>

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    @if(isset($shipping_info) && $shipping_info)
        <section class="card border-0 shadow-sm" aria-labelledby="shipping-title">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                    <div>
                        <h2 id="shipping-title" class="h5 mb-3">Địa chỉ giao hàng</h2>
                        <p class="fw-semibold mb-1">{{ $shipping_info->shipping_name }}</p>
                        <p class="mb-1">{{ $shipping_info->shipping_phone }}</p>
                        <p class="mb-1">{{ $shipping_info->shipping_email }}</p>
                        <p class="mb-0 text-secondary">{{ $shipping_info->shipping_address }}</p>
                        @if(!empty($shipping_info->shipping_notes))
                            <p class="mt-2 mb-0"><span class="fw-semibold">Ghi chú:</span> {{ $shipping_info->shipping_notes }}</p>
                        @endif
                    </div>
                    <div>
                        <a href="{{ URL::to('/checkout') }}" class="btn btn-outline-secondary">Chỉnh sửa địa chỉ</a>
                    </div>
                </div>
            </div>
        </section>
    @else
        <div class="alert alert-warning">
            Bạn chưa có địa chỉ giao hàng. <a href="{{ route('add_new_address') }}" class="alert-link">Thêm địa chỉ</a> trước khi thanh toán.
        </div>
    @endif

    <section class="card border-0 shadow-sm" aria-labelledby="cart-review-title">
        <div class="card-header bg-white">
            <h2 id="cart-review-title" class="h5 mb-0">Sản phẩm trong giỏ</h2>
        </div>
        <div class="card-body p-0">
            <div class="d-md-none">
                <div class="list-group list-group-flush">
                    @foreach($content as $item)
                        <div class="list-group-item p-3">
                            <div class="d-flex gap-3">
                                <img src="{{ product_image_url($item->options->image ?? null) }}"
                                     class="img-fluid rounded flex-shrink-0"
                                     style="width:72px; height:72px; object-fit:cover"
                                     alt="{{ $item->name }}">
                                <div class="flex-grow-1 min-w-0">
                                    <h3 class="h6 mb-2">{{ $item->name }}</h3>
                                    <div class="d-grid gap-2 small">
                                        <div class="d-flex justify-content-between gap-3">
                                            <span class="text-secondary">Giá</span>
                                            <span class="fw-semibold text-end">{{ number_format($item->price) }} VND</span>
                                        </div>
                                        <div class="d-flex justify-content-between gap-3">
                                            <span class="text-secondary">Số lượng</span>
                                            <span class="fw-semibold">{{ $item->qty }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between gap-3">
                                            <span class="text-secondary">Tổng tiền</span>
                                            <span class="fw-semibold text-end">{{ number_format($item->price * $item->qty) }} VND</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="table-responsive d-none d-md-block">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th scope="col">Sản phẩm</th>
                        <th scope="col">Giá</th>
                        <th scope="col">Số lượng</th>
                        <th scope="col">Tổng tiền</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($content as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ product_image_url($item->options->image ?? null) }}"
                                         class="img-fluid rounded"
                                         style="width:72px"
                                         alt="{{ $item->name }}">
                                    <span class="fw-semibold">{{ $item->name }}</span>
                                </div>
                            </td>
                            <td>{{ number_format($item->price) }} VND</td>
                            <td>{{ $item->qty }}</td>
                            <td class="fw-semibold">{{ number_format($item->price * $item->qty) }} VND</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    @if(!isset($shipping_info) || !$shipping_info)
        <div class="alert alert-danger">Vui lòng thêm địa chỉ giao hàng trước khi đặt hàng.</div>
    @else
        <form action="{{ route('complete_order') }}" method="POST" class="card border-0 shadow-sm">
            @csrf
            <div class="card-body d-grid gap-4">
                <div>
                    <label for="order_notes" class="form-label">Ghi chú cho đơn hàng</label>
                    <textarea name="order_notes" id="order_notes" rows="3" class="form-control"
                              placeholder="VD: Giao ngoài giờ hành chính, gọi trước khi giao..."></textarea>
                </div>

                <fieldset>
                    <legend class="h5 mb-3">Hình thức thanh toán</legend>
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="card h-100">
                                <span class="card-body d-flex gap-3 align-items-center">
                                    <input class="form-check-input mt-0" name="payment_option" value="momo" type="radio" required>
                                    <span>
                                        <span class="fw-semibold d-block">QR MoMo</span>
                                        <span class="small text-secondary">Thanh toán qua mã QR</span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="card h-100">
                                <span class="card-body d-flex gap-3 align-items-center">
                                    <input class="form-check-input mt-0" name="payment_option" value="momo_test" type="radio" required>
                                    <span>
                                        <span class="fw-semibold d-block">MoMo Napas test</span>
                                        <span class="small text-secondary">Cổng thanh toán thử nghiệm</span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="card h-100">
                                <span class="card-body d-flex gap-3 align-items-center">
                                    <input class="form-check-input mt-0" name="payment_option" value="atm" type="radio" required>
                                    <span>
                                        <span class="fw-semibold d-block">Thẻ ATM</span>
                                        <span class="small text-secondary">Thanh toán bằng thẻ ngân hàng</span>
                                    </span>
                                </span>
                            </label>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="card h-100">
                                <span class="card-body d-flex gap-3 align-items-center">
                                    <input class="form-check-input mt-0" name="payment_option" value="cod" type="radio" required>
                                    <span>
                                        <span class="fw-semibold d-block">Thanh toán khi nhận hàng</span>
                                        <span class="small text-secondary">Trả tiền sau khi nhận đơn</span>
                                    </span>
                                </span>
                            </label>
                        </div>
                    </div>
                </fieldset>

                <div class="d-grid d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-success btn-lg">Xác nhận đặt hàng</button>
                </div>
            </div>
        </form>
    @endif
</div>
@endsection
