@extends('welcome')

@section('title', 'Fresh | Giỏ hàng')

@section('content')
@php
    $content = Cart::content();
    $customerId = Session::get('customer_id');
@endphp

<div class="d-grid gap-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ URL::to('/') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Giỏ hàng</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h1 class="h4 mb-0">Giỏ hàng của bạn</h1>
        </div>
        <div class="card-body p-0">
            <div class="d-md-none">
                <div class="list-group list-group-flush">
                    @forelse($content as $item)
                        <div class="list-group-item p-3">
                            <div class="d-flex gap-3">
                                <img src="{{ asset('uploads/product/' . $item->options->image) }}"
                                     class="img-fluid rounded flex-shrink-0"
                                     style="width:72px; height:72px; object-fit:cover"
                                     alt="{{ $item->name }}">
                                <div class="flex-grow-1 min-w-0">
                                    <h2 class="h6 mb-2">{{ $item->name }}</h2>
                                    <div class="d-grid gap-2 small">
                                        <div class="d-flex justify-content-between gap-3">
                                            <span class="text-secondary">Giá</span>
                                            <span class="fw-semibold text-end">{{ number_format($item->price) }} VND</span>
                                        </div>
                                        <div class="d-flex justify-content-between gap-3">
                                            <span class="text-secondary">Tổng tiền</span>
                                            <span class="fw-semibold text-end">{{ number_format($item->price * $item->qty) }} VND</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 d-grid gap-2">
                                <form action="{{ URL::to('/update-cart-quantity') }}" method="POST" class="d-flex gap-2">
                                    @csrf
                                    <input type="hidden" name="rowId_cart" value="{{ $item->rowId }}">
                                    <label class="visually-hidden" for="mobile-qty-{{ $item->rowId }}">Số lượng</label>
                                    <input id="mobile-qty-{{ $item->rowId }}" class="form-control"
                                           type="number" name="cart_quantity" min="1" value="{{ $item->qty }}">
                                    <button class="btn btn-outline-secondary text-nowrap" type="submit">Cập nhật</button>
                                </form>

                                <a class="btn btn-outline-danger" href="{{ URL::to('/delete-to-cart/'.$item->rowId) }}">
                                    Xóa
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item text-center text-secondary py-4">Giỏ hàng đang trống.</div>
                    @endforelse
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
                        <th scope="col" class="text-end">Thao tác</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($content as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ asset('uploads/product/' . $item->options->image) }}"
                                         class="img-fluid rounded"
                                         style="width:72px"
                                         alt="{{ $item->name }}">
                                    <div class="fw-semibold">{{ $item->name }}</div>
                                </div>
                            </td>
                            <td>{{ number_format($item->price) }} VND</td>
                            <td>
                                <form action="{{ URL::to('/update-cart-quantity') }}" method="POST" class="d-flex align-items-center gap-2">
                                    @csrf
                                    <input type="hidden" name="rowId_cart" value="{{ $item->rowId }}">
                                    <label class="visually-hidden" for="qty-{{ $item->rowId }}">Số lượng</label>
                                    <input id="qty-{{ $item->rowId }}" class="form-control form-control-sm" style="max-width:90px"
                                           type="number" name="cart_quantity" min="1" value="{{ $item->qty }}">
                                    <button class="btn btn-outline-secondary btn-sm" type="submit">Cập nhật</button>
                                </form>
                            </td>
                            <td class="fw-semibold">{{ number_format($item->price * $item->qty) }} VND</td>
                            <td class="text-end">
                                <a class="btn btn-outline-danger btn-sm" href="{{ URL::to('/delete-to-cart/'.$item->rowId) }}">
                                    Xóa
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-secondary py-4">Giỏ hàng đang trống.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row justify-content-end">
        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-3">Tóm tắt đơn hàng</h2>
                    <dl class="row mb-0">
                        <dt class="col-6 fw-normal text-secondary">Tổng</dt>
                        <dd class="col-6 text-end">{{ number_format((float) str_replace(',', '', Cart::subtotal()), 0, ',', '.') }} vnđ</dd>
                        <dt class="col-6 fw-normal text-secondary">Phí vận chuyển</dt>
                        <dd class="col-6 text-end"><span class="badge text-bg-success">Free</span></dd>
                        <dt class="col-6">Thành tiền</dt>
                        <dd class="col-6 text-end fw-bold">{{ number_format((float) str_replace(',', '', Cart::total()), 0, ',', '.') }} vnđ</dd>
                    </dl>
                    <a class="btn btn-success btn-lg w-100 mt-3" href="{{ $customerId ? URL::to('/checkout') : URL::to('/login-checkout') }}">
                        Đặt hàng
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
