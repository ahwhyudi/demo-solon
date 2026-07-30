@extends('layouts.admin')

@section('title')
    Neraca Keuangan
@endsection

@section('content')
    @include('pages.Finance.reports._nav')

    <div class="container-fluid">

        {{-- Header --}}
        {{-- <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">

            <div>

                <h2 class="mb-1 fw-bold">
                    Neraca Keuangan
                </h2>

                <span class="text-secondary">

                    Periode

                    <strong>{{ $periodLabel }}</strong>

                </span>

            </div>

            <form method="GET">

                <input type="month" name="month" value="{{ $month }}" onchange="this.form.submit()"
                    class="form-control">

            </form>

        </div> --}}

        {{-- SUMMARY CARD --}}

        <div class="row g-3 mb-4">

            <div class="col-lg-3 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <small class="text-secondary">

                                    Kas & Bank

                                </small>

                                <h3 class="mt-2 text-primary fw-bold">

                                    Rp {{ number_format($summary['saldo_kas'], 0, ',', '.') }}

                                </h3>

                            </div>

                            <div class="fs-1 text-primary">

                                <i class="bi bi-bank2"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <small class="text-secondary">

                                    Piutang

                                </small>

                                <h3 class="mt-2 text-warning fw-bold">

                                    Rp {{ number_format($summary['piutang'], 0, ',', '.') }}

                                </h3>

                            </div>

                            <div class="fs-1 text-warning">

                                <i class="bi bi-wallet2"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <small class="text-secondary">

                                    Modal

                                </small>

                                <h3 class="mt-2 text-info fw-bold">

                                    Rp {{ number_format($summary['modal'], 0, ',', '.') }}

                                </h3>

                            </div>

                            <div class="fs-1 text-info">

                                <i class="bi bi-cash-stack"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <small class="text-secondary">

                                    Laba Bersih

                                </small>

                                <h3
                                    class="mt-2 fw-bold {{ $summary['laba_bersih'] >= 0 ? 'text-success' : 'text-danger' }}">

                                    Rp {{ number_format($summary['laba_bersih'], 0, ',', '.') }}

                                </h3>

                            </div>

                            <div class="fs-1 {{ $summary['laba_bersih'] >= 0 ? 'text-success' : 'text-danger' }}">

                                <i class="bi bi-graph-up-arrow"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- AKTIVA & EKUITAS --}}

        <div class="row g-4">

            <div class="col-lg-6">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-header bg-primary text-white">

                        <h5 class="mb-0">

                            <i class="bi bi-bank me-2"></i>

                            Aktiva

                        </h5>

                    </div>

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th>Akun</th>

                                    <th class="text-end">Nominal</th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach ($assets as $item)
                                    <tr>

                                        <td>

                                            <i class="bi {{ $item['icon'] }} text-{{ $item['color'] }}"></i>

                                            {{ $item['nama'] }}

                                        </td>

                                        <td class="text-end fw-semibold">

                                            Rp {{ number_format($item['nominal'], 0, ',', '.') }}

                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>

                            <tfoot>

                                <tr class="table-primary">

                                    <th>Total Aktiva</th>

                                    <th class="text-end">

                                        Rp {{ number_format($summary['saldo_kas'] + $summary['piutang'], 0, ',', '.') }}

                                    </th>

                                </tr>

                            </tfoot>

                        </table>

                    </div>

                </div>

            </div>

            <div class="col-lg-6">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-header bg-success text-white">

                        <h5 class="mb-0">

                            <i class="bi bi-pie-chart-fill me-2"></i>

                            Ekuitas

                        </h5>

                    </div>

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th>Akun</th>

                                    <th class="text-end">

                                        Nominal

                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach ($equity as $item)
                                    <tr>

                                        <td>

                                            <i class="bi {{ $item['icon'] }} text-{{ $item['color'] }}"></i>

                                            {{ $item['nama'] }}

                                        </td>

                                        <td class="text-end fw-semibold">

                                            Rp {{ number_format($item['nominal'], 0, ',', '.') }}

                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>

                            <tfoot>

                                <tr class="table-success">

                                    <th>Total Ekuitas</th>

                                    <th class="text-end">

                                        Rp {{ number_format($summary['modal'] + $summary['laba_bersih'], 0, ',', '.') }}

                                    </th>

                                </tr>

                            </tfoot>

                        </table>

                    </div>

                </div>

            </div>

        </div>
        <div class="row mt-4">

            <div class="col-lg-12">

                <div class="card shadow-sm border-0">

                    <div class="card-header bg-dark text-white">

                        <h5 class="mb-0">
                            <i class="bi bi-graph-up-arrow me-2"></i>
                            Ringkasan Laba Rugi
                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row text-center">

                            <div class="col-md-3">

                                <h6 class="text-secondary">
                                    Pendapatan
                                </h6>

                                <h3 class="text-primary">

                                    Rp {{ number_format($summary['pendapatan'], 0, ',', '.') }}

                                </h3>

                            </div>

                            <div class="col-md-3">

                                <h6 class="text-secondary">
                                    Modal
                                </h6>

                                <h3 class="text-info">

                                    Rp {{ number_format($summary['modal'], 0, ',', '.') }}

                                </h3>

                            </div>

                            <div class="col-md-3">

                                <h6 class="text-secondary">
                                    Beban
                                </h6>

                                <h3 class="text-danger">

                                    Rp {{ number_format($summary['beban_operasional'], 0, ',', '.') }}

                                </h3>

                            </div>

                            <div class="col-md-3">

                                <h6 class="text-secondary">
                                    Laba Bersih
                                </h6>

                                <h3 class="{{ $summary['laba_bersih'] >= 0 ? 'text-success' : 'text-danger' }}">

                                    Rp {{ number_format($summary['laba_bersih'], 0, ',', '.') }}

                                </h3>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
        <div class="row mt-4">

            <div class="col-lg-6">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-header bg-danger text-white">

                        <h5 class="mb-0">

                            <i class="bi bi-arrow-up-circle me-2"></i>

                            Breakdown Pengeluaran

                        </h5>

                    </div>

                    <div class="card-body">

                        @forelse($expenseBreakdown as $nama=>$nominal)
                            <div class="d-flex justify-content-between py-2 border-bottom">

                                <span>

                                    {{ $nama }}

                                </span>

                                <strong class="text-danger">

                                    Rp {{ number_format($nominal, 0, ',', '.') }}

                                </strong>

                            </div>

                        @empty

                            <div class="text-center text-muted">

                                Tidak ada data

                            </div>
                        @endforelse

                    </div>

                </div>

            </div>

            <div class="col-lg-6">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-header bg-success text-white">

                        <h5 class="mb-0">

                            <i class="bi bi-arrow-down-circle me-2"></i>

                            Breakdown Pemasukan

                        </h5>

                    </div>

                    <div class="card-body">

                        @forelse($incomeBreakdown as $nama=>$nominal)
                            <div class="d-flex justify-content-between py-2 border-bottom">

                                <span>

                                    {{ $nama }}

                                </span>

                                <strong class="text-success">

                                    Rp {{ number_format($nominal, 0, ',', '.') }}

                                </strong>

                            </div>

                        @empty

                            <div class="text-center text-muted">

                                Tidak ada data

                            </div>
                        @endforelse

                    </div>

                </div>

            </div>

        </div>
        <div class="row mt-4">

            <div class="col-md-3">

                <div class="card bg-primary text-white border-0">

                    <div class="card-body text-center">

                        <i class="bi bi-cash-stack fs-1"></i>

                        <h6 class="mt-3">

                            Kas Masuk

                        </h6>

                        <h4>

                            Rp {{ number_format($summary['kas_masuk'], 0, ',', '.') }}

                        </h4>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card bg-danger text-white border-0">

                    <div class="card-body text-center">

                        <i class="bi bi-wallet2 fs-1"></i>

                        <h6 class="mt-3">

                            Kas Keluar

                        </h6>

                        <h4>

                            Rp {{ number_format($summary['kas_keluar'], 0, ',', '.') }}

                        </h4>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card bg-info text-white border-0">

                    <div class="card-body text-center">

                        <i class="bi bi-receipt fs-1"></i>

                        <h6 class="mt-3">

                            Total Transaksi

                        </h6>

                        <h2>

                            {{ number_format($summary['jumlah_transaksi']) }}

                        </h2>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card bg-success text-white border-0">

                    <div class="card-body text-center">

                        <i class="bi bi-list-check fs-1"></i>

                        <h6 class="mt-3">

                            Item Pekerjaan

                        </h6>

                        <h2>

                            {{ number_format($summary['jumlah_item']) }}

                        </h2>

                    </div>

                </div>

            </div>

        </div>
        <div class="row mt-4">

            {{-- Cashflow --}}
            <div class="col-lg-12">

                <div class="card shadow-sm border-0">

                    <div class="card-header">

                        <h5 class="mb-0">

                            <i class="bi bi-bar-chart-line me-2"></i>

                            Cashflow Bulanan

                        </h5>

                    </div>

                    <div class="card-body">

                        <div id="cashflowChart"></div>

                    </div>

                </div>

            </div>

        </div>

        <div class="row mt-4">

            {{-- Pengeluaran --}}

            <div class="col-lg-6">

                <div class="card shadow-sm border-0">

                    <div class="card-header">

                        <h5 class="mb-0">

                            Breakdown Pengeluaran

                        </h5>

                    </div>

                    <div class="card-body">

                        <div id="expenseChart"></div>

                    </div>

                </div>

            </div>

            {{-- Pemasukan --}}

            <div class="col-lg-6">

                <div class="card shadow-sm border-0">

                    <div class="card-header">

                        <h5 class="mb-0">

                            Breakdown Pemasukan

                        </h5>

                    </div>

                    <div class="card-body">

                        <div id="incomeChart"></div>

                    </div>

                </div>

            </div>

        </div>
        <div class="row mt-4">

            <div class="col-lg-6">

                <div class="card shadow-sm border-0">

                    <div class="card-header bg-success text-white">

                        <h5 class="mb-0">

                            <i class="bi bi-trophy-fill me-2"></i>

                            Top 5 Pekerjaan Paling Menguntungkan

                        </h5>

                    </div>

                    <div class="card-body">

                        @forelse($topProfit as $item)
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">

                                <div>

                                    <strong>{{ $item['kode'] }}</strong>

                                    <br>

                                    <small class="text-secondary">

                                        {{ $item['item'] }}

                                    </small>

                                </div>

                                <span class="badge bg-success fs-6">

                                    Rp {{ number_format($item['laba_kotor'], 0, ',', '.') }}

                                </span>

                            </div>

                        @empty

                            <div class="text-center text-muted">

                                Tidak ada data

                            </div>
                        @endforelse

                    </div>

                </div>

            </div>
                <div class="col-lg-6">

        <div class="card shadow-sm border-0">

            <div class="card-header bg-primary text-white">

                <h5 class="mb-0">

                    <i class="bi bi-clock-history me-2"></i>

                    Transaksi Terbaru

                </h5>

            </div>

            <div class="card-body">

                @forelse($recentTransactions as $trx)

                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">

                        <div>

                            <strong>

                                {{ $trx->jobDivisi->kode ?? '-' }}

                            </strong>

                            <br>

                            <small class="text-secondary">

                                {{ $trx->peruntukan }}

                            </small>

                        </div>

                        <div class="text-end">

                            <span class="badge bg-{{ $trx->tipe=='in'?'success':'danger' }} text-white">

                                {{ strtoupper($trx->tipe) }}

                            </span>

                            <br>

                            <strong>

                                Rp {{ number_format($trx->total,0,',','.') }}

                            </strong>

                        </div>

                    </div>

                @empty

                    <div class="text-center text-muted">

                        Tidak ada transaksi

                    </div>

                @endforelse

            </div>

        </div>

    </div>

