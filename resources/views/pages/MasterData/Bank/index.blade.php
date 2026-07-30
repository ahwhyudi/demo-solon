@extends('layouts.admin')

@section('title')
    Master Data Bank
@endsection

@push('page-title')
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);"
        aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Master Data</a></li>
            <li class="breadcrumb-item active" aria-current="page">Bank</li>
        </ol>
    </nav>
@endpush

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="d-flex gap-4">
                <a href="{{ route('master-data.bank.create') }}" class="btn btn-primary">
                    Tambah Data
                </a>
                @include('pages.MasterData.Bank._modal_import_bank')
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
                                        <form action="{{ route('master-data.bank.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger confirm_delete" data-message="{{ $item->nama }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">

                                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5
                                                                        0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1
                                                0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5
                                                0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />

                                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1
                                                1H13v9a2 2 0 0 1-2
                                                2H5a2 2 0 0 1-2-2V4h-.5a1
                                                1 0 0 1-1-1V2a1 1 0 0 1
                                                1-1H6a1 1 0 0 1 1-1h2a1
                                                1 0 0 1 1 1h3.5a1 1 0 0
                                                1 1 1v1zM4.118 4 4 4.059V13a1
                                                1 0 0 0 1 1h6a1 1 0 0 0
                                                1-1V4.059L11.882 4H4.118zM2.5
                                                3V2h11v1h-11z" />

                                                </svg>
                                            </button>
                                        </form>
                                            <a href="{{ route('master-data.bank.edit', $item->id) }}?tipe=kontrak"
                                                class="btn btn-secondary text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                    <path
                                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                    <path fill-rule="evenodd"
                                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                </svg>
                                            </a>
                                        {{-- <div class="dropdown-up">
                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false" data-bs-boundary="body">
                                                Aksi
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item"
                                                        href="{{ route('master-data.bank.show', $item->id) }}">Detail</a>
                                                </li>
                                                <li><a class="dropdown-item"
                                                        href="{{ route('master-data.bank.edit', $item->id) }}?tipe=kontrak">Edit
                                                        Kontrak</a></li>
                                                <li><a class="dropdown-item" href="#">Edit Organisasi</a></li>
                                            </ul>
                                        </div> --}}
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
