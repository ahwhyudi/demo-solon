@extends('layouts.admin')

@section('title')
    Tambah Setting Step Ops
@endsection

@section('content')
    <div class="alert alert-info">
        Penambahan step baru sekarang dilakukan melalui popup di halaman daftar step.
    </div>

    <a href="{{ route('setting.step-ops.index', ['open-modal' => 'create']) }}" class="btn btn-primary">
        Buka Popup Tambah Step
    </a>
@endsection
