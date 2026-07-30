@extends('layouts.admin')

@section('title')
    Master Data Paket Pekerjaan
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">

                <a href="{{ route('master-data.form-order.create') }}" class="btn btn-primary">
                    Tambah Data
                </a>
                
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama </th>
                            <th>Jumlah Proses</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $index => $item)
                            <tr>
                                <td>
                                    {{ $index + 1 }}
                                </td>
                                <td>
                                    {{ $item->nama }}
                                </td>
                                <td>
                                    {{ $item->details_count }}
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <form action="{{route('master-data.form-order.destroy', $item->id)}}" method="POST">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger confirm_delete">
                                                hapus
                                            </button>
                                        </form>
                                        <a href="{{ route('master-data.form-order.edit', $item->id) }}" class="btn btn-primary">
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <th class="text-center" colspan="12">Data Kosong</th>
                            </tr>
                        @endforelse
                </table>
            </div>
        </div>
    </div>
@endsection
