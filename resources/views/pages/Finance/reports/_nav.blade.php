<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3">
            <div>
                <div class="text-secondary text-uppercase small">Finance Control</div>
                <h2 class="mb-1">Modul Laporan Keuangan</h2>
                <div class="text-secondary">Periode {{ $periodLabel }}</div>
            </div>
            <form action="" method="GET" class="d-flex flex-column flex-sm-row gap-2 align-items-sm-end">
                <div>
                    <label class="form-label">Periode Bulan</label>
                    <input type="month" name="month" class="form-control" value="{{ $month }}">
                </div>
                <div>
                    <button class="btn btn-primary">Terapkan</button>
                </div>
            </form>
        </div>

        <div class="row g-2 mt-2">
            <div class="col-md-3">
                <a href="{{ route('finance.reports.jurnal', ['month' => $month]) }}"
                    class="btn w-100 {{ request()->routeIs('finance.reports.jurnal') ? 'btn-primary' : 'btn-outline-primary' }}">
                    Jurnal
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('finance.reports.neraca', ['month' => $month]) }}"
                    class="btn w-100 {{ request()->routeIs('finance.reports.neraca') ? 'btn-primary' : 'btn-outline-primary' }}">
                    Neraca
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('finance.reports.laba-kotor', ['month' => $month]) }}"
                    class="btn w-100 {{ request()->routeIs('finance.reports.laba-kotor') ? 'btn-primary' : 'btn-outline-primary' }}">
                    Laba Kotor
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('finance.reports.laba-bersih', ['month' => $month]) }}"
                    class="btn w-100 {{ request()->routeIs('finance.reports.laba-bersih') ? 'btn-primary' : 'btn-outline-primary' }}">
                    Laba Bersih
                </a>
            </div>
        </div>
    </div>
</div>
