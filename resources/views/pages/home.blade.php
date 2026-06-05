@extends('welcome')

@section('title', 'Fresh | Trang chủ')

@section('content')
<div class="d-grid gap-5">
    <section aria-labelledby="shock-sale-title">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-2 mb-3">
            <div>
                <p class="text-success fw-semibold mb-1">Ưu đãi hôm nay</p>
                <h2 id="shock-sale-title" class="h3 fw-bold mb-0">Khuyến mãi sốc</h2>
            </div>
            <a href="{{ URL::to('/khuyen-mai-soc') }}" class="btn btn-outline-success">Xem tất cả</a>
        </div>

        @if($shock_sale_products->count() > 0)
            <div class="row g-4">
                @foreach($shock_sale_products as $product)
                    <div class="col-12 col-sm-6 col-xl-3">
                        @include('components.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info mb-0">Hiện tại không có sản phẩm khuyến mãi sốc.</div>
        @endif
    </section>

    <section aria-labelledby="featured-products-title">
        <div class="mb-3">
            <p class="text-success fw-semibold mb-1">Sản phẩm chọn lọc</p>
            <h2 id="featured-products-title" class="h3 fw-bold mb-0">Thực phẩm nổi bật</h2>
        </div>

        <div class="row g-4">
            @foreach($all_product as $product)
                <div class="col-12 col-sm-6 col-xl-3">
                    @include('components.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>
    </section>
</div>
@endsection
