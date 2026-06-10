@extends('welcome')

@section('title', 'Fresh | Khuyến mãi')

@section('content')
<section aria-labelledby="sale-products-title">
    <div class="mb-4">
        <p class="text-success fw-semibold mb-1">Ưu đãi</p>
        <h1 id="sale-products-title" class="h3 fw-bold mb-0">Sản phẩm khuyến mãi</h1>
    </div>

    @if($shock_sale_products->count() > 0)
        <div class="row g-2 g-md-3">
            @foreach($shock_sale_products as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    @include('components.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info mb-0">Hiện tại không có sản phẩm khuyến mãi.</div>
    @endif
</section>
@endsection
