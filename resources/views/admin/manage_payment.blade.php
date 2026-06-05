@extends('admin_layout')

@section('admin_title', 'Admin | Thanh toán')
@section('page_heading', 'Thanh toán')

@section('admin_content')
<div class="d-grid gap-4">
    <div>
        <p class="text-secondary mb-1">Theo dõi trạng thái giao dịch</p>
        <h1 class="h3 fw-bold mb-0">Danh sách thanh toán</h1>
    </div>

    <section class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Khách hàng</th>
                        <th scope="col">Phương thức</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col">Mã giao dịch</th>
                        <th scope="col">Tổng tiền</th>
                        <th scope="col">Ngày thanh toán</th>
                        <th scope="col" class="text-end">Hành động</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($all_payment as $pay)
                        <tr>
                            <td class="fw-semibold">#{{ $pay->payment_id }}</td>
                            <td>
                                <div class="fw-semibold">{{ $pay->customer_name }}</div>
                                <div class="small text-secondary">{{ $pay->customer_email }} · {{ $pay->customer_phone }}</div>
                            </td>
                            <td>{{ $pay->payment_method }}</td>
                            <td>
                                @if($pay->payment_status == 'Đã thanh toán')
                                    <span class="badge text-bg-success">Thành công</span>
                                @elseif($pay->payment_status == 'Đang chờ xử lý')
                                    <span class="badge text-bg-warning">Đang xử lý</span>
                                @else
                                    <span class="badge text-bg-danger">Thất bại</span>
                                @endif
                            </td>
                            <td>{{ $pay->payment_code ?? '---' }}</td>
                            <td class="fw-semibold">{{ number_format($pay->order_total, 0, ',', '.') }} đ</td>
                            <td>{{ \Carbon\Carbon::parse($pay->created_at)->format('d/m/Y H:i:s') }}</td>
                            <td class="text-end">
                                <a href="{{ URL::to('/view-payment/'.$pay->payment_id) }}" class="btn btn-outline-secondary btn-sm">
                                    Xem chi tiết
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-center">
                {{ $all_payment->links() }}
            </div>
        </div>
    </section>
</div>
@endsection
