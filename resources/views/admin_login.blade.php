<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dang nhap Admin</title>
    <link rel="stylesheet" href="{{ asset('frontend/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/font-awesome.css') }}">
    <style>
        body {
            min-height: 100vh;
            background: #f5f7fb;
        }

        .admin-login-shell {
            min-height: 100vh;
        }

        .admin-login-card {
            max-width: 440px;
            border-radius: 1rem;
        }
    </style>
</head>
<body>
    <main class="admin-login-shell d-flex align-items-center py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-10 col-md-7 col-lg-5">
                    <div class="card admin-login-card border-0 shadow-sm mx-auto">
                        <div class="card-body p-4 p-md-5">
                            <div class="text-center mb-4">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success-subtle text-success mb-3" style="width: 56px; height: 56px;">
                                    <i class="fa fa-lock" aria-hidden="true"></i>
                                </div>
                                <h1 class="h3 mb-2">Dang nhap Admin</h1>
                                <p class="text-secondary mb-0">Quan ly san pham, don hang va thanh toan cua cua hang.</p>
                            </div>

                            @if(Session::has('message'))
                                <div class="alert alert-info">
                                    {{ Session::get('message') }}
                                    {{ Session::forget('message') }}
                                </div>
                            @endif

                            <form action="{{ URL::to('/admin-dashboard') }}" method="post" class="d-grid gap-3">
                                @csrf

                                <div>
                                    <label for="admin_email" class="form-label">Email</label>
                                    <input id="admin_email" type="email" class="form-control form-control-lg" name="admin_email"
                                           placeholder="admin@example.com" required autocomplete="email">
                                </div>

                                <div>
                                    <label for="admin_password" class="form-label">Mat khau</label>
                                    <input id="admin_password" type="password" class="form-control form-control-lg" name="admin_password"
                                           placeholder="Nhap mat khau" required autocomplete="current-password">
                                </div>

                                <div class="d-flex flex-column flex-sm-row justify-content-between gap-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="remember_admin">
                                        <label class="form-check-label" for="remember_admin">Nho dang nhap</label>
                                    </div>
                                    <a href="#" class="link-secondary text-decoration-none">Quen mat khau?</a>
                                </div>

                                <button type="submit" name="login" class="btn btn-success btn-lg w-100">
                                    Dang nhap
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('frontend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
