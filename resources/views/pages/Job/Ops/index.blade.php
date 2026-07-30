@extends('layouts.admin')
@push('addStyle')
    <style>
        .hover-underline:hover {
            text-decoration: underline !important;
            cursor: pointer;
            color: blue;
        }
    </style>
@endpush

@push('page-title')
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);"
        aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Job </a></li>
            <li class="breadcrumb-item active" aria-current="page">
                Operasional
            </li>
        </ol>
    </nav>
@endpush

@section('title')
    Job Operasional
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-end ">
            @include('pages.Job.Ops._filter_ops')
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                No
                            </th>
                            <th>
                                Parent
                            </th>
                            <th>
                                Status Akad
                            </th>
                            <th>
                                Proses
                            </th>
                            <th>
                                Nomor Objek
                            </th>
                            <th>
                                Nama Penghadap
                            </th>
                            <th>
                                Nama Bank
                            </th>
                            @can('job/ops/biaya proses')
                                <th>
                                    Biaya Proses
                                </th>
                            @endcan
                            <th>
                                Status Pengerjaan
                            </th>
                            <th>
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $key => $item)
                            {{-- {{ dd($item) }} --}}
                            <tr>
                                @php
                                    $lastStatus = $item->statusJobOps->last();

                                    $currentUserId = auth()->id();

                                    $isSuperAdmin = auth()->user()->hasRole('super admin');

                                    /*
                                    |--------------------------------------------------------------------------
                                    | USER YANG DITUGASKAN
                                    |--------------------------------------------------------------------------
                                    */

                                    $isAssignedUser =
                                        (int) $lastStatus?->user_id == (int) $currentUserId ||
                                        (int) $item->penugasan_user == (int) $currentUserId;

                                    /*
                                        |--------------------------------------------------------------------------
                                        | HAK PENUGASAN
                                        |--------------------------------------------------------------------------
                                        |
                                        | bisa assign orang
                                        |
                                    */

                                    $canAssign = $isSuperAdmin || auth()->user()->can('job/ops/penugasan');

                                    /*
                                        |--------------------------------------------------------------------------
                                        | HAK PROGRESS
                                        |--------------------------------------------------------------------------
                                        |
                                        | lanjut step kerja
                                        |
                                    */

                                    $canProgress = $isSuperAdmin || $isAssignedUser;
                                @endphp
                                <td>
                                    {{ $items->firstItem() + $key }}
                                </td>
                                {{-- parent --}}
                                <td>
                                    <span class="hover-underline">
                                        @include('pages.Job.Ops._modal-parent', [
                                            'jobDivisi' => $item->jobDivisi,
                                            'key' => $key,
                                        ])

                                    </span>
                                </td>
                                {{-- proses --}}
                                <td style="text-transform: uppercase " class="">
                                    @php
                                        $statusClass = [
                                            'Pra Akad' => 'bg-info',
                                            'Akad' => 'bg-success',
                                            'Pending' => 'bg-warning',
                                            'Batal Akad' => 'bg-danger',
                                            'Selesai' => 'bg-primary',
                                        ];
                                    @endphp
                                    <span
                                        class="badge {{ $statusClass[$item->jobDivisi->status] ?? 'bg-dark' }} text-white p-2">
                                        {{ $item->jobDivisi->status }}
                                    </span>
                                </td>
                                <td>
                                    {{ $item->nama }}
                                </td>
                                <td>
                                    {{ $item->jobDivisi?->objek?->pluck('no_sertifikat')->implode(', ') ?? '-' }}
                                </td>
                                <td>
                                    @foreach ($item->jobDivisi->debitur as $namaDebitur)
                                        {{ $namaDebitur->nama }}
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($item->jobDivisi->listBank as $bank)
                                        {{ $bank->nama_bank }}
                                    @endforeach
                                </td>
                                {{-- biaya proses --}}
                                @can('job/ops/biaya proses')
                                    <td>
                                        Rp. {{ number_format($item->harga_proses) }}
                                    </td>
                                @endcan
                                {{-- <td>
                                    {{ $item->jobDivisi->status }}
                                </td> --}}
                                <td>
                                    @if ($item->status !== 'dispo')
                                        @php
                                            $status = $item->statusJobOps->last()->status ?? 'Belum dikerjakan';
                                        @endphp
                                        <span
                                            class="badge text-bg-secondary
                                        {{ $status !== 'Belum dikerjakan' && $status !== 'Dikembalikan' ? 'text-bg-warning' : '' }} {{ $status === 'Dikembalikan' ? 'text-bg-success' : '' }} ">
                                            {{ $status }}
                                        </span>
                                    @else
                                        <span class="badge text-bg-danger">
                                            Dispo
                                        </span>
                                    @endif
                                </td>
                                {{-- aksi --}}
                                <td>
                                    @if ($item->jobDivisi->status !== 'Batal Akad')
                                        <div class="d-flex gap-3">

                                            {{-- RIWAYAT --}}
                                            @include('pages.Job.Ops._modal_riwayat', [
                                                'statusJobOps' => $item->statusJobOps,
                                                'key' => $key,
                                                'formOrder' => $item,
                                            ])

                                            {{-- EDIT STATUS / PENUGASAN --}}
                                            <x-job.ops.edit-status-ops :formOrder="$item" :key="$key" :jobDivisi="$item->jobDivisi"
                                                :statusOps="$item->statusJobOps" :userOps="$userOps" :userPermission="$permissionAll" />


                                            {{-- DISPO --}}
                                            @if ($canProgress)
                                                @if ($item->status !== 'dispo')
                                                    @can('job/ops/dispo')
                                                        @include('pages.Job.Ops._modal_dispo', [
                                                            'key' => $key,
                                                        ])
                                                    @endcan
                                                @endif
                                            @endif

                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <th colspan="12" class="text-center">
                                    Tidak ada data
                                </th>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
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
