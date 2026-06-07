@extends('welcome')

@section('title', 'Fresh | Loi ket noi co so du lieu')

@section('content')
<div class="alert alert-danger">
    <h1 class="h4">Khong ket noi duoc co so du lieu</h1>
    <p class="mb-2">Website dang khong lay duoc du lieu tu PostgreSQL tren Render.</p>
    @isset($error)
        <pre class="mb-0 small text-wrap">{{ $error }}</pre>
    @endisset
</div>
@endsection
