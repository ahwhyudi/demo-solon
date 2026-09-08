@extends('layouts.admin')

@section('title')
    Dashboard Analisa
@endsection

@push('addStyle')
    <style>
        .dashboard-gradient {
            background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 45%, #38bdf8 100%);
            color: #fff;
        }

        .dashboard-metric {
            min-height: 100%;
        }

        .dashboard-metric .display-6 {
            font-weight: 700;
        }

        .dashboard-chart {
            min-height: 360px;
        }

        .dashboard-mini-list .list-group-item {
            padding-left: 0;
            padding-right: 0;
            border-left: 0;
            border-right: 0;
        }

        .dashboard-metric:hover {
            background-color: #d3d3d3 !important;
        }
    </style>
@endpush

@section('content')
    {{-- HEADER & FILTER --}}
    <div class="card dashboard-gradient border-0 mb-4 shadow-sm rounded-3">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between gap-4">
                <div>
                    <div class="text-uppercase opacity-75 small mb-2 fw-semibold">Analisa Operasional</div>
                    <h2 class="mb-2 text-white fw-bold">Laporan Berkas, HRIS, dan Performa</h2>
                    <div class="opacity-75">
                        Menampilkan data berdasarkan filter periode yang dipilih
                    </div>
                </div>
                <form action="" method="GET" class="row g-2 align-items-end">
                    {{-- Filter Bulan --}}
                    <div class="col-12 col-md-auto">
                        <label class="form-label text-white opacity-75 small">Bulan Analisa</label>
                        <input type="month" name="filter_month" class="form-control shadow-sm"
                            value="{{ request('filter_month', date('Y-m')) }}">
                    </div>
                    {{-- Filter Satuan Grafik --}}
                    <div class="col-12 col-md-auto">
                        <label class="form-label text-white opacity-75 small">Satuan Grafik</label>
                        <select class="form-select shadow-sm" name="group_by">
                            <option value="day" {{ request('group_by', $groupBy ?? 'day') === 'day' ? 'selected' : '' }}>
                                Hari</option>
                            <option value="week"
                                {{ request('group_by', $groupBy ?? 'day') === 'week' ? 'selected' : '' }}>Minggu</option>
                            <option value="month"
                                {{ request('group_by', $groupBy ?? 'day') === 'month' ? 'selected' : '' }}>Bulan</option>
                        </select>
                    </div>
                    {{-- Filter Grafik Penyelesaian --}}
                    <div class="col-12 col-md-auto">
                        <label class="form-label text-white opacity-75 small">Grafik Penyelesaian</label>
                        <select class="form-select shadow-sm" name="completion_group_by">
                            <option value="perusahaan"
                                {{ request('completion_group_by', 'perusahaan') === 'perusahaan' ? 'selected' : '' }}>
                                Perusahaan</option>
                            <option value="pegawai"
                                {{ request('completion_group_by', 'perusahaan') === 'pegawai' ? 'selected' : '' }}>
                                Pegawai</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-auto">
                        <button class="btn btn-light text-primary fw-semibold shadow-sm w-100">
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- HIGHLIGHT 5 METRIK BERKAS --}}
    <div class="row row-cols-2 row-cols-md-3 row-cols-xl-5 g-3 mb-4">
        <div class="col">
            <div class="card dashboard-metric border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <div class="text-secondary text-uppercase fw-semibold small mb-2">Berkas Masuk</div>
                    <div class="display-6 text-dark">{{ number_format($summary['berkas_masuk'] ?? 0) }}</div>
                    {{-- {{ dd($summary) }} --}}
                </div>
            </div>
        </div>
        <div class="col">
            <a href="{{ route('job.divisi.index') }}" class="text-decoration-none">
                <div class="card dashboard-metric border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <div class="text-secondary text-uppercase fw-semibold small mb-2">
                            Berkas Berjalan
                        </div>
                        <div class="display-6 text-primary">
                            {{ number_format($summary['berkas_berjalan'] ?? 0) }}
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('laporan.list-job-divisi-history') }}" class="text-decoration-none">
                <div class="card dashboard-metric border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <div class="text-secondary text-uppercase fw-semibold small mb-2">Berkas Selesai</div>
                        <div class="display-6 text-success">{{ number_format($summary['berkas_selesai'] ?? 0) }}</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('berkas-bermasalah.pending.index') }}" class="text-decoration-none">
                <div class="card dashboard-metric border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <div class="text-secondary text-uppercase fw-semibold small mb-2">Berkas Pending</div>
                        <div class="display-6 text-warning">{{ number_format($summary['berkas_pending'] ?? 0) }}</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('berkas-bermasalah.freeze.index') }}" class="text-decoration-none">
                <div class="card dashboard-metric border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <div class="text-secondary text-uppercase fw-semibold small mb-2">Berkas Freeze</div>
                        <div class="display-6 text-danger">{{ number_format($summary['berkas_freeze'] ?? 0) }}</div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="my-4 card border-0 shadow-sm rounded-3">
        <x-dashboard.limit-view-dashboard />
    </div>

    {{-- ROW 1: GRAFIK STATUS & HRIS --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white border-bottom">
                    <div>
                        <h3 class="card-title fw-bold m-0">Grafik Status Berkas</h3>
                        <div class="text-muted small">Pergerakan selesai, berjalan, pending, dan freeze</div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chart-completion-pending" class="dashboard-chart"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white border-bottom">
                    <div>
                        <h3 class="card-title fw-bold m-0">Ringkasan HRIS</h3>
                        <div class="text-muted small">Permintaan cuti dan lembur</div>
                    </div>
                </div>
                <div class="card-body d-flex flex-column justify-content-center">
                    <div id="chart-hris-total" style="min-height: 200px"></div>
                    <div id="chart-hris-status" class="mt-3" style="min-height: 150px"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ROW 2: MENU & RANKING PENYELESAIAN --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white border-bottom">
                    <div>
                        <h3 class="card-title fw-bold m-0">Status Berkas Per Menu</h3>
                        <div class="text-muted small">Notaris, PPAT, Operasional, Pajak, dan PNBP</div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chart-menu-completion" class="dashboard-chart"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white border-bottom">
                    <div>
                        <h3 class="card-title fw-bold m-0">
                            Penyelesaian
                            {{ request('completion_group_by', 'perusahaan') === 'perusahaan' ? 'Perusahaan' : 'Pegawai' }}
                        </h3>
                        <div class="text-muted small">Top 10 berdasarkan filter saat ini</div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chart-completion-ranking" class="dashboard-chart"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ROW 3: KOMPOSISI PAKET --}}
    <div class="row g-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white border-bottom">
                    <h3 class="card-title fw-bold m-0">Komposisi Paket Pekerjaan</h3>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush dashboard-mini-list">
                        @forelse ($formOrderGroup as $kategori => $item)
                            <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div class="fw-semibold text-uppercase text-dark">{{ str_replace('_', ' ', $kategori) }}
                                </div>
                                <span
                                    class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3 py-2">
                                    {{ $item->count() }}
                                </span>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">Belum ada data pekerjaan pada periode ini.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('addScript')
    <script src="{{ asset('tabler-admin/demo/dist/libs/apexcharts/dist/apexcharts.min.js') }}"></script>

    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            const trendChart = @json($trendChart);
            const hrisChart = @json($hrisChart);
            const completionChart = @json($completionChart);
            const menuCompletionChart = @json($menuCompletionChart);

            new ApexCharts(document.getElementById("chart-hris-total"), {
                chart: {
                    type: 'donut',
                    height: 200
                },
                labels: hrisChart.categories,
                series: hrisChart.totals,
                colors: ['#0ea5e9', '#f97316'],
                legend: {
                    position: 'bottom'
                }
            }).render();

            new ApexCharts(document.getElementById("chart-hris-status"), {
                chart: {
                    type: 'bar',
                    height: 150,
                    toolbar: { show: false }
                },
                series: [{
                    name: 'Jumlah',
                    data: hrisChart.status
                }],
                plotOptions: {
                    bar: {
                        borderRadius: 6,
                        distributed: true
                    }
                },
                colors: ['#f59e0b', '#22c55e', '#ef4444'],
                xaxis: {
                    categories: ['Pending', 'Approved', 'Rejected']
                },
                legend: { show: false }
            }).render();

            new ApexCharts(document.getElementById("chart-completion-pending"), {
                chart: {
                    type: 'bar',
                    stacked: true,
                    height: 360,
                    toolbar: { show: false }
                },
                series: [
                    { name: 'Selesai', data: trendChart.selesai },
                    { name: 'Berjalan', data: trendChart.berjalan },
                    { name: 'Pending', data: trendChart.pending },
                    { name: 'Freeze', data: trendChart.freeze }
                ],
                colors: ['#16a34a', '#2563eb', '#f59e0b', '#dc2626'],
                xaxis: { categories: trendChart.categories },
                legend: { position: 'top' }
            }).render();

            new ApexCharts(document.getElementById("chart-completion-ranking"), {
                chart: {
                    type: 'bar',
                    height: 360,
                    toolbar: { show: false }
                },
                series: [
                    { name: 'Selesai', data: completionChart.selesai },
                    { name: 'Berjalan', data: completionChart.berjalan },
                    { name: 'Pending', data: completionChart.pending },
                    { name: 'Freeze', data: completionChart.freeze }
                ],
                colors: ['#16a34a', '#2563eb', '#f97316', '#dc2626'],
                plotOptions: {
                    bar: {
                        horizontal: true,
                        borderRadius: 4
                    }
                },
                xaxis: { categories: completionChart.labels },
                legend: { position: 'top' }
            }).render();

            new ApexCharts(document.getElementById("chart-menu-completion"), {
                chart: {
                    type: 'bar',
                    stacked: true,
                    height: 360,
                    toolbar: { show: false }
                },
                series: [
                    { name: 'Selesai', data: menuCompletionChart.selesai },
                    { name: 'Berjalan', data: menuCompletionChart.berjalan },
                    { name: 'Pending', data: menuCompletionChart.pending },
                    { name: 'Freeze', data: menuCompletionChart.freeze }
                ],
                colors: ['#16a34a', '#2563eb', '#f97316', '#dc2626'],
                xaxis: { categories: menuCompletionChart.labels },
                legend: { position: 'top' }
            }).render();
        });
    </script> --}}
@endpush
