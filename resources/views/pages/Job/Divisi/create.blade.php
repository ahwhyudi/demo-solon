@extends('layouts.admin')

@section('title')
    Tambah Job Divisi
@endsection

@section('content')
    <ul class="steps steps-primary steps-counter my-4">
        <li class="step-item {{ !request()->get('step') ? 'active' : '' }}">Form Akad</li>
        <li class="step-item {{ (int) request()->get('step') === 2 ? 'active' : '' }}">Form Data</li>
        <li class="step-item {{ (int) request()->get('step') === 3 ? 'active' : '' }}">Konfirmasi</li>
    </ul>

    @if ((int) request()->get('step') === 2)
        @include('pages.Job.Divisi._Step2')
    @else
        @include('pages.Job.Divisi._Form_akad')
    @endif
@endsection
