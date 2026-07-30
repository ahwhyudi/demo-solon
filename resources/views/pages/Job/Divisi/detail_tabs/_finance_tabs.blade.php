@include('pages.Job.Divisi.detail_tabs._modal_add_finance')
<div class="card">
    <div class="card-status-top bg-green"></div>

    <div class="card-header gap-3 justify-content-end">
        {{-- <a href="{{ route('pdf.invoice', $jobDivisi->id) }}" class="btn btn-success" target="_blank">
            Print invoice
        </a> --}}
        @include('pages.Job.Divisi.detail_tabs._modal-print-invoice')
        <div data-bs-toggle="modal" data-bs-target="#addFinance" class="btn btn-primary">
            + Catatan Transaksi Baru
        </div>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-warning position-relative" data-bs-toggle="modal"
            data-bs-target="#modalFilterFinance">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-funnel-fill" viewBox="0 0 16 16">
                <path
                    d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5z" />
            </svg>

            {{-- Filter Finance --}}

            @if ($countFilter)
                <span
                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger text-white">
                    {{ $countFilter }}
                </span>
            @endif
        </button>

        <!-- Modal -->
        <div class="modal fade" id="modalFilterFinance" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header ">
                        <h1 class=" modal-title fs-5 ">
                            Filter Finance
                        </h1>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>

                    <div class="modal-body">

                        <form action="{{ route('job.divisi.show', $jobDivisi->id) }}#tabs-finance">

                            <div class="mb-3">
                                <label class="form-label">
                                    Tipe
                                </label>

                                <select name="tipe" class="form-select select_2_ops">

                                    <option value=""></option>

                                    <option value="in" {{ request()->tipe == 'in' ? 'selected' : '' }}>
                                        Pemasukan
                                    </option>

                                    <option value="out" {{ request()->tipe == 'out' ? 'selected' : '' }}>
                                        Pengeluaran
                                    </option>

                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    Peruntukan
                                </label>

                                <select name="peruntukan" class="form-select select_2_ops">

                                    <option value=""></option>

                                    @foreach ($peruntukanList as $item)
                                        <option value="{{ $item }}"
                                            {{ request()->peruntukan == $item ? 'selected' : '' }}>
                                            {{ $item }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="d-flex gap-3 justify-content-end mt-4">

                                <a href="{{ route('job.divisi.show', $jobDivisi->id) }}#tabs-finance"
                                    class="btn btn-secondary">
                                    Reset
                                </a>

                                <button class="btn btn-primary">
                                    Terapkan Filter
                                </button>

                            </div>

                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="card-body border-bottom">
        <div class="row g-3">
            <div class="col-md-3">
                <div class="border rounded p-3 h-100">
                    <div class="text-secondary text-uppercase small">Pemasukan</div>
                    <div class="fs-3 fw-bold text-success">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
                    {{-- {{ dd() }} --}}
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 h-100">
                    <div class="text-secondary text-uppercase small">Pengeluaran</div>
                    <div class="fs-3 fw-bold text-danger">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 h-100">
                    <div class="text-secondary text-uppercase small">Profit</div>
                    <div class="fs-3 fw-bold {{ $profit >= 0 ? 'text-primary' : 'text-danger' }}">
                        Rp {{ number_format($profit, 0, ',', '.') }}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 h-100">
                    <div class="text-secondary text-uppercase small">
                        {{ $piutang < 0 ? 'Pengembalian Dana' : 'Piutang' }}</div>
                    <div class="fs-3 fw-bold {{ $piutang > 0 ? 'text-warning' : 'text-success' }}">
                        Rp {{ number_format($piutang, 0, ',', '.') }}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-3 h-100">
                    <div class="text-secondary text-uppercase small">Total Biaya</div>
                    <div class="fs-3 fw-bold {{ $totalBiaya > 0 ? 'text-warning' : 'text-success' }}">
                        Rp {{ number_format($totalBiaya, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>
                        No
                    </th>
                    <th>
                        Tanggal
                    </th>
                    <th>
                        Nama Proses
                    </th>
                    <th>
                        Tipe
                    </th>
                    <th>
                        Peruntukan
                    </th>
                    <th>
                        Total
                    </th>
                    <th>
                        Metode Pembayaran
                    </th>
                    <th>
                        Keterangan
                    </th>
                    <th>
                        Status
                    </th>
                    <th>
                        Pembuat
                    </th>
                </tr>
            </thead>
            <tbody>
            <tbody>
                @foreach ($filteredFinance as $index => $item)
                    <tr>
                        <td>
                            {{ $index + 1 }}
                        </td>
                        <td>
                            {{ $item->tanggal }}
                        </td>
                        <td>
                            {{ $item->formOrder->nama ?? '-' }}
                        </td>
                        <td>
                            {{ $item->tipe }}
                        </td>
                        <td>
                            {{ $item->peruntukan }} {{ $item->invoice ? "({$item->invoice->kategori})" : '' }}
                        </td>
                        <td>
                            Rp. {{ number_format($item->total) }}
                        </td>
                        <td>
                            {{ $item->metode_pembayaran }}
                        </td>
                        <td>
                            {{ $item->keterangan }}
                        </td>
                        <td>
                            {{ $item->status }}
                        </td>
                        <td>
                            {{ $item->peruntukan === 'pnbp' ? $item->pnbp->user->name ?? '-' : $item->user->name ?? '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
