@extends('admin_layout')

@section('admin_title', 'Admin | Thong ke san pham')
@section('page_heading', 'Thong ke san pham')

@section('admin_content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <h1 class="h4 mb-1">Thong ke san pham ban chay</h1>
        <p class="text-secondary mb-0">Theo doi san pham co so luong ban cao trong khoang thoi gian chon.</p>
    </div>

    <div class="card-body p-4">
        <form method="POST" action="{{ route('statistics.products.filter') }}" class="mb-4">
            @csrf
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-5">
                    <label for="start_date" class="form-label">Tu ngay</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-12 col-md-5">
                    <label for="end_date" class="form-label">Den ngay</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-12 col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary">Loc</button>
                </div>
            </div>
        </form>

        <div class="position-relative" style="min-height: 320px;">
            <canvas id="productsChart" aria-label="Bieu do san pham ban chay" role="img"></canvas>
        </div>
    </div>
</div>
@endsection

@push('admin_scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const productLabels = @json($data->pluck('product_name'));
const productValues = @json($data->pluck('total_sold'));

new Chart(document.getElementById('productsChart'), {
    type: 'bar',
    data: {
        labels: productLabels,
        datasets: [{
            label: 'So luong ban',
            data: productValues,
            backgroundColor: '#0d6efd',
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            x: { ticks: { autoSkip: false, maxRotation: 45, minRotation: 0 } },
            y: { beginAtZero: true }
        }
    }
});
</script>
@endpush
