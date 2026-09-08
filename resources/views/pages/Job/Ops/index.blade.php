@extends('layouts.admin')

@section('title')
    Job Operasional
@endsection

@push('addStyle')
    <style>
        .hover-underline:hover {
            text-decoration: underline !important;
            cursor: pointer;
            color: #0d6efd;
        }
    </style>
@endpush

@push('page-title')
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);"
        aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Job</a></li>
            <li class="breadcrumb-item active fw-semibold text-uppercase" aria-current="page">Operasional</li>
        </ol>
    </nav>
@endpush

@section('content')
    <div class="card border-0 shadow-sm rounded-3">
        {{-- Header Card --}}
        <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
            <div>
                <h5 class="fw-bold m-0 text-dark">Daftar Job Operasional</h5>
                <p class="text-muted small m-0">Kelola dan pantau seluruh berkas proses operasional</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                @include('pages.Job.Ops._filter_ops')
            </div>
        </div>

        {{-- Body Tabel --}}
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-nowrap">
                    <thead class="bg-light">
                        <tr>
                            {{-- <th class="py-3 ps-4 text-secondary text-uppercase font-monospace small fw-bold" style="width: 50px;">No</th> --}}
                            <th class="py-3 text-secondary text-uppercase font-monospace small fw-bold">Parent</th>
                            <th class="py-3 text-center text-secondary text-uppercase font-monospace small fw-bold">Status Akad</th>
                            <th class="py-3 text-secondary text-uppercase font-monospace small fw-bold">Proses</th>
                            <th class="py-3 text-secondary text-uppercase font-monospace small fw-bold">Nomor Objek</th>
                            <th class="py-3 text-secondary text-uppercase font-monospace small fw-bold">Nama Penghadap</th>
                            <th class="py-3 text-secondary text-uppercase font-monospace small fw-bold">Nama Bank</th>
                            @can('job/ops/biaya proses')
                                <th class="py-3 text-secondary text-uppercase font-monospace small fw-bold">Biaya Proses</th>
                            @endcan
                            <th class="py-3 text-center text-secondary text-uppercase font-monospace small fw-bold">Status Pengerjaan</th>
                            <th class="py-3 pe-4 text-center text-secondary text-uppercase font-monospace small fw-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse ($items as $key => $item)
                            @php
                                $lastStatus = $item->statusJobOps->last();
                                $currentUserId = auth()->id();
                                $isSuperAdmin = auth()->user()->hasRole('super admin');

                                /* USER YANG DITUGASKAN */
                                $isAssignedUser =
                                    (int) $lastStatus?->user_id == (int) $currentUserId ||
                                    (int) $item->penugasan_user == (int) $currentUserId;

                                /* HAK PENUGASAN */
                                $canAssign = $isSuperAdmin || auth()->user()->can('job/ops/penugasan');

                                /* HAK PROGRESS */
                                $canProgress = $isSuperAdmin || $isAssignedUser;
                            @endphp
                            <tr>
                                {{-- No --}}
                                {{-- <td class="ps-4 fw-semibold text-secondary">
                                    {{ $items->firstItem() + $key }}
                                </td> --}}

                                {{-- Parent --}}
                                <td>
                                    <span class="hover-underline fw-semibold text-primary">
                                        @include('pages.Job.Ops._modal-parent', [
                                            'jobDivisi' => $item->jobDivisi,
                                            'key' => $key,
                                        ])
                                    </span>
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
                                        $statusAkad = $item->jobDivisi->status ?? '';
                                    @endphp
                                    <span class="badge rounded-pill {{ $statusClass[$statusAkad] ?? 'bg-secondary bg-opacity-10 text-secondary border' }} px-3 py-2 fw-medium text-uppercase" style="font-size: 0.75rem;">
                                        {{ $statusAkad ?: '-' }}
                                    </span>
                                </td>

                                {{-- Proses --}}
                                <td class="fw-medium text-dark">
                                    {{ $item->nama ?? '-' }}
                                </td>

                                {{-- Nomor Objek --}}
                                <td>
                                    <span class="text-muted">
                                        {{ $item->jobDivisi?->objek?->pluck('no_sertifikat')->implode(', ') ?: '-' }}
                                    </span>
                                </td>

                                {{-- Nama Penghadap --}}
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

                                {{-- Biaya Proses --}}
                                @can('job/ops/biaya proses')
                                    <td class="fw-medium text-dark">
                                        Rp {{ number_format($item->harga_proses, 0, ',', '.') }}
                                    </td>
                                @endcan

                                {{-- Status Pengerjaan --}}
                                <td class="text-center">
                                    @if ($item->status !== 'dispo')
                                        @php
                                            $status = $item->statusJobOps->last()->status ?? 'Belum dikerjakan';
                                            
                                            $pengerjaanClass = 'bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25';
                                            if ($status === 'Dikembalikan') {
                                                $pengerjaanClass = 'bg-success bg-opacity-10 text-success border border-success border-opacity-25';
                                            } elseif ($status !== 'Belum dikerjakan') {
                                                $pengerjaanClass = 'bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25';
                                            }
                                        @endphp
                                        <span class="badge {{ $pengerjaanClass }} px-2.5 py-1.5 fw-medium">
                                            {{ $status }}
                                        </span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2.5 py-1.5 fw-medium">
                                            Dispo
                                        </span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="pe-4 text-center">
                                    @if ($item->jobDivisi->status !== 'Batal Akad')
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            {{-- RIWAYAT --}}
                                            @include('pages.Job.Ops._modal_riwayat', [
                                                'statusJobOps' => $item->statusJobOps,
                                                'key' => $key,
                                                'formOrder' => $item,
                                            ])

                                            {{-- EDIT STATUS / PENUGASAN --}}
                                            <x-job.ops.edit-status-ops 
                                                :formOrder="$item" 
                                                :key="$key" 
                                                :jobDivisi="$item->jobDivisi"
                                                :statusOps="$item->statusJobOps" 
                                                :userOps="$userOps" 
                                                :userPermission="$permissionAll" />

                                            {{-- DISPO --}}
                                            @if ($canProgress && $item->status !== 'dispo')
                                                @can('job/ops/dispo')
                                                    @include('pages.Job.Ops._modal_dispo', [
                                                        'key' => $key,
                                                    ])
                                                @endcan
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted small fs-7">Tidak ada aksi</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5 text-muted">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                            fill="currentColor" class="bi bi-inbox text-secondary mb-2 opacity-50"
                                            viewBox="0 0 16 16">
                                            <path d="M4.98 4a.5.5 0 0 0-.39.188L1.54 8H6a.5.5 0 0 1 .5.5 1.5 1.5 0 1 0 3 0A.5.5 0 0 1 10 8h4.46l-3.05-3.812A.5.5 0 0 0 11.02 4H4.98zm-1.17-.437A1.5 1.5 0 0 1 4.98 3h6.04a1.5 1.5 0 0 1 1.17.563l3.7 4.625a.5.5 0 0 1 .106.374l-.39 3.124A1.5 1.5 0 0 1 14.117 13H1.883a1.5 1.5 0 0 1-1.489-1.314l-.39-3.124a.5.5 0 0 1 .106-.374l3.7-4.625z" />
                                        </svg>
                                        <span class="fw-medium">Belum ada data Job Operasional</span>
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

@push('addScript')
    <script>
        $(".select_2_ops").select2({
            theme: 'bootstrap-5',
            dropdownParent: $("#modalFilterOpsLabel"),
            width: "100%"
        });
    </script>
@endpush