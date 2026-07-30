@extends('layouts.admin')

@section('title')
    Edit Developer
@endsection

@push('page-title')
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);"
        aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Master Data</a></li>
            <li class="breadcrumb-item "><a href="{{ route('master-data.developer.index') }}">Developer</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
        </ol>
    </nav>
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('master-data.developer.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="" class="form-label required">Nama Perumahan</label>
                        <input type="text" class="form-control" name="nama_perumahan" required value="{{ old('nama_perumaan', $item->nama_perumahan) }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="" class="form-label required">Nama Perusahaan</label>
                        <input type="text" class="form-control" name="nama_pt" required value="{{ old('nama_pt', $item->nama_pt) }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="" class="form-label required">Email Perusahaan</label>
                        <input type="email" class="form-control" name="email_perusahaan" autocomplete="off" required value="{{ old('email_perusahaan', $item->email_perusahaan) }}">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="" class="form-label required">Nama Pimpinan</label>
                        <input type="text" class="form-control" name="nama_pimpinan" required value="{{ old('nama_pimpinan', $item->nama_pimpinan) }}">
                    </div>
                </div>

                @include('pages.MasterData.Developer._add_marketing')
                @include('pages.MasterData.Developer._add_legal')
                <button class="btn btn-primary mt-5">
                    Simpan
                </button>
            </form>
        </div>
    </div>
@endsection
