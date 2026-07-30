@extends('layouts.admin')

@section('title')
    Tambah Job Divisi
@endsection

@push('addStyle')
    <style>
        .add_more {
            background-color: #f1f1f1;
            border: 1px dashed #ccc;
            border-radius: 0.25rem;

            cursor: pointer;
        }
    </style>
@endpush

@section('content')
    <ul class="steps steps-primary steps-counter my-4">
        <li class="step-item ">Form Akad</li>
        <li class="step-item active">Form Data</li>
        <li class="step-item ">Konfirmasi</li>
    </ul>
    <form action="{{ route('job.divisi-step2-store') }}" method="post">
        @csrf
        @foreach ($jenisData as $item)
            @php
                $item = str_replace(' ', '_', $item);
            @endphp
            @include("pages.Job.Divisi._form_$item")
        @endforeach

        <div class="d-flex justify-content-end mt-5 gap-4">
            <a href="{{ route('job.divisi.create') }}" class="btn btn-secondary">Kembali Form Akad</a>
            <button type="submit" class="btn btn-primary">Simpan & Lanjut</button>
        </div>

    </form>
@endsection
