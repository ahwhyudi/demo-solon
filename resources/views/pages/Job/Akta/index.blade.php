@extends('layouts.admin')

@section('title')
    Job {{ strtoupper($tipe) }}
@endsection

@push('addStyle')
    {{-- <style>
        .table-responsive {
            overflow-x: auto;
        }

        .table-freeze-akta {
            min-width: 1300px;
        }

        .table-freeze-akta th,
        .table-freeze-akta td {
            white-space: nowrap;
            vertical-align: middle;
            background-color: #ffffff;
        }

        /* Freeze Columns */
        .freeze-parent {
            position: sticky;
            left: 0;
            z-index: 5;
            background: #ffffff;
            min-width: 120px;
            width: 120px;
        }

        .freeze-proses {
            position: sticky;
            left: 120px;
            z-index: 5;
            background: #ffffff;
            min-width: 180px;
            width: 180px;
        }

        .freeze-nomor-akta {
            position: sticky;
            left: 300px;
            z-index: 5;
            background: #ffffff;
            min-width: 200px;
            width: 200px;
            box-shadow: 3px 0 5px -2px rgba(0, 0, 0, 0.08);
        }

        .table-freeze-akta thead .freeze-parent,
        .table-freeze-akta thead .freeze-proses,
        .table-freeze-akta thead .freeze-nomor-akta {
            z-index: 8;
            background: #f8f9fa;
        }

        .table-freeze-akta tbody tr.bg-red-lt .freeze-parent,
        .table-freeze-akta tbody tr.bg-red-lt .freeze-proses,
        .table-freeze-akta tbody tr.bg-red-lt .freeze-nomor-akta {
            background: rgba(220, 53, 69, 0.05) !important;
        }
    </style> --}}
@endpush

@push('page-title')
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);"
        aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Job</a></li>
            <li class="breadcrumb-item active fw-semibold text-uppercase" aria-current="page">{{ $tipe }}</li>
        </ol>
    </nav>
@endpush

