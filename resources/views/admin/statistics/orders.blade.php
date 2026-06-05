@extends('admin_layout')

@section('admin_title', 'Admin | Thong ke don hang')
@section('page_heading', 'Thong ke don hang')

@section('admin_content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex flex-column flex-lg-row justify-content-between gap-3">
        <div>
            <h1 class="h4 mb-1">Thong ke tinh trang thanh toan</h1>
            <p class="text-secondary mb-0">Loc theo khoang ngay de xem ti le COD va thanh toan online.</p>
        </div>
        <a href="{{ route('statistics.orders') }}" class="btn btn-outline-secondary align-self-start align-self-lg-center">Reset</a>
    </div>

    <div class="card-body p-4">
        <form method="POST" action="{{ route('statistics.orders.filter') }}" class="mb-4">
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

        @if (!empty($data))
            <div class="mx-auto" style="max-width: 520px;">
                <canvas id="ordersChart" aria-label="Bieu do tinh trang thanh toan don hang" role="img"></canvas>
            </div>
        @else
            <div class="alert alert-info text-center mb-0">Khong co du lieu de hien thi.</div>
        @endif
    </div>
</div>
@endsection

@push('admin_scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const rawData = @json($data);
const paymentStatusMap = {
    cod: { label: 'Thanh toan khi nhan hang (COD)', color: '#198754' },
    paid: { label: 'Da thanh toan online', color: '#ffc107' }
};
const chartLabels = [];
const chartData = [];
const chartColors = [];

Object.keys(rawData || {}).forEach(function (key) {
    if (paymentStatusMap[key]) {
        chartLabels.push(paymentStatusMap[key].label);
        chartData.push(rawData[key]);
        chartColors.push(paymentStatusMap[key].color);
    }
});

if (chartData.length > 0) {
    new Chart(document.getElementById('ordersChart'), {
        type: 'pie',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'So luong don hang',
                data: chartData,
                backgroundColor: chartColors,
                borderColor: '#ffffff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.parsed + ' don hang';
                        }
                    }
                }
            }
        }
    });
}
</script>
@endpush
