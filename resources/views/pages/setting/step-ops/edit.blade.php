@extends('layouts.admin')

@section('title')
    Edit Setting Step Ops
@endsection

@section('content')
    <form action="{{ route('setting.step-ops.update', $item->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('pages.setting.step-ops._sortable-form', [
            'item' => $item,
            'items' => $items,
            'submitLabel' => 'Update',
            'formKey' => 'edit_' . $item->id,
            'formType' => 'edit-step-ops',
            'isModal' => false,
        ])
    </form>
@endsection
