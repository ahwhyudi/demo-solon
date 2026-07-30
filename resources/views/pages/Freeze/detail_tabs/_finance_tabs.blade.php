@include('pages.Job.Divisi.detail_tabs._modal_add_finance')
@php
    $approvedFinance = $jobDivisi->finance->filter(function ($finance) {
        $status = strtolower((string) $finance->status);
        return in_array($status, ['disetujui', 'approved']);
    });
    $totalPemasukan = (float) $approvedFinance->where('tipe', 'in')->sum('total');
    $totalPengeluaran = (float) $approvedFinance->where('tipe', 'out')->sum('total');
    $totalBiaya = (float) $jobDivisi->formOrder->sum('harga_proses');
    $profit = $totalPemasukan - $totalPengeluaran;
    $piutang = $totalBiaya - $totalPemasukan;
@endphp
<div class="card">
    <div class="card-status-top bg-green"></div>

    <div class="card-header gap-3 justify-content-end">

        <div data-bs-toggle="modal" data-bs-target="#addFinance" class="btn btn-success">
            + Catatan Transaksi Baru
        </div>
    </div>

    <div class="card-body border-bottom">
        <div class="row g-3">
            <div class="col-md-3">
                <div class="border rounded p-3 h-100">
                    <div class="text-secondary text-uppercase small">Pemasukan</div>
                    <div class="fs-3 fw-bold text-success">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
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
                    <div class="text-secondary text-uppercase small">Piutang</div>
                    <div class="fs-3 fw-bold {{ $piutang > 0 ? 'text-warning' : 'text-success' }}">
                        Rp {{ number_format($piutang, 0, ',', '.') }}
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
                        Tipe
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
                @foreach ($jobDivisi->finance as $index => $item)
                    <tr>
                        <td>
                            {{ $index + 1 }}
                        </td>
                        <td>
                            {{ $item->tanggal }}
                        </td>
                        <td>
                            {{ $item->tipe }}
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


                            {{ $item->user->name ?? '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
