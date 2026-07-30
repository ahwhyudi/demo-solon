@extends('layouts.admin')

@section('title')
    Job Divisi {{ $jobDivisi->kode }}
@endsection

@push('page-title')
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);"
        aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Job </a></li>
            <li class="breadcrumb-item"><a href="{{ route('job.divisi.index') }}">Job Divisi</a></li>
            <li class="breadcrumb-item active" aria-current="page">
                {{ $jobDivisi->kode }}
            </li>
        </ol>
    </nav>
@endpush

@section('content')
    <!-- Modal -->
    <div class="modal fade" id="modalAddFile" tabindex="-1" aria-labelledby="modalAddFileLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalAddFileLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <div class="card-title">
                Upload File
            </div>
        </div>
        <div class="card-body">
            <div class="btn__upload_file bg-secondary text-center text-white py-3 rounded" data-bs-toggle="modal"
                data-bs-target="#modalAddFile">
                <div class="">
                    <span style="font-size: 20px">
                        <i class="bi bi-cloud-arrow-up"></i>
                    </span>
                    <p class="">
                        Upload
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                Ringkasan
            </div>
        </div>
        <div class="card-body">
            @include('pages.Job.Divisi.detail_tabs._ringkasan')
        </div>
    </div>
@endsection
