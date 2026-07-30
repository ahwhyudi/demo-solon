@extends('layouts.admin')

@section('title')
    Job Pending
@endsection


@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>

                            <th>
                                Kode
                            </th>
                            <th>
                                Tanggal Mulai
                            </th>
                            <th>
                                Tanggal Selesai
                            </th>
                            <th>
                                Penambahan SLA
                            </th>
                            <th>
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>
                                    {{ $item->jobDivisi->kode }}
                                </td>
                                <td>
                                    {{ $item->start_date }}
                                </td>
                                <td>
                                    {{ $item->end_date }}
                                </td>
                                <td>
                                    {{ number_format($item->penambahan_sla) }}
                                </td>
                                <td>
                                    @can('berkas-bermasalah/pending/detail')
                                        <a href="{{ route('berkas-bermasalah.pending.show', $item->id) }}"
                                            class="btn btn-primary">Detail</a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
