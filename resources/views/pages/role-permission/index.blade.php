@extends('layouts.admin')

@section('title')
    Role & Permission
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row justify-content-between mb-3">
                <div class="col-md-6">
                    <a href="{{ route('akses.role.create') }}" class="btn btn-primary">Tambah Data</a>
                </div>
                <div class="col-md-4">
                    <form action="">
                        <input type="text" placeholder="cari data" class="form-control" name="q"
                            value="{{ request()->get('q') }}">
                    </form>
                </div>
            </div>
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th scope="col">Nama</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roles as $item)
                        <tr>
                            <td>{{ $item->name }}</td>

                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('akses.role.edit', $item->id) }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-pen"></i>
                                    </a>

                                    {{-- <form action="{{ route('akses.role.destroy', $item->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <span class="btn btn-danger confirm_delete ms-2 btn-sm">
                                            <i class="bi bi-trash3"></i>
                                        </span>
                                    </form> --}}
                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="12">
                                <div class="text-center">Data tidak ada</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-end mt-4">
                {{ $roles->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
