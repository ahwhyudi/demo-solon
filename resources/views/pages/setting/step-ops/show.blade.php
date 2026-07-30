@extends('layouts.admin')

@section('title')
    Detail Setting Step Ops
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 220px;">Urutan</th>
                        <td>{{ $item->urutan }}</td>
                    </tr>
                    <tr>
                        <th>Nama Step</th>
                        <td>{{ $item->nama_step }}</td>
                    </tr>
                    <tr>
                        <th>Data Objek</th>
                        <td>{{ $item->data_objek ? 'Ya' : 'Tidak' }}</td>
                    </tr>
                    <tr>
                        <th>Penugasan Staff</th>
                        <td>{{ $item->penugasan_staff ? 'Ya' : 'Tidak' }}</td>
                    </tr>
                    <tr>
                        <th>Konfirmasi</th>
                        <td>{{ $item->konfirmasi ? 'Ya' : 'Tidak' }}</td>
                    </tr>
                    <tr>
                        <th>Dibuat</th>
                        <td>{{ $item->created_at?->format('d-m-Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Diupdate</th>
                        <td>{{ $item->updated_at?->format('d-m-Y H:i') }}</td>
                    </tr>
                </table>
            </div>

            <div class="d-flex gap-2 mt-3">
                <a href="{{ route('setting.step-ops.edit', $item->id) }}" class="btn btn-warning">Edit</a>
                <a href="{{ route('setting.step-ops.index') }}" class="btn btn-light">Kembali</a>
            </div>
        </div>
    </div>
@endsection
