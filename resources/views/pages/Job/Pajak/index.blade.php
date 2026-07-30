@extends('layouts.admin')

@section('title')
    Job Pajak
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
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
                                Pembayaran
                            </th>
                            <th>
                                Status Akad
                            </th>
                            <th>
                                Status
                            </th>
                            <th>
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $key => $item)
                            <tr>
                                <td>
                                    {{ $item->jobDivisi->kode }}
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
                                <td>
                                    Rp. {{ number_format($item->harga_jual, 2, ',', '.') }}
                                </td>
                                <td>
                                    {{ $item->jobDivisi->status }}
                                </td>
                                <td>
                                    {{ $item->statusJobOps->last()->status ?? 'Belum dikerjakan' }}
                                </td>
                                <td>
                                    @can('job/pajak/edit')
                                        @if ($item->jobDivisi->status !== 'Batal Akad')
                                            <x-pajak.edit-status-pajak :formOrder="$item" :key="$key" :jobDivisi="$item->jobDivisi"
                                                :statusJobOps="$item->statusJobOps" />
                                        @endif
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center">
                                    Data Kosong
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
