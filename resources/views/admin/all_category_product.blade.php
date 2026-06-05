@extends('admin_layout')

@section('admin_title', 'Admin | Danh mục')
@section('page_heading', 'Danh mục sản phẩm')

@section('admin_content')
<div class="d-grid gap-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <p class="text-secondary mb-1">Tổ chức nhóm sản phẩm</p>
            <h1 class="h3 fw-bold mb-0">Danh sách danh mục</h1>
        </div>
        <a href="{{ URL::to('/add-category-product') }}" class="btn btn-success">Thêm danh mục</a>
    </div>

    @if(Session::has('message'))
        <div class="alert alert-info mb-0">
            {{ Session::get('message') }}
            {{ Session::forget('message') }}
        </div>
    @endif

    <section class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th scope="col">Tên danh mục</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col" class="text-end">Thao tác</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($all_category_product as $cate_pro)
                        <tr>
                            <td class="fw-semibold">{{ $cate_pro->category_name }}</td>
                            <td>
                                @if($cate_pro->category_status == 0)
                                    <a href="{{ URL::to('/unactive-category-product/'.$cate_pro->category_id) }}" class="badge text-bg-secondary text-decoration-none">Ẩn</a>
                                @else
                                    <a href="{{ URL::to('/active-category-product/'.$cate_pro->category_id) }}" class="badge text-bg-success text-decoration-none">Hiển thị</a>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group" aria-label="Thao tác danh mục">
                                    <a href="{{ URL::to('/edit-category-product/'.$cate_pro->category_id) }}" class="btn btn-outline-secondary">Sửa</a>
                                    <a onclick="return confirm('Bạn có muốn xóa danh mục này không?')"
                                       href="{{ URL::to('/delete-category-product/'.$cate_pro->category_id) }}"
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
                    Hiển thị {{ $all_category_product->firstItem() }} đến {{ $all_category_product->lastItem() }} trong tổng số {{ $all_category_product->total() }} danh mục
                </small>
                {{ $all_category_product->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </section>
</div>
@endsection
