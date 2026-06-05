@php
    $price = (float) $product->product_price;
    $discount = (int) ($product->discount_percentage ?? 0);
    $salePrice = $discount > 0 ? $price - ($price * $discount / 100) : $price;
    $unit = str_replace('1 ', '', $product->product_unit);
@endphp

<article class="card h-100 border-0 shadow-sm">
    <a href="{{ URL::to('/chi-tiet-san-pham/'.$product->product_id) }}" class="ratio ratio-4x3 bg-white">
        <img src="{{ asset('uploads/product/' . $product->product_image) }}"
             class="img-fluid object-fit-contain p-3"
             alt="{{ $product->product_name }}">
    </a>

    <div class="card-body d-flex flex-column gap-3">
        <div class="d-flex justify-content-between align-items-start gap-2">
            <h3 class="h6 mb-0">
                <a class="link-dark text-decoration-none" href="{{ URL::to('/chi-tiet-san-pham/'.$product->product_id) }}">
                    {{ $product->product_name }}
                </a>
            </h3>
            @if($discount > 0)
                <span class="badge text-bg-danger">-{{ $discount }}%</span>
            @endif
        </div>

        <div>
            @if($discount > 0)
                <div class="small text-secondary text-decoration-line-through">
                    {{ number_format($price) }} VND/{{ $unit }}
                </div>
                <div class="fw-bold text-danger">
                    {{ number_format($salePrice) }} VND/{{ $unit }}
                </div>
            @else
                <div class="fw-bold text-success">
                    {{ number_format($price) }} VND/{{ $unit }}
                </div>
            @endif
        </div>

        <div class="mt-auto d-grid gap-2">
            <form action="{{ URL::to('/save-cart') }}" method="POST">
                @csrf
                <input type="hidden" name="productid_hidden" value="{{ $product->product_id }}">
                <input type="hidden" name="qty" value="1">
                <button type="submit" class="btn btn-success w-100">Thêm vào giỏ</button>
            </form>

            <form action="{{ route('wishlist.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                <button type="submit" class="btn btn-outline-secondary w-100">Yêu thích</button>
            </form>
        </div>
    </div>
</article>
