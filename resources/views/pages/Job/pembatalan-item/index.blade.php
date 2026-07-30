@extends('layouts.admin')

@section('title')
    Pembatalan Item Job Divisi
@endsection

@section('content')
    <div class="card">
        <div class="card-header justify-content-between">
            @include('pages.Job.pembatalan-item._modal_pembatalan_item')
            <div class="row">
                <div class="col-md-12">
                    <div class="input-group ">
                        <input type="text" class="form-control" name="q" placeholder="Cari Data">
                        <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>

                            <th>
                                Parent
                            </th>
                            <th>
                                Kode
                            </th>
                            <th>
                                Status
                            </th>
                            <th>
                                Tanggal
                            </th>
                            <th>

                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($items as $item)
                            <tr>
{{-- {{ dd($item) }} --}}
                                <td>
                                    {{ $item->jobDivisi->kode }}
                                </td>
                                <td>
                                    {{ $item->kode }}
                                </td>
                                <td style="text-transform: uppercase">
                                    {{ $item->status }}
                                </td>
                                <td>
                                    {{ $item->created_at }}
                                </td>
                                <td>
                                    <a href="{{ route('job.pembatalan-items.show', $item->id) }}" class="btn btn-info">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
