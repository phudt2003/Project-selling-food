@extends('admin_layout')

@section('admin_title', 'Admin | Sản phẩm')
@section('page_heading', 'Sản phẩm')

@section('admin_content')
<div class="d-grid gap-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <p class="text-secondary mb-1">Quản lý danh mục hàng bán</p>
            <h1 class="h3 fw-bold mb-0">Danh sách sản phẩm</h1>
        </div>
        <a href="{{ URL::to('/add-product') }}" class="btn btn-success">Thêm sản phẩm</a>
    </div>

    @if(Session::has('message'))
        <div class="alert alert-info mb-0">
            {{ Session::get('message') }}
            {{ Session::forget('message') }}
        </div>
    @endif

    <section class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <div class="row g-3 align-items-center">
                <div class="col-12 col-md">
                    <h2 class="h5 mb-0">Tất cả sản phẩm</h2>
                </div>
                <div class="col-12 col-md-auto">
                    <div class="input-group">
                        <label class="visually-hidden" for="product-search">Tìm kiếm</label>
                        <input id="product-search" type="search" class="form-control" placeholder="Tìm sản phẩm">
                        <button class="btn btn-outline-secondary" type="button">Tìm</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th scope="col">Sản phẩm</th>
                        <th scope="col">Giá</th>
                        <th scope="col">Danh mục</th>
                        <th scope="col">Công ty</th>
                        <th scope="col">Hạn dùng</th>
                        <th scope="col">Đơn vị</th>
                        <th scope="col">Giảm giá</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col" class="text-end">Thao tác</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($all_product as $pro)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ product_image_url($pro->product_image ?? null) }}" class="admin-product-img" alt="{{ $pro->product_name }}">
                                    <div>
                                        <div class="fw-semibold">{{ $pro->product_name }}</div>
                                        <div class="small text-secondary">
                                            NSX: {{ $pro->product_date ? \Carbon\Carbon::parse($pro->product_date)->format('d/m/Y') : 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="fw-semibold">{{ number_format($pro->product_price, 0, ',', '.') }} đ</td>
                            <td>{{ $pro->category_name }}</td>
                            <td>{{ $pro->product_company }}</td>
                            <td>{{ $pro->expiration_date ? \Carbon\Carbon::parse($pro->expiration_date)->format('d/m/Y') : 'N/A' }}</td>
                            <td>{{ $pro->product_unit }}</td>
                            <td>
                                <span class="badge {{ $pro->discount_percentage > 0 ? 'text-bg-danger' : 'text-bg-secondary' }}">
                                    {{ $pro->discount_percentage }}%
                                </span>
                            </td>
                            <td>
                                @if($pro->product_status == 0)
                                    <a href="{{ URL::to('/unactive-product/'.$pro->product_id) }}" class="badge text-bg-secondary text-decoration-none">Ẩn</a>
                                @else
                                    <a href="{{ URL::to('/active-product/'.$pro->product_id) }}" class="badge text-bg-success text-decoration-none">Hiển thị</a>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group" aria-label="Thao tác sản phẩm">
                                    <a href="{{ URL::to('/edit-product/'.$pro->product_id) }}" class="btn btn-outline-secondary">Sửa</a>
                                    <a onclick="return confirm('Bạn có chắc là muốn xóa sản phẩm này không?')"
                                       href="{{ URL::to('/delete-product/'.$pro->product_id) }}"
                                       class="btn btn-outline-danger">Xóa</a>
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
                    Hiển thị {{ $all_product->firstItem() }} - {{ $all_product->lastItem() }} / {{ $all_product->total() }} sản phẩm
                </small>
                {{ $all_product->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </section>
</div>
@endsection
