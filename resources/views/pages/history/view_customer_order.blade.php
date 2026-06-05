@extends('welcome')
@section('content')

<div class="container" style="margin-top: 20px; margin-bottom: 20px;">
    <a href="{{ URL::to('/lich-su-don-hang') }}" class="btn btn-link mb-3"><i class="fa fa-arrow-left"></i> Quay lại Lịch sử đơn hàng</a>
    
    {{-- Bảng thông tin vận chuyển --}}
<div class="card mb-4">
    <div class="card-header">
        <h4>Thông tin giao hàng</h4>
    </div>
    <div class="card-body">
        <p><strong>Tên người nhận:</strong> {{ $order->shipping_name }}</p>
        <p><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>
        <p><strong>Số điện thoại:</strong> {{ $order->shipping_phone }}</p>
        <p><strong>Ghi chú:</strong> {{ $order->order_notes }}</p>
        
    </div>
</div>

    {{-- Bảng chi tiết đơn hàng --}}
    <div class="card">
        <div class="card-header">
            <h4>Chi tiết đơn hàng #{{ $order->order_id }}</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped b-t b-light">
                    <thead>
                        <tr>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Giá</th>
                            <th>Tổng tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach($order_details as $details)
                            @php
                                $subtotal = $details->product_price * $details->product_sales_quantity;
                                $total += $subtotal;
                            @endphp
                            <tr>
                                <td>{{ $details->product_name }}</td>
                                <td>{{ $details->product_sales_quantity }}</td>
                                <td>{{ number_format($details->product_price, 0, ',', '.') }} đ</td>
                                <td>{{ number_format($subtotal, 0, ',', '.') }} đ</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right"><strong>Tổng cộng:</strong></td>
                            <td><strong>{{ number_format($total, 0, ',', '.') }} đ</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection