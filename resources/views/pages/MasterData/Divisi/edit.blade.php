@extends('layouts.admin')

@section('title')
    Edit Divisi
@endsection

@push('page-title')
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);"
        aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Master Data</a></li>
            <li class="breadcrumb-item "><a href="{{ route('master-data.divisi.index') }}">Divisi</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </nav>
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('master-data.divisi.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3 col-md-6 d-flex align-items-end gap-2">
                    <div class="flex-grow-1">
                        <label for="nama" class="form-label">Nama Divisi</label>
                        <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $item->nama) }}">
                    </div>
                    <div>
                        <button class="btn btn-primary" type="submit">Simpan</button>
                    </div>
                </div>                
            </form>
        </div>
    </div>
@endsection