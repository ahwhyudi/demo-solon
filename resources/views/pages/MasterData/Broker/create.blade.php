@extends('layouts.admin')

@section('title')
    Tambah Broker
@endsection

@push('page-title')
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);"
        aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Master Data</a></li>
            <li class="breadcrumb-item "><a href="{{ route('master-data.broker.index') }}">Broker</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Data</li>
        </ol>
    </nav>
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('master-data.broker.store') }}" method="post">
                @csrf
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="" class="form-label required">Nama Perumahan</label>
                        <input type="text" class="form-control" name="nama_perumahan" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="" class="form-label required">Nama Perusahaan</label>
                        <input type="text" class="form-control" name="nama_perusahaan" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="" class="form-label required">Email Perusahaan</label>
                        <input type="email" class="form-control" name="email_perusahaan" autocomplete="off" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="" class="form-label required">Nama Pimpinan</label>
                        <input type="text" class="form-control" name="nama_pimpinan" required>
                    </div>
                </div>

                @include('pages.MasterData.Broker._add_marketing')

                <button class="btn btn-primary mt-5">
                    Simpan
                </button>
            </form>
        </div>
    </div>
@endsection
