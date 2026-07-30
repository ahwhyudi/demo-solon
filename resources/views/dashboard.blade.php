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
    </style>
@endpush

@section('content')
    <div class="card dashboard-gradient border-0 mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between gap-4">
                <div>
                    <div class="text-uppercase opacity-75 small mb-2">Analisa Operasional</div>
                    <h2 class="mb-2 text-white">Pendapatan, profit, HRIS, penyelesaian, dan pending dalam satu dashboard</h2>
                    <div class="opacity-75">
                        Periode {{ $groupBy === 'day' ? 'hari ini' : ($groupBy === 'week' ? 'minggu ini' : 'bulan ini') }}
                    </div>
                </div>
                <form action="" method="GET" class="row g-2 align-items-end">
                    <div class="col-12 col-md-6 col-xl-auto">
                        <label class="form-label text-white">Satuan Grafik</label>
                        <select class="form-select" name="group_by">
                            <option value="day" {{ $groupBy === 'day' ? 'selected' : '' }}>Hari</option>
                            <option value="week" {{ $groupBy === 'week' ? 'selected' : '' }}>Minggu</option>
                            <option value="month" {{ $groupBy === 'month' ? 'selected' : '' }}>Bulan</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6 col-xl-auto">
                        <label class="form-label text-white">Grafik Penyelesaian</label>
                        <select class="form-select" name="completion_group_by">
                            <option value="perusahaan" {{ $completionGroupBy === 'perusahaan' ? 'selected' : '' }}>
                                Perusahaan</option>
                            <option value="pegawai" {{ $completionGroupBy === 'pegawai' ? 'selected' : '' }}>Pegawai
                            </option>
                        </select>
                    </div>
                    <div class="col-12 col-xl-auto">
                        <button class="btn btn-light text-primary w-100">
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <div class="row g-3 mb-4">
        {{-- <div class="col-sm-6 col-xl-3">
            <div class="card dashboard-metric">
                <div class="card-body">
                    <div class="text-secondary text-uppercase small">Pendapatan</div>
                    <div class="display-6">Rp {{ number_format($summary['pendapatan'], 0, ',', '.') }}</div>
                    <div class="text-secondary mt-2">{{ $summary['pekerjaan'] }} item pekerjaan</div>
                </div>
            </div>
        </div> --}}
        {{-- <div class="col-sm-6 col-xl-3">
            <div class="card dashboard-metric">
                <div class="card-body">
                    <div class="text-secondary text-uppercase small">Profit</div>
                    <div class="display-6">Rp {{ number_format($summary['profit'], 0, ',', '.') }}</div>
                    <div class="text-secondary mt-2">Modal Rp {{ number_format($summary['modal'], 0, ',', '.') }}</div>
                </div>
            </div>
        </div> --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card dashboard-metric">
                <div class="card-body">
                    <div class="text-secondary text-uppercase small">Penyelesaian</div>
                    <div class="display-6">{{ number_format($summary['selesai']) }}</div>
                    <div class="text-secondary mt-2">{{ number_format($summary['belum_selesai']) }} belum selesai</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card dashboard-metric">
                <div class="card-body">
                    <div class="text-secondary text-uppercase small">Pending & Freeze</div>
                    <div class="display-6">{{ number_format($summary['pending']) }}</div>
                    <div class="text-secondary mt-2">{{ number_format($summary['freeze']) }} freeze aktif pada periode ini
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="my-4 card">
        <x-dashboard.limit-view-dashboard />
    </div>

    <div class="row g-3 mb-4">
        {{-- <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">Grafik Pendapatan dan Profit</h3>
                        <div class="text-secondary">Analisa mengikuti filter hari, minggu, atau bulan</div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chart-revenue-profit" class="dashboard-chart"></div>
                </div>
            </div>
        </div> --}}
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">Ringkasan HRIS</h3>
                        <div class="text-secondary">Permintaan cuti dan lembur</div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chart-hris-total" style="min-height: 230px"></div>
                    <div id="chart-hris-status" style="min-height: 180px"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">Grafik Penyelesaian vs Pending</h3>
                        <div class="text-secondary">Pergerakan selesai, belum selesai, pending, dan freeze</div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chart-completion-pending" class="dashboard-chart"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">Penyelesaian
                            {{ $completionGroupBy === 'perusahaan' ? 'Perusahaan' : 'Pegawai' }}</h3>
                        <div class="text-secondary">Top 10 berdasarkan filter saat ini</div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chart-completion-ranking" class="dashboard-chart"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">Penyelesaian Per Menu</h3>
                        <div class="text-secondary">Notaris, PPAT, Operasional, Pajak, dan PNBP</div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chart-menu-completion" class="dashboard-chart"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Komposisi Paket Pekerjaan</h3>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush dashboard-mini-list">
                        @forelse ($formOrderGroup as $kategori => $item)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="fw-semibold text-uppercase">{{ str_replace('_', ' ', $kategori) }}</div>
                                <span class="badge bg-primary-lt">{{ $item->count() }}</span>
                            </div>
                        @empty
                            <div class="text-secondary">Belum ada data pekerjaan pada periode ini.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Fokus Analisa</h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <div class="text-secondary small text-uppercase">Job Divisi</div>
                                <div class="fs-1 fw-bold">{{ number_format($summary['job_divisi']) }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <div class="text-secondary small text-uppercase">Request HRIS</div>
                                <div class="fs-1 fw-bold">{{ number_format($summary['hris']) }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <div class="text-secondary small text-uppercase">Pending</div>
                                <div class="fs-1 fw-bold text-warning">{{ number_format($summary['pending']) }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <div class="text-secondary small text-uppercase">Selesai</div>
                                <div class="fs-1 fw-bold text-success">{{ number_format($summary['selesai']) }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <div class="text-secondary small text-uppercase">Belum Selesai</div>
                                <div class="fs-1 fw-bold text-primary">{{ number_format($summary['belum_selesai']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <div class="text-secondary small text-uppercase">Freeze</div>
                                <div class="fs-1 fw-bold text-danger">{{ number_format($summary['freeze']) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('addScript')
    <script src="{{ asset('tabler-admin/demo/dist/libs/apexcharts/dist/apexcharts.min.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const trendChart = @json($trendChart);
            const hrisChart = @json($hrisChart);
            const completionChart = @json($completionChart);
            const menuCompletionChart = @json($menuCompletionChart);

            new ApexCharts(document.getElementById("chart-revenue-profit"), {
                chart: {
                    type: 'line',
                    height: 360,
                    toolbar: {
                        show: false
                    }
                },
                stroke: {
                    width: [4, 4],
                    curve: 'smooth'
                },
                series: [{
                        name: 'Pendapatan',
                        data: trendChart.pendapatan
                    },
                    {
                        name: 'Profit',
                        data: trendChart.profit
                    }
                ],
                colors: ['#2563eb', '#16a34a'],
                xaxis: {
                    categories: trendChart.categories
                },
                yaxis: {
                    labels: {
                        formatter: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value || 0);
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value || 0);
                        }
                    }
                },
                legend: {
                    position: 'top'
                }
            }).render();

            new ApexCharts(document.getElementById("chart-hris-total"), {
                chart: {
                    type: 'donut',
                    height: 230
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
                    height: 180,
                    toolbar: {
                        show: false
                    }
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
                legend: {
                    show: false
                }
            }).render();

            new ApexCharts(document.getElementById("chart-completion-pending"), {
                chart: {
                    type: 'bar',
                    stacked: true,
                    height: 360,
                    toolbar: {
                        show: false
                    }
                },
                series: [{
                        name: 'Selesai',
                        data: trendChart.selesai
                    },
                    {
                        name: 'Belum Selesai',
                        data: trendChart.belum_selesai
                    },
                    {
                        name: 'Pending',
                        data: trendChart.pending
                    },
                    {
                        name: 'Freeze',
                        data: trendChart.freeze
                    }
                ],
                colors: ['#22c55e', '#2563eb', '#f59e0b', '#ef4444'],
                xaxis: {
                    categories: trendChart.categories
                },
                legend: {
                    position: 'top'
                }
            }).render();

            new ApexCharts(document.getElementById("chart-completion-ranking"), {
                chart: {
                    type: 'bar',
                    height: 360,
                    toolbar: {
                        show: false
                    }
                },
                series: [{
                        name: 'Selesai',
                        data: completionChart.selesai
                    },
                    {
                        name: 'Belum Selesai',
                        data: completionChart.belum_selesai
                    },
                    {
                        name: 'Pending',
                        data: completionChart.pending
                    },
                    {
                        name: 'Freeze',
                        data: completionChart.freeze
                    }
                ],
                colors: ['#16a34a', '#2563eb', '#f97316', '#dc2626'],
                plotOptions: {
                    bar: {
                        horizontal: true,
                        borderRadius: 4
                    }
                },
                xaxis: {
                    categories: completionChart.labels
                },
                legend: {
                    position: 'top'
                }
            }).render();

            new ApexCharts(document.getElementById("chart-menu-completion"), {
                chart: {
                    type: 'bar',
                    stacked: true,
                    height: 360,
                    toolbar: {
                        show: false
                    }
                },
                series: [{
                        name: 'Selesai',
                        data: menuCompletionChart.selesai
                    },
                    {
                        name: 'Belum Selesai',
                        data: menuCompletionChart.belum_selesai
                    },
                    {
                        name: 'Pending',
                        data: menuCompletionChart.pending
                    },
                    {
                        name: 'Freeze',
                        data: menuCompletionChart.freeze
                    }
                ],
                colors: ['#16a34a', '#2563eb', '#f97316', '#dc2626'],
                xaxis: {
                    categories: menuCompletionChart.labels
                },
                legend: {
                    position: 'top'
                }
            }).render();
        });
    </script>
@endpush
