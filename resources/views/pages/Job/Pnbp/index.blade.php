@extends('layouts.admin')

@section('title')
    Job PNBP/Voucher
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">
                @include('pages.Job.Pnbp._filter')

            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                Parent
                            </th>
                            <th>
                                Proses
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
                                Nama Petugas
                            </th>
                            <th>
                                Status
                            </th>
                            <th>
                                Nominal
                            </th>
                            <th>
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $key => $item)
                            <tr>
                                @php
                                    $isSuperAdmin = auth()->user()->hasRole('super admin');

                                    $isAssignedUser = $item->pnbp && $item->pnbp->user_id == auth()->id();

                                    $canAssign = auth()->user()->can('job/pnbp/penugasan');

                                    $canAccess = $isSuperAdmin || $isAssignedUser;
                                @endphp
                                <td>
                                    {{ $item->jobDivisi->kode }}
                                </td>
                                <td>
                                    {{ $item->nama }}
                                </td>
                                <td>
                                    @if ($item->jobDivisi?->objek?->isNotEmpty())
                                        {{ $item->jobDivisi?->objek?->pluck('no_sertifikat')->implode(', ') ?? 'Belum di input no sertifikat' }}
                                    @else
                                        <span class="text-warning">
                                            Belum di input no sertifikat
                                        </span>
                                    @endif
                                </td>
                                <td>

                                    @forelse ($item->jobDivisi->debitur as $namaDebitur)
                                        {{ $item->jobDivisi->debitur->pluck('nama')->implode(', ') }}
                                    @empty
                                        <span class="text-warning">
                                            Belum di input nama debitur
                                        </span>
                                    @endforelse


                                </td>
                                <td>
                                    @foreach ($item->jobDivisi->listBank as $bank)
                                        {{ $bank->nama_bank }}
                                    @endforeach
                                </td>
                                <td>
                                    {{ $item->pnbp?->user?->name }}
                                </td>
                                <td class="">
                                    {{-- {{ $item->pnbp->status ?? 'Belum dikerjakan' }} --}}

                                    @if (!$item->pnbp)
                                        <span class="badge bg-secondary text-white">
                                            Belum dikerjakan
                                        </span>
                                    @elseif ($item->pnbp->status === 'Penugasan')
                                        <span class="badge bg-dark text-white">
                                            Penugasan
                                        </span>
                                    @elseif ($item->pnbp->status === 'Sedang Online')
                                        <span class="badge bg-warning text-white">
                                            Sedang Online
                                        </span>
                                    @elseif ($item->pnbp->status === 'Menunggu Pembayaran')
                                        <span class="badge bg-info text-white">
                                            Menunggu Pembayaran
                                        </span>
                                    @elseif ($item->pnbp->status === 'Terbayar')
                                        <span class="badge bg-success text-white">
                                            Terbayar
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    Rp {{ number_format($item->pnbp->nominal ?? 0, 0, ',', '.') }}
                                </td>
                                <td>

                                    {{-- BELUM ADA DATA --}}
                                    @if (!$item->pnbp)
                                        @if ($canAssign)
                                            @include('pages.Job.Pnbp._modal_assign', [
                                                'key' => $key,
                                                'item' => $item,
                                                'users' => $users,
                                            ])
                                        @endif
                                    @else
                                        {{-- PENUGASAN --}}
                                        @if ($item->pnbp->status === 'Penugasan' && $canAssign)
                                            @include('pages.Job.Pnbp._modal_assign', [
                                                'key' => $key,
                                                'item' => $item,
                                                'users' => $users,
                                            ])
                                        @endif


                                        {{-- INPUT VA --}}
                                        @if ($item->pnbp->status === 'Sedang Online' && $canAccess)
                                            @can('job/pnbp/input-va')
                                                @include('pages.Job.Pnbp._modal_input_va', [
                                                    'key' => $key,
                                                    'item' => $item,
                                                ])
                                            @endcan
                                        @endif


                                        {{-- PAYMENT --}}
                                        @if ($item->pnbp->status === 'Menunggu Pembayaran' && $canAccess)
                                            @can('job/pnbp/payment')
                                                @include('pages.Job.Pnbp._modal_payment', [
                                                    'key' => $key,
                                                    'item' => $item,
                                                ])
                                            @endcan
                                        @endif
                                        {{-- DONE --}}
                                        @if ($item->pnbp->status === 'Terbayar')
                                            <i class="bi bi-check-circle text-success" style="font-size: 20px;"></i>
                                        @endif
                                    @endif

                                </td>
                            </tr>
                            {{-- @empty
                            <tr>
                                <td colspan="8" class="text-center">
                                    Data tidak ada
                                </td>
                            </tr> --}}
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $items->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
