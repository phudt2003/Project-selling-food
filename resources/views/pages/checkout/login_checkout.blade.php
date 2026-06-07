@extends('welcome')

@section('title', 'Fresh | Đăng nhập')

@section('content')
<div class="row g-4 align-items-start">
    <div class="col-12 col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 mb-3">Đăng nhập tài khoản</h1>

                @if(Session::has('error'))
                    <div class="alert alert-danger">{{ Session::get('error') }}</div>
                @endif

                <form action="{{ URL::to('/login-customer') }}" method="POST" class="d-grid gap-3">
                    @csrf
                    <div>
                        <label for="email-account" class="form-label">Email</label>
                        <input id="email-account" type="email" name="email_account"
                               class="form-control @error('email_account') is-invalid @enderror"
                               value="{{ old('email_account') }}" required autocomplete="email">
                        @error('email_account')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="password-account" class="form-label">Mật khẩu</label>
                        <input id="password-account" type="password" name="password_account"
                               class="form-control @error('password_account') is-invalid @enderror"
                               required autocomplete="current-password">
                        @error('password_account')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check">
                        <input id="remember-login" type="checkbox" class="form-check-input" name="remember" value="1">
                        <label for="remember-login" class="form-check-label">Ghi nhớ đăng nhập</label>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg">Đăng nhập</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h2 class="h4 mb-3">Đăng ký tài khoản</h2>

                <form action="{{ URL::to('/add-customer') }}" method="POST" class="row g-3">
                    @csrf

                    <div class="col-12">
                        <label for="customer-name" class="form-label">Họ và tên</label>
                        <input id="customer-name" type="text" name="customer_name"
                               class="form-control @error('customer_name') is-invalid @enderror"
                               value="{{ old('customer_name') }}" required autocomplete="name">
                        @error('customer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="customer-email" class="form-label">Email</label>
                        <input id="customer-email" type="email" name="customer_email"
                               class="form-control @error('customer_email') is-invalid @enderror"
                               value="{{ old('customer_email') }}" required autocomplete="email">
                        @error('customer_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="customer-phone" class="form-label">Số điện thoại</label>
                        <input id="customer-phone" type="tel" name="customer_phone"
                               class="form-control @error('customer_phone') is-invalid @enderror"
                               value="{{ old('customer_phone') }}" required autocomplete="tel">
                        @error('customer_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="customer-password" class="form-label">Mật khẩu</label>
                        <input id="customer-password" type="password" name="customer_password"
                               class="form-control @error('customer_password') is-invalid @enderror"
                               required autocomplete="new-password">
                        @error('customer_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-success btn-lg">Đăng ký</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
