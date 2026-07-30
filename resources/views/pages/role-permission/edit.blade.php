@extends('layouts.admin')

@section('title')
    Edit Role {{ $itemRol->name }}
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('akses.role.update', $itemRol->id) }}" method="post">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-12">
                        <label for="" class="form-label">Nama</label>
                        <input type="text" class="form-control" name="name" value="{{ $itemRol->name }}">
                    </div>

                    @php
                        $allPermission = getAllPermission();

                    @endphp
                    

                    @foreach ($allPermission as $key => $item)
                        <div class="col-md-4 mt-4">
                            <div class="card">
                                <div class="card-header">Menu {{ ucwords(str_replace('/', ' ', $key)) }}</div>
                                <div class="card-body">
                                    @foreach ($permissions as $itemPerm)
                                        @if (in_array($itemPerm->name, $item))
                                            <div class="custom-control custom-checkbox mb-2">
                                                <input type="checkbox" class="custom-control-input"
                                                    {{ $itemRol->permissions->contains('name', $itemPerm->name) ? 'checked' : '' }}
                                                    name="permissions[]" value="{{ $itemPerm->id }}"
                                                    id="perm-{{ $itemPerm->id }}">
                                                <label class="custom-control-label"
                                                    for="perm-{{ $itemPerm->id }}">{{ $itemPerm->name }}</label>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>  
                    @endforeach


                    <div class="col-md-12 mt-3">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
