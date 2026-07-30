@extends('layouts.admin')

@section('title')
    Edit Profil Notaris
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('setting.perusahaan.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('pages.setting.perusahaan._form', [
                    'item' => $item,
                    'submitLabel' => 'Update',
                ])
            </form>
        </div>
    </div>
@endsection
