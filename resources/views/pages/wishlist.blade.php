@extends('welcome')

@section('title', 'Fresh | Yêu thích')

@section('content')
<section aria-labelledby="wishlist-title">
    <div class="mb-4">
        <p class="text-success fw-semibold mb-1">Tài khoản</p>
        <h1 id="wishlist-title" class="h3 fw-bold mb-0">Danh sách yêu thích</h1>
    </div>

    @if(Session::has('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
    @endif
    @if(Session::has('error'))
        <div class="alert alert-danger">{{ Session::get('error') }}</div>
    @endif

    @if(!empty($wishlist) && count($wishlist) > 0)
        <div class="row g-4">
            @foreach($wishlist as $item)
                <div class="col-12 col-sm-6 col-xl-3">
                    <article class="card h-100 border-0 shadow-sm">
                        <a href="{{ URL::to('/chi-tiet-san-pham/'.$item['id']) }}" class="ratio ratio-4x3 bg-white">
                            <img src="{{ product_image_url($item['image'] ?? null) }}"
                                 class="img-fluid object-fit-contain p-3"
                                 alt="{{ $item['name'] }}">
                        </a>
                        <div class="card-body d-flex flex-column gap-3">
                            <div>
                                <h2 class="h6 mb-2">
                                    <a class="link-dark text-decoration-none" href="{{ URL::to('/chi-tiet-san-pham/'.$item['id']) }}">
                                        {{ $item['name'] }}
                                    </a>
                                </h2>
                                <div class="fw-bold text-success">
                                    {{ number_format($item['price']) }} VND/{{ str_replace('1 ', '', $item['unit']) }}
                                </div>
                            </div>

                            <div class="mt-auto d-grid gap-2">
                                <form action="{{ URL::to('/save-cart') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="productid_hidden" value="{{ $item['id'] }}">
                                    <input type="hidden" name="qty" value="1">
                                    <button type="submit" class="btn btn-success w-100">Thêm vào giỏ</button>
                                </form>

                                <form action="{{ route('wishlist.remove', $item['id']) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger w-100">Xóa khỏi yêu thích</button>
                                </form>
                            </div>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info mb-0">Chưa có sản phẩm nào trong danh sách yêu thích.</div>
    @endif
</section>
@endsection
