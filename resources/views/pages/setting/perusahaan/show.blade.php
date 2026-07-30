@extends('layouts.admin')

@section('title')
    Detail Setting Perusahaan
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="border rounded p-3 h-100 d-flex align-items-center justify-content-center">
                        @if ($item->logo)
                            <img src="{{ asset('storage/' . $item->logo) }}" alt="Logo perusahaan" class="img-fluid"
                                style="max-height: 220px;">
                        @else
                            <span class="text-muted">Logo belum diupload</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 220px;">Nama Perusahaan</th>
                                <td>{{ $item->nama_perusahaan }}</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>{{ $item->alamat ?: '-' }}</td>
                            </tr>
                            <tr>
                                <th>Telepon</th>
                                <td>{{ $item->telepon ?: '-' }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $item->email ?: '-' }}</td>
                            </tr>
                            <tr>
                                <th>Nomor SK</th>
                                <td>{{ $item->nomo_sk ?: '-' }}</td>
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
                        <a href="{{ route('setting.perusahaan.edit', $item->id) }}" class="btn btn-warning">Edit</a>
                        <a href="{{ route('setting.perusahaan.index') }}" class="btn btn-light">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
