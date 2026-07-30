@extends('layouts.admin')

@section('title')
    Edit Notaris
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('master-data.notaris.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('pages.MasterData.Notaris.form', ['item' => $item])
            </form>
        </div>
    </div>
@endsection
