@extends('admin_layout')

@section('admin_title', 'Admin | Cập nhật danh mục')
@section('page_heading', 'Cập nhật danh mục')

@section('admin_content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-8">
        @foreach($edit_category_product as $edit_value)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h1 class="h4 mb-1">Cập nhật danh mục sản phẩm</h1>
                    <p class="text-secondary mb-0">Điều chỉnh thông tin danh mục hiện có.</p>
                </div>
                <div class="card-body p-4">
                    @if(Session::has('message'))
                        <div class="alert alert-info">
                            {{ Session::get('message') }}
                            {{ Session::forget('message') }}
                        </div>
                    @endif

                    <form action="{{ URL::to('/update-category-product/'.$edit_value->category_id) }}" method="POST" class="row g-3">
                        @csrf
                        <div class="col-12">
                            <label for="category_product_name" class="form-label">Tên danh mục</label>
                            <input type="text" value="{{ $edit_value->category_name }}" name="category_product_name"
                                   class="form-control" id="category_product_name" placeholder="Tên danh mục">
                        </div>

                        <div class="col-12">
                            <label for="category_product_desc" class="form-label">Mô tả danh mục</label>
                            <textarea rows="5" class="form-control" name="category_product_desc" id="category_product_desc">{{ $edit_value->category_desc }}</textarea>
                        </div>

                        <div class="col-12 d-flex flex-column flex-sm-row justify-content-end gap-2 pt-2">
                            <a href="{{ URL::to('/all-category-product') }}" class="btn btn-outline-secondary">Hủy</a>
                            <button type="submit" name="update_category_product" class="btn btn-success">Cập nhật danh mục</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
