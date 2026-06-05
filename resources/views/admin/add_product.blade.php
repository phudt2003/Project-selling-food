@extends('admin_layout')

@section('admin_title', 'Admin | Thêm sản phẩm')
@section('page_heading', 'Thêm sản phẩm')

@section('admin_content')
<div class="row justify-content-center">
    <div class="col-12 col-xxl-10">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h1 class="h4 mb-1">Thêm sản phẩm</h1>
                <p class="text-secondary mb-0">Nhập đầy đủ thông tin để sản phẩm hiển thị chính xác trên cửa hàng.</p>
            </div>

            <div class="card-body p-4">
                @if(Session::has('message'))
                    <div class="alert alert-info">
                        {{ Session::get('message') }}
                        {{ Session::forget('message') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <div class="fw-semibold mb-1">Vui lòng kiểm tra lại thông tin:</div>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ URL::to('/save-product') }}" method="POST" enctype="multipart/form-data" class="d-grid gap-4">
                    @csrf

                    <section>
                        <h2 class="h5 mb-3">Thông tin cơ bản</h2>
                        <div class="row g-3">
                            <div class="col-12 col-lg-6">
                                <label for="product_name" class="form-label">Tên sản phẩm</label>
                                <input id="product_name" type="text" name="product_name"
                                       class="form-control @error('product_name') is-invalid @enderror"
                                       value="{{ old('product_name') }}" placeholder="Tên sản phẩm">
                                @error('product_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12 col-sm-6 col-lg-3">
                                <label for="product_price" class="form-label">Giá sản phẩm</label>
                                <input id="product_price" type="text" name="product_price"
                                       class="form-control @error('product_price') is-invalid @enderror"
                                       value="{{ old('product_price') }}" placeholder="VD: 50000">
                                @error('product_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12 col-sm-6 col-lg-3">
                                <label for="product_unit" class="form-label">Đơn vị</label>
                                <input id="product_unit" type="text" name="product_unit"
                                       class="form-control @error('product_unit') is-invalid @enderror"
                                       value="{{ old('product_unit') }}" placeholder="VD: kg, gói">
                                @error('product_unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12 col-sm-6">
                                <label for="discount_percentage" class="form-label">Phần trăm giảm giá</label>
                                <input id="discount_percentage" type="number" name="discount_percentage" class="form-control"
                                       min="0" max="100" value="{{ old('discount_percentage', 0) }}">
                            </div>

                            <div class="col-12 col-sm-6">
                                <label for="product_image" class="form-label">Hình ảnh sản phẩm</label>
                                <input id="product_image" type="file" name="product_image"
                                       class="form-control @error('product_image') is-invalid @enderror">
                                @error('product_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </section>

                    <section>
                        <h2 class="h5 mb-3">Nội dung sản phẩm</h2>
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="product_desc" class="form-label">Mô tả sản phẩm</label>
                                <textarea id="product_desc" rows="5" class="form-control @error('product_desc') is-invalid @enderror"
                                          name="product_desc" placeholder="Mô tả sản phẩm">{{ old('product_desc') }}</textarea>
                                @error('product_desc')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label for="product_content" class="form-label">Nội dung sản phẩm</label>
                                <textarea id="product_content" rows="5" class="form-control @error('product_content') is-invalid @enderror"
                                          name="product_content" placeholder="Nội dung sản phẩm">{{ old('product_content') }}</textarea>
                                @error('product_content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </section>

                    <section>
                        <h2 class="h5 mb-3">Phân loại & trạng thái</h2>
                        <div class="row g-3">
                            <div class="col-12 col-lg-6">
                                <label for="product_company" class="form-label">Công ty sản xuất</label>
                                <input id="product_company" type="text" name="product_company"
                                       class="form-control @error('product_company') is-invalid @enderror"
                                       value="{{ old('product_company') }}" placeholder="Tên công ty sản xuất">
                                @error('product_company')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12 col-sm-6 col-lg-3">
                                <label for="product_date" class="form-label">Ngày sản xuất</label>
                                <input id="product_date" type="date" name="product_date"
                                       class="form-control @error('product_date') is-invalid @enderror"
                                       value="{{ old('product_date') }}">
                                @error('product_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12 col-sm-6 col-lg-3">
                                <label for="expiration_date" class="form-label">Ngày hết hạn</label>
                                <input id="expiration_date" type="date" name="expiration_date"
                                       class="form-control @error('expiration_date') is-invalid @enderror"
                                       value="{{ old('expiration_date') }}">
                                @error('expiration_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="product_cate" class="form-label">Danh mục sản phẩm</label>
                                <select id="product_cate" name="product_cate" class="form-select @error('product_cate') is-invalid @enderror">
                                    @foreach($cate_product as $cate)
                                        <option value="{{ $cate->category_id }}" {{ old('product_cate') == $cate->category_id ? 'selected' : '' }}>
                                            {{ $cate->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_cate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="product_status" class="form-label">Trạng thái hiển thị</label>
                                <select id="product_status" name="product_status" class="form-select @error('product_status') is-invalid @enderror">
                                    <option value="0" {{ old('product_status') == 0 ? 'selected' : '' }}>Ẩn</option>
                                    <option value="1" {{ old('product_status') == 1 ? 'selected' : '' }}>Hiển thị</option>
                                </select>
                                @error('product_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </section>

                    <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                        <a href="{{ URL::to('/all-product') }}" class="btn btn-outline-secondary">Hủy</a>
                        <button type="submit" name="add_product" class="btn btn-success">Thêm sản phẩm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
