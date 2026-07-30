@extends('layouts.admin')

@section('title')
    Dispo
@endsection


@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>

                            <th>
                                Kode Parent
                            </th>
                            <th>
                                Pekerjaan
                            </th>
                            <th>
                                Tanggal Mulai
                            </th>
                            <th>
                                Tanggal Selesai
                            </th>
                            <th>
                                Dibuat Oleh
                            </th>
                            <th>
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $key => $item)
                            <tr>
                                <td>
                                    {{ $item->jobDivisiFormOrder->jobDivisi->kode }}
                                </td>
                                <td>
                                    {{ $item->jobDivisiFormOrder->nama }}
                                </td>
                                <td>
                                    {{ $item->start_date }}
                                </td>
                                <td>
                                    {{ $item->end_date }}
                                </td>
                                <td>
                                    {{ $item->dibuat->name }}
                                </td>
                                <td>
                                    @include('pages.dispo._modal-detail-item-dispo', [
                                        'key' => $key,
                                        'item' => $item,
                                    ])
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
