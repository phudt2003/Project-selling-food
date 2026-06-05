@extends('admin_layout')

@section('admin_title', 'Admin | Chi tiet thanh toan')
@section('page_heading', 'Chi tiet thanh toan')

@section('admin_content')
@php
    $paymentStatus = strtolower($payment->payment_status ?? '');
    $statusClass = str_contains($paymentStatus, 'paid') || str_contains($paymentStatus, 'da')
        ? 'bg-success'
        : 'bg-warning text-dark';
@endphp

<div class="row justify-content-center">
    <div class="col-12 col-xxl-10">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex flex-column flex-lg-row justify-content-between gap-3">
                <div>
                    <h1 class="h4 mb-1">Thanh toan #{{ $payment->payment_id }}</h1>
                    <p class="text-secondary mb-0">Thong tin giao dich va cac san pham da mua.</p>
                </div>
                <span class="badge {{ $statusClass }} align-self-start align-self-lg-center">
                    {{ $payment->payment_status }}
                </span>
            </div>

            <div class="card-body p-4">
                <div class="row g-4 mb-4">
                    <div class="col-12 col-lg-6">
                        <section class="h-100 rounded border p-3">
                            <h2 class="h6 text-uppercase text-secondary mb-3">Thong tin thanh toan</h2>
                            <dl class="row mb-0 gy-2">
                                <dt class="col-sm-4">Phuong thuc</dt>
                                <dd class="col-sm-8 mb-0">{{ $payment->payment_method }}</dd>
                                <dt class="col-sm-4">Trang thai</dt>
                                <dd class="col-sm-8 mb-0">{{ $payment->payment_status }}</dd>
                                <dt class="col-sm-4">Ngay gio</dt>
                                <dd class="col-sm-8 mb-0">{{ $payment->payment_date }}</dd>
                            </dl>
                        </section>
                    </div>

                    <div class="col-12 col-lg-6">
                        <section class="h-100 rounded border p-3">
                            <h2 class="h6 text-uppercase text-secondary mb-3">Lien he khach hang</h2>
                            <dl class="row mb-0 gy-2">
                                <dt class="col-sm-4">Email</dt>
                                <dd class="col-sm-8 mb-0 text-break">{{ $payment->customer_email }}</dd>
                                <dt class="col-sm-4">So dien thoai</dt>
                                <dd class="col-sm-8 mb-0">{{ $payment->customer_phone }}</dd>
                            </dl>
                        </section>
                    </div>
                </div>

                <h2 class="h5 mb-3">San pham da mua</h2>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Ten san pham</th>
                                <th scope="col" class="text-end">Gia</th>
                                <th scope="col" class="text-center">So luong</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td class="fw-semibold">{{ $product->product_name }}</td>
                                    <td class="text-end">{{ number_format($product->product_price, 0, ',', '.') }} VND</td>
                                    <td class="text-center">{{ $product->product_sales_quantity }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
