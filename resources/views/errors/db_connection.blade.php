@extends('welcome')

@section('title', 'Fresh | Lỗi kết nối cơ sở dữ liệu')

@section('content')
<div class="alert alert-danger">
    <h1 class="h4">Không kết nối được cơ sở dữ liệu</h1>
    <p class="mb-2">Website đang không lấy được dữ liệu từ PostgreSQL trên Render.</p>
    @isset($error)
        <pre class="mb-0 small text-wrap">{{ $error }}</pre>
    @endisset
</div>
@endsection
