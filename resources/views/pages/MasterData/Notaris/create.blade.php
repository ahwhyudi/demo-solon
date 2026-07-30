@extends('layouts.admin')

@section('title')
    Tambah Notaris
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('master-data.notaris.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('pages.MasterData.Notaris.form')
            </form>
        </div>
    </div>
@endsection
