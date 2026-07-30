@extends('layouts.admin')

@section('title')
    Masukan Profil Notaris
@endsection

@section('content')
    <div class="">
        <div class="">
            <form action="{{ route('setting.perusahaan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('pages.setting.perusahaan._form', [
                    'item' => null,
                    'submitLabel' => 'Simpan',
                ])
            </form>
        </div>
    </div>
@endsection
