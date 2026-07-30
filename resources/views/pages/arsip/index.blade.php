@extends('layouts.admin')

@section('title')
    Bundle Nomor
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            @can('arsip/ppat/create')
                <div class="mb-4">
                    @include('pages.arsip._modal-bundle')
                </div>
            @endcan
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Nomor Bundle</th>
                            <th>Total Nomor</th>
                            <th>Tahun</th>
                            <th>Bulan</th>
                            <th>Tanggal Penginputan</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            <tr>
                                <td>{{ $item->nomor }}</td>
                                <td>{{ $item->nomor_ppat_count }}</td>
                                <td>{{ $item->tahun }}</td>
                                <td>{{ $item->bulan ? $bulanList[$item->bulan] ?? $item->bulan : 'Semua bulan' }}</td>
                                <td>{{ $item->created_at }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modalDetailBundle{{ $item->id }}">
                                            Detail
                                        </button>
                                        @can('arsip/ppat/delete')
                                            <form action="{{ route('arsip.bundle.destroy', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm confirm_delete"
                                                    data-message="bundle {{ $item->nomor }}">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="text-center">Tidak ada data</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $items->links() }}
            </div>
        </div>
    </div>

    @foreach ($items as $item)
        <div class="modal fade" id="modalDetailBundle{{ $item->id }}" tabindex="-1"
            aria-labelledby="modalDetailBundle{{ $item->id }}Label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalDetailBundle{{ $item->id }}Label">
                            Detail Bundle Nomor
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Nomor Bundle</label>
                                <input type="text" class="form-control" value="{{ $item->nomor }}" disabled>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Bulan</label>
                                <input type="text" class="form-control"
                                    value="{{ $item->bulan ? $bulanList[$item->bulan] ?? $item->bulan : 'Semua bulan' }}"
                                    disabled>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tahun</label>
                                <input type="text" class="form-control" value="{{ $item->tahun }}" disabled>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea class="form-control" rows="3" disabled>{{ $item->keterangan }}</textarea>
                        </div>

                        <div class="mb-0">
                            <label class="form-label">Nomor yang Dibundling</label>
                            <div class="border rounded">
                                <div class="px-3 pt-3">
                                    <div class="alert alert-info mb-0">
                                        Total {{ $item->nomor_ppat_count }} nomor dalam bundle ini.
                                    </div>
                                </div>
                                <div class="p-3">
                                    <div class="row g-2">
                                        @forelse ($item->nomorPpat->sortBy(fn($nomorPpat) => (int) $nomorPpat->nomor) as $nomorPpat)
                                            @php
                                                $namaProses = $nomorPpat->form_order_id
                                                    ? $nomorPpat->pekerjaan?->nama ?? '-'
                                                    : $nomorPpat->formOrder?->nama ?? '-';
                                                $namaDebitur = $nomorPpat->form_order_id
                                                    ? $nomorPpat->nama_debitur_notaris_pengambil ?? '-'
                                                    : ($nomorPpat->formOrder?->jobDivisi?->debitur
                                                        ?->pluck('nama')
                                                        ->implode(', ') ?:
                                                    '-');
                                                $objek = $nomorPpat->form_order_id
                                                    ? $nomorPpat->objek_notaris_pengambil ?? '-'
                                                    : ($nomorPpat->formOrder?->jobDivisi?->objek
                                                        ?->pluck('no_sertifikat')
                                                        ->implode(', ') ?:
                                                    '-');
                                            @endphp
                                            <div class="col-12 col-md-6">
                                                <label class="form-selectgroup-item w-100">
                                                    <input type="checkbox" class="form-selectgroup-input" checked disabled>
                                                    <span class="form-selectgroup-label text-start w-100">
                                                        <span class="d-block fw-bold">Nomor: {{ $nomorPpat->nomor }}</span>
                                                        <span class="d-block text-secondary small text-truncate">
                                                            {{ $namaProses }}
                                                        </span>
                                                        <span class="d-block text-secondary small text-truncate">
                                                            Debitur: {{ $namaDebitur }}
                                                        </span>
                                                        <span class="d-block text-secondary small text-truncate">
                                                            Objek: {{ $objek }}
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                        @empty
                                            <div class="col-12 text-secondary text-center py-4">
                                                Belum ada nomor dalam bundle ini.
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
