@extends('layouts.admin')

@section('title')
    Job {{ strtoupper($tipe) }}
@endsection


@push('addStyle')
    <style>
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
            background-color: white;
        }

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
            box-shadow: 4px 0 6px rgba(0, 0, 0, 0.04);
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
            background: var(--tblr-red-lt, #ffe3e3);
        }
    </style>
@endpush


@push('page-title')
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);"
        aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Job </a></li>
            <li class="breadcrumb-item active" aria-current="page" style="text-transform: uppercase">
                {{ $tipe }}
            </li>
        </ol>
    </nav>
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">

                @include('pages.Job.Akta._modal-filter')
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-sm table-freeze-akta">
                    <thead>
                        <tr>
                            <th class="">
                                Parent
                            </th>
                            <th class="">
                                Proses
                            </th>
                            <th class="">
                                Nomor Akta
                            </th>
                            <th>
                                Status
                            </th>
                            <th>
                                Penugasan
                            </th>
                            <th>
                                Penugasan QC
                            </th>
                            <th>
                                Nomor Objek
                            </th>
                            <th>
                                Nama Debitur
                            </th>
                            <th>
                                Nama Bank
                            </th>
                            <th>
                                Status Akad
                            </th>
                            <th>
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $key => $item)
                            <tr class="{{ isset($item->statusJobOps->last()->status_penolakan) ? 'bg-red-lt ' : '' }}">
                                <th class="text-primary ">
                                    {{ $item->jobDivisi->kode }}
                                </th>

                                <td class="">
                                    {{ $item->nama }}
                                </td>

                                <td class="">
                                    {{ $item->nomorPpat?->nomor }} {{ $item->nomorPpat?->rekanan === 1 ? '(Rekanan)' : '' }}
                                    @if ($item->nomorPpat?->tanggal)
                                        <br>
                                        <small>
                                            (Tgl. {{ $item->nomorPpat?->tanggal }})
                                        </small>
                                    @endif
                                </td>

                                <td>
                                    @if ($item->statusJobOps->last())
                                        {{ $item->statusJobOps->last()->status_penolakan ? "{$item->statusJobOps->last()->status_penolakan} Perlu Perbaikan" : $item->nextStep }}
                                    @else
                                        Belum diproses
                                    @endif
                                </td>

                                <td>
                                    @if ($item->statusJobOps->last())
                                        {{ $item->statusJobOps->last()->user->name ?? '-' }}
                                    @else
                                        Belum diproses
                                    @endif
                                </td>

                                <td>
                                    @if ($item->statusJobOps->last())
                                        {{ $item->statusJobOps->last()->nextUser->name ?? '-' }}
                                    @else
                                        Belum diproses
                                    @endif
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

                                    <span class="badge text-{{ $statusClass[$item->jobDivisi->status] ?? 'bg-dark' }}">
                                        {{ $item->jobDivisi->status }}
                                    </span>
                                </td>

                                <td class="d-flex gap-4">
                                    @if ($item->jobDivisi->status !== 'Batal Akad')
                                        @if ($item->jobDivisi->status !== 'Selesai' && $item->jobDivisi->status !== 'Batal Akad')
                                            <x-job.akta.edit-akta :formOrder="$item" :jobDivisi="$item->jobDivisi" :key="$key"
                                                :statusJobOps="$item->statusJobOps" :tipe="$tipe" :users="$user_ops" />
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
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $items->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
