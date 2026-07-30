@extends('layouts.admin')

@section('title')
    Tambah Data Role Permission
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('akses.role.store') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <label for="" class="form-label">Nama Role</label>
                        <input type="text" class="form-control" required name="name" placeholder="nama role">
                    </div>

                    @php
                        $allPermission = getAllPermission();
                    @endphp

                    @foreach ($allPermission as $key => $item)
                        <div class="col-md-4 mt-4">
                            <div class="card">
                                <div class="card-header">Menu {{ $key }}</div>
                                <div class="card-body">
                                    @foreach ($permissions as $itemPerm)
                                        @if (in_array($itemPerm->name, $item))
                                            <div class="custom-control custom-checkbox mb-2">
                                                <input type="checkbox" class="custom-control-input" name="permissions[]"
                                                    value="{{ $itemPerm->id }}" id="{{ $itemPerm->name }}">
                                                <label class="custom-control-label"
                                                    for="{{ $itemPerm->name }}">{{ $itemPerm->name }}</label>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="col-12">
                        <button type="submit" class="mt-3 btn btn-primary">Submit Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('addScript')
    <script></script>
@endpush
