@extends('admin_layout')

@section('admin_title', 'Admin | Chi tiet don hang')
@section('page_heading', 'Chi tiet don hang')

@section('admin_content')
@php
    $total = 0;
    $statusLabels = [
        1 => ['label' => 'Cho xu ly', 'class' => 'bg-warning text-dark'],
        2 => ['label' => 'Dang giao hang', 'class' => 'bg-info text-dark'],
        3 => ['label' => 'Da giao hang', 'class' => 'bg-success'],
        4 => ['label' => 'Da huy', 'class' => 'bg-danger'],
    ];
    $currentStatus = $statusLabels[$order->order_status] ?? ['label' => 'Khong xac dinh', 'class' => 'bg-secondary'];
@endphp

<div class="d-grid gap-4">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex flex-column flex-lg-row justify-content-between gap-3">
            <div>
                <h1 class="h4 mb-1">Don hang #{{ $order->order_id }}</h1>
                <p class="text-secondary mb-0">Theo doi thong tin khach hang, van chuyen va cac mat hang trong don.</p>
            </div>
            <span class="badge {{ $currentStatus['class'] }} align-self-start align-self-lg-center">
                {{ $currentStatus['label'] }}
            </span>
        </div>

        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-12 col-lg-6">
                    <section class="h-100 rounded border p-3">
                        <h2 class="h6 text-uppercase text-secondary mb-3">Thong tin khach hang</h2>
                        <dl class="row mb-0 gy-2">
                            <dt class="col-sm-4">Ten khach hang</dt>
                            <dd class="col-sm-8 mb-0">{{ $order->customer_name }}</dd>
                            <dt class="col-sm-4">So dien thoai</dt>
                            <dd class="col-sm-8 mb-0">{{ $order->customer_phone }}</dd>
                            <dt class="col-sm-4">Email</dt>
                            <dd class="col-sm-8 mb-0 text-break">{{ $order->customer_email }}</dd>
                        </dl>
                    </section>
                </div>

                <div class="col-12 col-lg-6">
                    <section class="h-100 rounded border p-3">
                        <h2 class="h6 text-uppercase text-secondary mb-3">Thong tin van chuyen</h2>
                        <dl class="row mb-0 gy-2">
                            <dt class="col-sm-4">Nguoi nhan</dt>
                            <dd class="col-sm-8 mb-0">{{ $order->shipping_name }}</dd>
                            <dt class="col-sm-4">Dia chi</dt>
                            <dd class="col-sm-8 mb-0">{{ $order->shipping_address }}</dd>
                            <dt class="col-sm-4">So dien thoai</dt>
                            <dd class="col-sm-8 mb-0">{{ $order->shipping_phone }}</dd>
                            <dt class="col-sm-4">Ghi chu</dt>
                            <dd class="col-sm-8 mb-0">{{ $order->order_notes ?? 'Khong co ghi chu' }}</dd>
                            <dt class="col-sm-4">Du kien nhan</dt>
                            <dd class="col-sm-8 mb-0">{{ $order->shipping_estimated_date ?? 'Chua cap nhat' }}</dd>
                        </dl>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h2 class="h5 mb-0">San pham trong don</h2>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="text-secondary">STT</th>
                            <th scope="col">Ten san pham</th>
                            <th scope="col" class="text-center">So luong</th>
                            <th scope="col" class="text-end">Gia</th>
                            <th scope="col" class="text-end">Tong tien</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order_details as $key => $details)
                            @php
                                $subtotal = $details->product_price * $details->product_sales_quantity;
                                $total += $subtotal;
                            @endphp
                            <tr>
                                <td class="text-secondary">{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $details->product_name }}</td>
                                <td class="text-center">{{ $details->product_sales_quantity }}</td>
                                <td class="text-end">{{ number_format($details->product_price, 0, ',', '.') }} d</td>
                                <td class="text-end fw-semibold">{{ number_format($subtotal, 0, ',', '.') }} d</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-lg-7">
                    <form action="{{ URL::to('/update-order-status/'.$order->order_id) }}" method="POST">
                        @csrf
                        <label for="order_status" class="form-label">Trang thai don hang</label>
                        <div class="d-flex flex-column flex-sm-row gap-2">
                            <select id="order_status" name="order_status" class="form-select">
                                <option value="1" {{ $order->order_status == 1 ? 'selected' : '' }}>Cho xu ly</option>
                                <option value="2" {{ $order->order_status == 2 ? 'selected' : '' }}>Dang giao hang</option>
                                <option value="3" {{ $order->order_status == 3 ? 'selected' : '' }}>Da giao hang</option>
                                <option value="4" {{ $order->order_status == 4 ? 'selected' : '' }}>Da huy</option>
                            </select>
                            <button type="submit" class="btn btn-primary flex-shrink-0">Cap nhat</button>
                        </div>
                        @if(session('update_success'))
                            <div class="text-success mt-2">{{ session('update_success') }}</div>
                        @endif
                    </form>
                </div>

                <div class="col-12 col-lg-5 text-lg-end">
                    <div class="text-secondary">Tong thanh toan</div>
                    <div class="h4 mb-0 text-danger">{{ number_format($total, 0, ',', '.') }} d</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
