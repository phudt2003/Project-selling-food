@extends('admin_layout')

@section('admin_title', 'Admin | Đơn hàng')
@section('page_heading', 'Đơn hàng')

@section('admin_content')
<div class="d-grid gap-4">
    <div>
        <p class="text-secondary mb-1">Theo dõi và xử lý đơn hàng</p>
        <h1 class="h3 fw-bold mb-0">Danh sách đơn hàng</h1>
    </div>

    @if(Session::has('message'))
        <div class="alert alert-info mb-0">
            {{ Session::get('message') }}
            {{ Session::forget('message') }}
        </div>
    @endif

    <section class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th scope="col">Khách hàng</th>
                        <th scope="col">Tổng giá tiền</th>
                        <th scope="col">Tình trạng</th>
                        <th scope="col" class="text-end">Thao tác</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($all_order as $order)
                        <tr>
                            <td class="fw-semibold">{{ $order->customer_name }}</td>
                            <td>{{ $order->order_total }}</td>
                            <td>
                                <span class="badge text-bg-secondary">{{ $order->order_status }}</span>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ URL::to('/view-order/'.$order->order_id) }}" class="btn btn-outline-secondary btn-sm">Xem</a>
                                    <form action="{{ URL::to('/delete-order/'.$order->order_id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này không?');"
                                                class="btn btn-outline-danger btn-sm">
                                            Xóa
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <small class="text-secondary">
                    Hiển thị {{ $all_order->firstItem() }} đến {{ $all_order->lastItem() }} trong tổng số {{ $all_order->total() }} đơn hàng
                </small>
                {{ $all_order->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </section>
</div>
@endsection