</div>
            @push('addScript')
                <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

                <script>
                    const cashflow = new ApexCharts(document.querySelector("#cashflowChart"), {

                        chart: {
                            type: 'bar',
                            height: 380,
                            toolbar: {
                                show: false
                            }
                        },

                        series: [{
                                name: 'Kas Masuk',
                                data: @json($cashflowMasuk)
                            },
                            {
                                name: 'Kas Keluar',
                                data: @json($cashflowKeluar)
                            }
                        ],

                        xaxis: {
                            categories: @json($cashflowLabels)
                        },

                        dataLabels: {
                            enabled: false
                        }

                    });

                    cashflow.render();
                    const expense = new ApexCharts(document.querySelector("#expenseChart"), {

                        chart: {
                            type: 'donut',
                            height: 350
                        },

                        labels: @json($expenseChartLabels),

                        series: @json($expenseChartSeries),

                        legend: {
                            position: 'bottom'
                        }

                    });

                    expense.render();
                    const income = new ApexCharts(document.querySelector("#incomeChart"), {

                        chart: {
                            type: 'donut',
                            height: 350
                        },

                        labels: @json($incomeChartLabels),

                        series: @json($incomeChartSeries),

                        legend: {
                            position: 'bottom'
                        }

                    });

                    income.render();
                </script>
            @endpush
        @endsection
