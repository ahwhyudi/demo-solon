@extends('layouts.admin')

@section('title')
    Job Divisi
@endsection

@push('page-title')
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);"
        aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Job</a></li>
            <li class="breadcrumb-item active fw-semibold" aria-current="page">Divisi</li>
        </ol>
    </nav>
@endpush

@section('content')
    <div class="card border-0 shadow-sm rounded-3">
        {{-- Card Header dengan padding lebih lega dan background bersih --}}
        <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
            <div class="d-flex align-items-center gap-2">
                @can('job/divisi/create')
                    @include('pages.Job.Divisi._modal_add_job')
                @endcan
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('job.export-job-divisi') }}"
                    class="btn btn-success d-inline-flex align-items-center justify-content-center gap-2 shadow-sm px-3">
                    <i class="bi bi-file-earmark-excel fs-6"></i>
                    <span>Export Excel</span>
                </a>

                <div class="d-flex align-items-center">
                    @include('pages.Job.Divisi._filter_divisi')
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                {{-- Menghapus table-bordered, mengganti dengan table-hover dan align-middle --}}
                <table class="table table-hover align-middle mb-0 text-nowrap">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 ps-4 text-secondary text-uppercase font-monospace small fw-bold">Kode</th>
                            <th class="py-3 text-secondary text-uppercase font-monospace small fw-bold">Status</th>
                            <th class="py-3 text-secondary text-uppercase font-monospace small fw-bold">Nama Penghadap</th>
                            <th class="py-3 text-secondary text-uppercase font-monospace small fw-bold">Bank</th>
                            <th class="py-3 text-secondary text-uppercase font-monospace small fw-bold">Objek</th>
                            <th class="py-3 text-secondary text-uppercase font-monospace small fw-bold">Jenis Akad</th>
                            <th class="py-3 text-secondary text-uppercase font-monospace small fw-bold">Tanggal Akad</th>
                            <th class="py-3 text-secondary text-uppercase font-monospace small fw-bold">Est. Internal</th>
                            <th class="py-3 text-secondary text-uppercase font-monospace small fw-bold">Est. Eksternal</th>
                            <th class="py-3 pe-4 text-end text-secondary text-uppercase font-monospace small fw-bold">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse ($items as $item)
                            <tr>
                                <td class="ps-4 fw-semibold">
                                    <a href="{{ route('job.divisi.show', $item->id) }}"
                                        class="text-primary text-decoration-none">
                                        {{ $item->kode }}
                                    </a>
                                </td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'Pra Akad' =>
                                                'bg-info bg-opacity-10 text-info border border-info border-opacity-25',
                                            'Akad' =>
                                                'bg-success bg-opacity-10 text-success border border-success border-opacity-25',
                                            'Pending' =>
                                                'bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25',
                                            'Batal Akad' =>
                                                'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25',
                                            'Selesai' =>
                                                'bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25',
                                        ];
                                    @endphp
                                    <span
                                        class="badge rounded-pill {{ $statusClass[$item->status] ?? 'bg-secondary' }} px-3 py-2 fw-medium text-uppercase"
                                        style="font-size: 0.75rem;">
                                        {{ $item->is_pending ? 'Pending' : $item->status }}
                                    </span>
                                </td>
                                <td class="text-uppercase text-wrap" style="max-width: 200px;">
                                    <span class="d-inline-block text-truncate w-100"
                                        title="{{ implode(', ', $item->debitur->pluck('nama')->toArray()) }}">
                                        {{ implode(', ', $item->debitur->pluck('nama')->toArray()) }}
                                    </span>
                                </td>
                                <td>
                                    @foreach ($item->listBank as $bank)
                                        <span class="badge bg-light text-dark border me-1">{{ $bank->nama_bank }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <span
                                        class="text-muted">{{ $item->objek->pluck('no_sertifikat')->implode(', ') ?: '-' }}</span>
                                </td>
                                <td>
                                    {{ $item->jenisAkad->nama ?? '-' }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1 text-secondary">
                                        <i class="bi bi-calendar2-event small"></i>
                                        <span>{{ $item->tanggal_akad ? $item->tanggal_akad : $item->tanggal_rencana_akad }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border">
                                        {{ $item->tanggal_estimasi_selesai ?: '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border">

                                        {{ $item->tanggal_estimasi_selesai_eksternal ?: '-' }}
                                    </span>
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        {{-- Tombol Detail --}}
                                        <a href="{{ route('job.divisi.show', $item->id) }}"
                                            class="btn btn-outline-info d-inline-flex align-items-center gap-1 px-3 py-1.5 shadow-sm fw-medium"
                                            title="Lihat Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                                                <path
                                                    d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                                <path
                                                    d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0" />
                                            </svg>
                                            {{-- <span>Detail</span> --}}
                                        </a>

                                        {{-- Tombol Export PDF --}}
                                        <a href="{{ route('job.export-quotation-job-divisi', $item->id) }}"
                                            class="btn btn-outline-danger d-inline-flex align-items-center gap-1 px-3 py-1.5 shadow-sm fw-medium"
                                            title="Export Quotation PDF" target="_blank">
                                            <i class="bi bi-file-earmark-pdf fs-6"></i>
                                            <span>PDF</span>
                                        </a>
                                        <a href="#"
                                            class="btn btn-danger d-inline-flex align-items-center gap-1 px-3 py-1.5 shadow-sm fw-medium"
                                            title="Export Quotation PDF" target="_blank">
                                            <i class="bi bi-trash-fill"></i>
                                            {{-- <span>Hapus</span> --}}
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            {{-- Penambahan Empty State agar tabel tidak terlihat rusak saat data kosong --}}
                            <tr>
                                <td colspan="10" class="text-center py-5 text-muted">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                            fill="currentColor" class="bi bi-inbox text-secondary mb-2 opacity-50"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M4.98 4a.5.5 0 0 0-.39.188L1.54 8H6a.5.5 0 0 1 .5.5 1.5 1.5 0 1 0 3 0A.5.5 0 0 1 10 8h4.46l-3.05-3.812A.5.5 0 0 0 11.02 4H4.98zm-1.17-.437A1.5 1.5 0 0 1 4.98 3h6.04a1.5 1.5 0 0 1 1.17.563l3.7 4.625a.5.5 0 0 1 .106.374l-.39 3.124A1.5 1.5 0 0 1 14.117 13H1.883a1.5 1.5 0 0 1-1.489-1.314l-.39-3.124a.5.5 0 0 1 .106-.374l3.7-4.625z" />
                                        </svg>
                                        <span class="fw-medium">Belum ada data Job Divisi</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Footer Card untuk Pagination dengan styling yang lebih bersih --}}
        @if ($items->hasPages())
            <div class="card-footer bg-white border-top py-3 px-4">
                <div class="d-flex justify-content-end m-0">
                    {{ $items->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
@endsection
