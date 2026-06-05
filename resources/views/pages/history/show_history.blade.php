@extends('welcome')
@section('content')

<div class="container" style="margin-top: 20px; margin-bottom: 20px;">
    <div class="card">
        <div class="card-header text-center">
            <h3>Lịch Sử Đơn Hàng Của Bạn</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Mã đơn hàng</th>
                            <th scope="col">Ngày đặt</th>
                            <th scope="col">Tình trạng đơn hàng</th>
                            <th scope="col">Tổng tiền</th>
                            <th scope="col">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($orders->isNotEmpty())
                            @foreach($orders as $order)
                            <tr>
                                <td>#{{ $order->order_id }}</td>
                                {{-- Hiển thị đúng thời gian từ DB --}}
                                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</td>
                                <td>
                                    {{-- Đã sửa: Thêm đủ các trường hợp cho trạng thái --}}
                                    @if($order->order_status == 1)
                                        <span class="badge badge-warning">Đang chờ xử lý</span>
                                    @elseif($order->order_status == 2)
                                        <span class="badge badge-info">Đang giao hàng</span>
                                    @elseif($order->order_status == 3)
                                        <span class="badge badge-success">Đã giao hàng thành công</span>
                                    @elseif($order->order_status == 4)
                                        <span class="badge badge-danger">Đã hủy</span>
                                    @else
                                        <span class="badge badge-secondary">Không rõ</span>
                                    @endif
                                </td>
                                <td>{{ number_format($order->order_total, 0, ',', '.') }} đ</td>
                                <td>
                                    {{-- Đã sửa: Trỏ link đến route mới dành cho khách hàng --}}
                                    <a href="{{ URL::to('/xem-don-hang/'.$order->order_id) }}" class="btn btn-sm btn-primary">Xem chi tiết</a>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center">Bạn chưa có đơn hàng nào.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection