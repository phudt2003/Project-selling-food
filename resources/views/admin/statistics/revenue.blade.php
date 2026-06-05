@extends('admin_layout')

@section('admin_title', 'Admin | Thong ke doanh thu')
@section('page_heading', 'Thong ke doanh thu')

@section('admin_content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex flex-column flex-lg-row justify-content-between gap-3">
        <div>
            <h1 class="h4 mb-1">Thong ke doanh thu theo thang</h1>
            <p class="text-secondary mb-0">Chon nam de xem xu huong doanh thu theo tung thang.</p>
        </div>
        <form method="POST" action="{{ route('statistics.revenue.filter') }}" class="align-self-start align-self-lg-center">
            @csrf
            <label for="year" class="form-label visually-hidden">Chon nam</label>
            <select id="year" name="year" class="form-select" onchange="this.form.submit()">
                @for ($y = now()->year; $y >= 2020; $y--)
                    <option value="{{ $y }}" {{ request('year', $year ?? now()->year) == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select>
        </form>
    </div>

    <div class="card-body p-4">
        <div class="position-relative" style="min-height: 360px;">
            <canvas id="revenueChart" aria-label="Bieu do doanh thu theo thang" role="img"></canvas>
        </div>
    </div>
</div>
@endsection

@push('admin_scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const revenueLabels = @json(array_keys($data));
const revenueValues = @json(array_values($data));

new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: revenueLabels.map(function (month) {
            return 'Thang ' + month;
        }),
        datasets: [{
            label: 'Doanh thu (VND)',
            data: revenueValues,
            borderColor: '#198754',
            backgroundColor: 'rgba(25, 135, 84, 0.16)',
            fill: true,
            tension: 0.35,
            pointRadius: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            intersect: false,
            mode: 'index'
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return new Intl.NumberFormat('vi-VN').format(value);
                    }
                }
            }
        }
    }
});
</script>
@endpush
