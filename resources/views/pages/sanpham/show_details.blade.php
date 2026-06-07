@extends('welcome')

@section('title', 'Fresh | Chi tiết sản phẩm')

@section('content')
@foreach($product_details as $value)
    @php
        $price = (float) $value->product_price;
        $discount = (int) ($value->discount_percentage ?? 0);
        $salePrice = $discount > 0 ? $price - ($price * $discount / 100) : $price;
        $isFallback = (bool) ($value->is_fallback ?? false);
    @endphp

    <div class="d-grid gap-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ URL::to('/') }}">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $value->product_name }}</li>
            </ol>
        </nav>

        <section class="card border-0 shadow-sm">
            <div class="card-body p-3 p-lg-4">
                <div class="row g-4 align-items-start">
                    <div class="col-12 col-lg-5">
                        <div class="position-relative bg-white rounded border">
                            <div class="ratio ratio-4x3">
                                <img src="{{ product_image_url($value->product_image ?? null) }}"
                                     class="img-fluid object-fit-contain p-3"
                                     alt="{{ $value->product_name }}">
                            </div>
                            @if($discount > 0)
                                <span class="badge text-bg-danger position-absolute top-0 end-0 m-3">-{{ $discount }}%</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-lg-7">
                        <div class="d-grid gap-3">
                            <div>
                                <p class="text-success fw-semibold mb-1">{{ $value->category_name ?? 'Fresh' }}</p>
                                <h1 class="h3 fw-bold mb-0">{{ $value->product_name }}</h1>
                            </div>

                            <div>
                                @if($discount > 0)
                                    <div class="text-secondary text-decoration-line-through">{{ number_format($price) }} VND</div>
                                    <div class="h4 text-danger fw-bold mb-0">{{ number_format($salePrice) }} VND</div>
                                @else
                                    <div class="h4 text-success fw-bold mb-0">{{ number_format($price) }} VND</div>
                                @endif
                            </div>

                            <div class="row g-2 small">
                                <div class="col-12 col-sm-6"><span class="text-secondary">Mã ID:</span> {{ $value->product_id }}</div>
                                <div class="col-12 col-sm-6"><span class="text-secondary">Tình trạng:</span> <span class="badge text-bg-success">Còn hàng</span></div>
                                <div class="col-12 col-sm-6"><span class="text-secondary">Công ty:</span> {{ $value->product_company }}</div>
                                <div class="col-12 col-sm-6"><span class="text-secondary">Đơn vị:</span> {{ $value->product_unit }}</div>
                                <div class="col-12 col-sm-6">
                                    <span class="text-secondary">Ngày sản xuất:</span>
                                    {{ $value->product_date ? \Carbon\Carbon::parse($value->product_date)->format('d/m/Y') : 'N/A' }}
                                </div>
                                <div class="col-12 col-sm-6">
                                    <span class="text-secondary">Ngày hết hạn:</span>
                                    {{ $value->expiration_date ? \Carbon\Carbon::parse($value->expiration_date)->format('d/m/Y') : 'N/A' }}
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    @if($isFallback)
                                        <div class="card h-100">
                                            <div class="card-body d-grid gap-3">
                                                <label for="qty" class="form-label">Số lượng</label>
                                                <input id="qty" type="number" min="1" value="1" class="form-control" disabled>
                                                <button type="button" class="btn btn-success" disabled>Thêm vào giỏ</button>
                                            </div>
                                        </div>
                                    @else
                                        <form action="{{ URL::to('/save-cart') }}" method="POST" class="card h-100">
                                            @csrf
                                            <div class="card-body d-grid gap-3">
                                                <input type="hidden" name="productid_hidden" value="{{ $value->product_id }}">
                                                <div>
                                                    <label for="qty" class="form-label">Số lượng</label>
                                                    <input id="qty" type="number" name="qty" min="1" value="1" class="form-control" required>
                                                </div>
                                                <button type="submit" class="btn btn-success">Thêm vào giỏ</button>
                                            </div>
                                        </form>
                                    @endif
                                </div>

                                <div class="col-12 col-md-6">
                                    @if($isFallback)
                                        <div class="card h-100">
                                            <div class="card-body d-grid gap-3">
                                                <label for="weight" class="form-label">Khối lượng gram</label>
                                                <input id="weight" type="number" min="50" step="50" value="100" class="form-control" disabled>
                                                <button type="button" class="btn btn-outline-success" disabled>Thêm theo gram</button>
                                            </div>
                                        </div>
                                    @else
                                        <form action="{{ URL::to('/save-cart-by-weight') }}" method="POST" class="card h-100">
                                            @csrf
                                            <div class="card-body d-grid gap-3">
                                                <input type="hidden" name="productid_hidden" value="{{ $value->product_id }}">
                                                <div>
                                                    <label for="weight" class="form-label">Khối lượng gram</label>
                                                    <input id="weight" name="weight" type="number" min="50" step="50" value="100" class="form-control" required>
                                                </div>
                                                <button type="submit" class="btn btn-outline-success">Thêm theo gram</button>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="card border-0 shadow-sm">
            <div class="card-body">
                <ul class="nav nav-tabs" id="productTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc-pane"
                                type="button" role="tab" aria-controls="desc-pane" aria-selected="true">Mô tả</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="content-tab" data-bs-toggle="tab" data-bs-target="#content-pane"
                                type="button" role="tab" aria-controls="content-pane" aria-selected="false">Chi tiết</button>
                    </li>
                </ul>
                <div class="tab-content pt-3">
                    <div class="tab-pane fade show active" id="desc-pane" role="tabpanel" aria-labelledby="desc-tab" tabindex="0">
                        {!! $value->product_desc !!}
                    </div>
                    <div class="tab-pane fade" id="content-pane" role="tabpanel" tabindex="0">
                        {!! $value->product_content !!}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endforeach

<section class="mt-5" aria-labelledby="related-products-title">
    <div class="mb-4">
        <p class="text-success fw-semibold mb-1">Gợi ý thêm</p>
        <h2 id="related-products-title" class="h3 fw-bold mb-0">Sản phẩm liên quan</h2>
    </div>

    <div class="row g-4">
        @foreach($relate as $product)
            <div class="col-12 col-sm-6 col-xl-3">
                @include('components.product-card', ['product' => $product])
            </div>
        @endforeach
    </div>
</section>
@endsection
