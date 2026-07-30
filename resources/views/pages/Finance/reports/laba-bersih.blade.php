@extends('layouts.admin')

@section('title')
    Laba Bersih
@endsection

@section('content')
    @include('pages.Finance.reports._nav')

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-secondary text-uppercase small">Laba Kotor</div>
                    <div class="fs-2 fw-bold">Rp {{ number_format($summary['laba_kotor'], 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-secondary text-uppercase small">Beban Operasional</div>
                    <div class="fs-2 fw-bold">Rp {{ number_format($summary['beban_operasional'], 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-secondary text-uppercase small">Piutang</div>
                    <div class="fs-2 fw-bold">Rp {{ number_format($summary['piutang'], 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-secondary text-uppercase small">Laba Bersih</div>
                    <div class="fs-2 fw-bold {{ $summary['laba_bersih'] >= 0 ? 'text-success' : 'text-danger' }}">
                        Rp {{ number_format($summary['laba_bersih'], 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="card-title">Komponen Laba Bersih</h3>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between">
                            <span>Total Pendapatan</span>
                            <strong>Rp {{ number_format($summary['pendapatan'], 0, ',', '.') }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between">
                            <span>Total Modal</span>
                            <strong>Rp {{ number_format($summary['modal'], 0, ',', '.') }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between">
                            <span>Laba Kotor</span>
                            <strong>Rp {{ number_format($summary['laba_kotor'], 0, ',', '.') }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between">
                            <span>Beban Operasional</span>
                            <strong>Rp {{ number_format($summary['beban_operasional'], 0, ',', '.') }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between">
                            <span>Laba Bersih</span>
                            <strong class="{{ $summary['laba_bersih'] >= 0 ? 'text-success' : 'text-danger' }}">
                                Rp {{ number_format($summary['laba_bersih'], 0, ',', '.') }}
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="card-title">Breakdown Beban Operasional</h3>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse ($expenseBreakdown as $label => $value)
                            <div class="list-group-item d-flex justify-content-between">
                                <span>{{ $label }}</span>
                                <strong>Rp {{ number_format($value, 0, ',', '.') }}</strong>
                            </div>
                        @empty
                            <div class="text-secondary">Belum ada pengeluaran disetujui pada periode ini.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
