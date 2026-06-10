@extends('welcome')

@section('title', 'Fresh | Tìm kiếm')

@section('content')
<section aria-labelledby="search-products-title">
    <div class="mb-4">
        <p class="text-success fw-semibold mb-1">Tìm kiếm</p>
        <h1 id="search-products-title" class="h3 fw-bold mb-0">Kết quả tìm kiếm</h1>
    </div>

    @if(isset($search_product) && count($search_product) > 0)
        <div class="row g-2 g-md-3">
            @foreach($search_product as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    @include('components.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info mb-0">
            {{ $message ?? 'Không tìm thấy sản phẩm phù hợp.' }}
        </div>
    @endif
</section>
@endsection
