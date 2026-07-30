@extends('layouts.admin')

@section('title')
    Profil Notaris
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title">Data Identitas Notaris</h3>
                <div class="text-secondary">Menu ini menyimpan satu profil notaris untuk kantor.</div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ $item ? route('master-data.notaris.update', $item->id) : route('master-data.notaris.store') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @if ($item)
                    @method('PUT')
                @endif

                @include('pages.MasterData.Notaris.form', ['item' => $item])
            </form>
        </div>
    </div>
@endsection
