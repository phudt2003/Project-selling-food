@extends('admin_layout')

@section('admin_title', 'Admin | Thêm danh mục')
@section('page_heading', 'Thêm danh mục')

@section('admin_content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h1 class="h4 mb-1">Thêm danh mục sản phẩm</h1>
                <p class="text-secondary mb-0">Tạo danh mục để tổ chức sản phẩm rõ ràng hơn.</p>
            </div>
            <div class="card-body p-4">
                @if(Session::has('message'))
                    <div class="alert alert-info">
                        {{ Session::get('message') }}
                        {{ Session::forget('message') }}
                    </div>
                @endif

                <form action="{{ URL::to('/save-category-product') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-12">
                        <label for="category_product_name" class="form-label">Tên danh mục</label>
                        <input type="text" name="category_product_name" id="category_product_name"
                               class="form-control @error('category_product_name') is-invalid @enderror"
                               placeholder="VD: Rau củ" value="{{ old('category_product_name') }}">
                        @error('category_product_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="category_product_desc" class="form-label">Mô tả danh mục</label>
                        <textarea rows="5" class="form-control" name="category_product_desc" id="category_product_desc"
                                  placeholder="Mô tả ngắn về danh mục">{{ old('category_product_desc') }}</textarea>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="parent_id_select" class="form-label">Thuộc danh mục cha</label>
                        <select id="parent_id_select" name="parent_id" class="form-select">
                            <option value="0">Là danh mục cha</option>
                            @foreach($category as $cate)
                                <option value="{{ $cate->category_id }}" {{ old('parent_id') == $cate->category_id ? 'selected' : '' }}>
                                    {{ $cate->category_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="category_product_status" class="form-label">Trạng thái hiển thị</label>
                        <select id="category_product_status" name="category_product_status" class="form-select">
                            <option value="0" {{ old('category_product_status') == '0' ? 'selected' : '' }}>Ẩn</option>
                            <option value="1" {{ old('category_product_status') == '1' ? 'selected' : '' }}>Hiển thị</option>
                        </select>
                    </div>

                    <div class="col-12 d-flex flex-column flex-sm-row justify-content-end gap-2 pt-2">
                        <a href="{{ URL::to('/all-category-product') }}" class="btn btn-outline-secondary">Hủy</a>
                        <button type="submit" name="add_category_product" class="btn btn-success">Thêm danh mục</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
