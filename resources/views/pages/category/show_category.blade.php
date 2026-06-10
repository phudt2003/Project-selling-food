@extends('welcome')

@section('title', 'Fresh | Danh mục')

@section('content')
<section aria-labelledby="category-products-title">
    <div class="mb-4">
        @foreach($category_name as $name)
            <p class="text-success fw-semibold mb-1">Danh mục</p>
            <h1 id="category-products-title" class="h3 fw-bold mb-0">{{ $name->category_name }}</h1>
        @endforeach
    </div>

    @if($category_by_id->count() > 0)
        <div class="row g-2 g-md-3">
            @foreach($category_by_id as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    @include('components.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info mb-0">Danh mục này chưa có sản phẩm.</div>
    @endif
</section>
@endsection
