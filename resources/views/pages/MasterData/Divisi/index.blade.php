@extends('layouts.admin')

@section('title')
    Master Data Divisi
@endsection

@push('page-title')
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);"
        aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Master Data</a></li>
            <li class="breadcrumb-item active" aria-current="page">Divisi</li>
        </ol>
    </nav>
@endpush

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="">
                <a href="{{ route('master-data.divisi.create') }}" class="btn btn-primary">
                    Tambah Data
                </a>
            </div>

            <div class="">
                <form action="">
                    <div class="input-group ">
                        <input type="text" name="q" class="form-control" placeholder="Cari Data ..."
                            aria-label="Cari Data ..." aria-describedby="button-addon2">
                        <button class="btn btn-primary" type="button" id="button-addon2"><i
                                class="bi bi-search"></i></button>
                    </div>
                </form>
            </div>

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama </th>
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
                                    <div class="d-flex gap-4">
                                        <form action="{{ route('master-data.divisi.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('delete')
                                            <span class="btn btn-danger confirm_delete"
                                                data-message="{{ $item->nama }}">
                                                Hapus
                                            </span>
                                        </form>
                                        <a href="{{ route('master-data.divisi.edit', $item->id) }}" class="btn btn-secondary" type="submit">Edit</a href="{{}}">
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <th class="text-center" colspan="12">Data Kosong</th>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
