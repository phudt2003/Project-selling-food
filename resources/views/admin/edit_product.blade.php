@extends('admin_layout')

@section('admin_title', 'Admin | Cap nhat san pham')
@section('page_heading', 'Cap nhat san pham')

@section('admin_content')
@foreach($edit_product as $key => $pro)
<div class="row justify-content-center">
    <div class="col-12 col-xxl-10">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex flex-column flex-lg-row justify-content-between gap-3">
                <div>
                    <h1 class="h4 mb-1">Cap nhat san pham</h1>
                    <p class="text-secondary mb-0">Dieu chinh thong tin hien thi tren cua hang ma khong thay doi luong xu ly san pham.</p>
                </div>
                <a href="{{ URL::to('/all-product') }}" class="btn btn-outline-secondary align-self-start align-self-lg-center">
                    Quay lai danh sach
                </a>
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
                        <div class="fw-semibold mb-1">Vui long kiem tra lai thong tin:</div>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ URL::to('update-product/' . $pro->product_id) }}" method="post" enctype="multipart/form-data" class="d-grid gap-4">
                    @csrf

                    <section>
                        <h2 class="h5 mb-3">Thong tin co ban</h2>
                        <div class="row g-3">
                            <div class="col-12 col-lg-6">
                                <label for="product_name" class="form-label">Ten san pham</label>
                                <input id="product_name" type="text" name="product_name" class="form-control @error('product_name') is-invalid @enderror"
                                       value="{{ old('product_name', $pro->product_name) }}">
                                @error('product_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12 col-sm-6 col-lg-3">
                                <label for="product_price" class="form-label">Gia san pham</label>
                                <input id="product_price" type="text" name="product_price" class="form-control @error('product_price') is-invalid @enderror"
                                       value="{{ old('product_price', $pro->product_price) }}">
                                @error('product_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12 col-sm-6 col-lg-3">
                                <label for="product_unit" class="form-label">Don vi</label>
                                <input id="product_unit" type="text" name="product_unit" class="form-control @error('product_unit') is-invalid @enderror"
                                       value="{{ old('product_unit', $pro->product_unit) }}" placeholder="kg, goi, chiec">
                                @error('product_unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="discount_percentage" class="form-label">Phan tram giam gia</label>
                                <div class="input-group">
                                    <input id="discount_percentage" type="number" name="discount_percentage" class="form-control @error('discount_percentage') is-invalid @enderror"
                                           min="0" max="100" value="{{ old('discount_percentage', $pro->discount_percentage) }}">
                                    <span class="input-group-text">%</span>
                                    @error('discount_percentage')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="input_gram" class="form-label">Tinh nhanh gia theo gram</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="input_gram" placeholder="Nhap so gram">
                                    <input type="text" id="calculated_price" class="form-control bg-light" readonly placeholder="Gia tu dong">
                                </div>
                            </div>
                        </div>
                    </section>

                    <section>
                        <h2 class="h5 mb-3">Hinh anh</h2>
                        <div class="row g-3 align-items-center">
                            <div class="col-12 col-md-4 col-xl-3">
                                <img src="{{ asset('uploads/product/' . $pro->product_image) }}"
                                     class="img-fluid rounded border bg-light"
                                     alt="{{ $pro->product_name }}">
                            </div>
                            <div class="col-12 col-md-8 col-xl-9">
                                <label for="product_image" class="form-label">Thay doi hinh anh san pham</label>
                                <input id="product_image" type="file" name="product_image" class="form-control @error('product_image') is-invalid @enderror">
                                <div class="form-text">Bo trong neu muon giu hinh anh hien tai.</div>
                                @error('product_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </section>

                    <section>
                        <h2 class="h5 mb-3">Noi dung san pham</h2>
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="product_desc" class="form-label">Mo ta san pham</label>
                                <textarea id="product_desc" rows="5" class="form-control @error('product_desc') is-invalid @enderror"
                                          name="product_desc">{{ old('product_desc', $pro->product_desc) }}</textarea>
                                @error('product_desc')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label for="product_content" class="form-label">Noi dung san pham</label>
                                <textarea id="product_content" rows="5" class="form-control @error('product_content') is-invalid @enderror"
                                          name="product_content">{{ old('product_content', $pro->product_content) }}</textarea>
                                @error('product_content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </section>

                    <section>
                        <h2 class="h5 mb-3">Phan loai va trang thai</h2>
                        <div class="row g-3">
                            <div class="col-12 col-lg-6">
                                <label for="product_company" class="form-label">Cong ty san xuat</label>
                                <input id="product_company" type="text" name="product_company" class="form-control @error('product_company') is-invalid @enderror"
                                       value="{{ old('product_company', $pro->product_company) }}">
                                @error('product_company')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12 col-sm-6 col-lg-3">
                                <label for="product_date" class="form-label">Ngay san xuat</label>
                                <input id="product_date" type="date" name="product_date" class="form-control @error('product_date') is-invalid @enderror"
                                       value="{{ old('product_date', $pro->product_date) }}">
                                @error('product_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12 col-sm-6 col-lg-3">
                                <label for="expiration_date" class="form-label">Ngay het han</label>
                                <input id="expiration_date" type="date" name="expiration_date" class="form-control @error('expiration_date') is-invalid @enderror"
                                       value="{{ old('expiration_date', $pro->expiration_date) }}">
                                @error('expiration_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="product_cate" class="form-label">Danh muc san pham</label>
                                <select id="product_cate" name="product_cate" class="form-select @error('product_cate') is-invalid @enderror">
                                    @foreach($cate_product as $cate)
                                        <option value="{{ $cate->category_id }}" {{ old('product_cate', $pro->category_id) == $cate->category_id ? 'selected' : '' }}>
                                            {{ $cate->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_cate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="product_status" class="form-label">Trang thai hien thi</label>
                                <select id="product_status" name="product_status" class="form-select @error('product_status') is-invalid @enderror">
                                    <option value="0" {{ old('product_status', $pro->product_status) == 0 ? 'selected' : '' }}>An</option>
                                    <option value="1" {{ old('product_status', $pro->product_status) == 1 ? 'selected' : '' }}>Hien thi</option>
                                </select>
                                @error('product_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </section>

                    <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                        <a href="{{ URL::to('/all-product') }}" class="btn btn-outline-secondary">Huy</a>
                        <button type="submit" name="add_product" class="btn btn-primary">Cap nhat san pham</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

@push('admin_scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const gramInput = document.getElementById('input_gram');
    const productPriceInput = document.getElementById('product_price');
    const calculatedPrice = document.getElementById('calculated_price');

    if (!gramInput || !productPriceInput || !calculatedPrice) {
        return;
    }

    function formatNumber(number) {
        return new Intl.NumberFormat('vi-VN').format(number) + ' VND';
    }

    function calculatePrice() {
        const gram = parseFloat(gramInput.value);
        const pricePerKg = parseFloat(productPriceInput.value);

        calculatedPrice.value = !Number.isNaN(gram) && !Number.isNaN(pricePerKg)
            ? formatNumber((gram / 1000) * pricePerKg)
            : '';
    }

    gramInput.addEventListener('input', calculatePrice);
    productPriceInput.addEventListener('input', calculatePrice);
});
</script>
@endpush