@section('content')
    <div class="card border-0 shadow-sm rounded-3">
        {{-- Card Header dengan padding lega dan border bersih --}}
        <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
            <div>
                <h5 class="fw-bold m-0 text-dark">Daftar Job {{ strtoupper($tipe) }}</h5>
                <p class="text-muted small m-0">Kelola dan pantau seluruh berkas proses {{ $tipe }}</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                @include('pages.Job.Akta._modal-filter')
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-nowrap table-freeze-akta">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 ps-4 freeze-parent text-secondary text-uppercase font-monospace small fw-bold">Parent</th>
                            <th class="py-3 freeze-proses text-secondary text-uppercase font-monospace small fw-bold">Proses</th>
                            <th class="py-3 freeze-nomor-akta text-secondary text-uppercase font-monospace small fw-bold">Nomor Akta</th>
                            <th class="py-3 text-secondary text-uppercase font-monospace small fw-bold">Status</th>
                            <th class="py-3 text-secondary text-uppercase font-monospace small fw-bold">Petugasan</th>
                            {{-- <th class="py-3 text-secondary text-uppercase font-monospace small fw-bold">Penugasan QC</th> --}}
                            <th class="py-3 text-secondary text-uppercase font-monospace small fw-bold">Nomor Objek</th>
                            <th class="py-3 text-secondary text-uppercase font-monospace small fw-bold">Nama Debitur</th>
                            <th class="py-3 text-secondary text-uppercase font-monospace small fw-bold">Nama Bank</th>
                            <th class="py-3 text-center text-secondary text-uppercase font-monospace small fw-bold">Status Akad</th>
                            <th class="py-3 pe-4 text-center text-secondary text-uppercase font-monospace small fw-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse ($items as $key => $item)
                            <tr class="{{ isset($item->statusJobOps->last()->status_penolakan) ? 'bg-red-lt' : '' }}">
                                {{-- Sticky Column 1: Parent --}}
                                <td class="ps-4 freeze-parent fw-semibold">
                                    <span class="text-primary">{{ $item->jobDivisi->kode ?? '-' }}</span>
                                </td>

                                {{-- Sticky Column 2: Proses --}}
                                <td class="freeze-proses fw-medium">
                                    {{ $item->nama ?? '-' }}
                                </td>

                                {{-- Sticky Column 3: Nomor Akta --}}
                                <td class="freeze-nomor-akta">
                                    <div class="fw-semibold">
                                        {{ $item->nomorPpat?->nomor ?? '-' }}
                                        @if ($item->nomorPpat?->rekanan === 1)
                                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 ms-1">Rekanan</span>
                                        @endif
                                    </div>
                                    @if ($item->nomorPpat?->tanggal)
                                        <div class="text-muted small">
                                            Tgl. {{ $item->nomorPpat?->tanggal }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Status Process --}}
                                <td>
                                    @if ($item->statusJobOps->last())
                                        @if ($item->statusJobOps->last()->status_penolakan)
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2.5 py-1.5 fw-medium">
                                                {{ $item->statusJobOps->last()->status_penolakan }} Perlu Perbaikan
                                            </span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2.5 py-1.5 fw-medium">
                                                {{ $item->nextStep }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="badge bg-light text-secondary border px-2.5 py-1.5 fw-medium">Belum diproses</span>
                                    @endif
                                </td>

                                {{-- Penugasan --}}
                                <td>
                                    <span class="text-secondary">
                                        {{ $item->statusJobOps->last()->user->name ?? 'Belum diproses' }}
                                    </span>
                                </td>

                                {{-- Penugasan QC --}}
                                {{-- <td>
                                    <span class="text-secondary">
                                        {{ $item->statusJobOps->last()->nextUser->name ?? 'Belum diproses' }}
                                    </span>
                                </td> --}}

                                {{-- Nomor Objek --}}
                                <td>
                                    <span class="text-muted">
                                        {{ $item->jobDivisi?->objek?->pluck('no_sertifikat')->implode(', ') ?: '-' }}
                                    </span>
                                </td>

                                {{-- Nama Debitur --}}
                                <td class="text-uppercase text-wrap" style="max-width: 200px;">
                                    <div class="d-flex flex-column gap-1">
                                        @forelse ($item->jobDivisi->debitur as $namaDebitur)
                                            <span>{{ $namaDebitur->nama }}</span>
                                        @empty
                                            <span class="text-muted">-</span>
                                        @endforelse
                                    </div>
                                </td>

                                {{-- Nama Bank --}}
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @forelse ($item->jobDivisi->listBank as $bank)
                                            <span class="badge bg-light text-dark border">{{ $bank->nama_bank }}</span>
                                        @empty
                                            <span class="text-muted">-</span>
                                        @endforelse
                                    </div>
                                </td>

                                {{-- Status Akad --}}
                                <td class="text-center">
                                    @php
                                        $statusClass = [
                                            'Pra Akad'   => 'bg-info bg-opacity-10 text-info border border-info border-opacity-25',
                                            'Akad'       => 'bg-success bg-opacity-10 text-success border border-success border-opacity-25',
                                            'Pending'    => 'bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25',
                                            'Batal Akad' => 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25',
                                            'Selesai'    => 'bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25',
                                        ];
                                        $status = $item->jobDivisi->status ?? '';
                                    @endphp

                                    <span class="badge rounded-pill {{ $statusClass[$status] ?? 'bg-secondary' }} px-3 py-2 fw-medium text-uppercase"
                                          style="font-size: 0.75rem;">
                                        {{ $status ?: '-' }}
                                    </span>
                                </td>

                                {{-- Action Buttons --}}
                                <td class="pe-4 text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        @if ($item->jobDivisi->status !== 'Batal Akad')
                                            @if ($item->jobDivisi->status !== 'Selesai')
                                                <x-job.akta.edit-akta 
                                                    :formOrder="$item" 
                                                    :jobDivisi="$item->jobDivisi" 
                                                    :key="$key"
                                                    :statusJobOps="$item->statusJobOps" 
                                                    :tipe="$tipe" 
                                                    :users="$user_ops" />
                                            @endif

                                            @if (!$item->nomorPpat)
                                                @include('pages.Job.Akta._input-no-ppat', [
                                                    'formOrder' => $item,
                                                    'key' => $key,
                                                    'nama_proses' => $item->nama,
                                                ])
                                            @endif

                                            @include('pages.Job.Ops._modal_riwayat', [
                                                'statusJobOps' => $item->statusJobOps->sortByDesc('id'),
                                                'key' => $key,
                                                'formOrder' => $item,
                                            ])
                                        @else
                                            <span class="text-muted small fs-7">Tidak ada aksi</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-5 text-muted">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                            fill="currentColor" class="bi bi-inbox text-secondary mb-2 opacity-50"
                                            viewBox="0 0 16 16">
                                            <path d="M4.98 4a.5.5 0 0 0-.39.188L1.54 8H6a.5.5 0 0 1 .5.5 1.5 1.5 0 1 0 3 0A.5.5 0 0 1 10 8h4.46l-3.05-3.812A.5.5 0 0 0 11.02 4H4.98zm-1.17-.437A1.5 1.5 0 0 1 4.98 3h6.04a1.5 1.5 0 0 1 1.17.563l3.7 4.625a.5.5 0 0 1 .106.374l-.39 3.124A1.5 1.5 0 0 1 14.117 13H1.883a1.5 1.5 0 0 1-1.489-1.314l-.39-3.124a.5.5 0 0 1 .106-.374l3.7-4.625z" />
                                        </svg>
                                        <span class="fw-medium">Belum ada data Job {{ strtoupper($tipe) }}</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Footer Pagination --}}
        <div class="card-footer bg-white border-top py-3 px-4 d-flex align-items-center justify-content-between">
            <div class="text-muted small">
                Menampilkan <strong>{{ $items->firstItem() ?? 0 }}</strong> - <strong>{{ $items->lastItem() ?? 0 }}</strong> dari <strong>{{ $items->total() }}</strong> data
            </div>
            <div class="m-0">
                {{ $items->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection