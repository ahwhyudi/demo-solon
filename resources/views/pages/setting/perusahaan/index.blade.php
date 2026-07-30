@extends('layouts.admin')

@section('title')
    Profil Notaris
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap gap-3">
            <div class="container py-5">

                <div class="d-flex justify-content-end mb-3">
                    <a href="{{ $item ? route('setting.perusahaan.edit', $item->id) : route('setting.perusahaan.create') }}"
                        class="btn btn-outline-primary">

                        {!! $item ? '<i class="bi bi-pencil-square"></i>' : '<i class="bi bi-plus-square"></i>' !!}
                    </a>
                </div>

                <div class="row g-4 align-items-stretch">

                    <div class="col-lg-4 d-flex">
                        <div class="card w-100">
                            <div class="text-center">

                                <div class="mt-4">

                                    <div
                                        style="width: 150px; height: 150px; border-radius: 50%; overflow: hidden; margin: auto; border: 2px solid #30ff07;">

                                        @if ($item?->foto_profil)
                                            <img src="{{ Storage::url($item->foto_profil) }}" alt="avatar"
                                                style="width: 100%; height: 100%; object-fit: cover; object-position: center; display: block;">
                                        @else
                                            <div
                                                style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #f8f9fa; font-size: 20px; font-weight: bold; color: #6c757d;">
                                                Avatar
                                            </div>
                                        @endif

                                    </div>

                                    <h2 class="my-3">
                                        {{ $item?->nama_perusahaan ?? 'N/A' }}
                                    </h2>

                                </div>

                                <hr>

                                <div>
                                    <h3>SK Notaris</h3>

                                    <p class="text-muted mb-1">
                                        {{ $item?->skNotaris?->sk_kemenkumham ?? 'N/A' }}
                                    </p>

                                    <p class="text-muted mb-4">
                                        {{ $item?->skNotaris?->kota?->name ?? 'N/A' }}
                                    </p>

                                    @if ($item?->skNotaris?->file)
                                        <a href="{{ Storage::url($item->skNotaris->file) }}" target="_blank"
                                            class="text-primary text-decoration-none">
                                            Lihat SK Notaris
                                        </a>
                                    @else
                                        <span class="text-muted">
                                            Tidak ada file
                                        </span>
                                    @endif
                                </div>

                                <hr>

                                <div class="mb-4">
                                    <h3>SK PPAT</h3>

                                    <p class="text-muted mb-1">
                                        {{ $item?->skPpat?->sk_kemenkumham ?? 'N/A' }}
                                    </p>

                                    <p class="text-muted mb-4">
                                        {{ $item?->skPpat?->kota?->name ?? 'N/A' }}
                                    </p>

                                    @if ($item?->skPpat?->file)
                                        <a href="{{ Storage::url($item->skPpat->file) }}" target="_blank"
                                            class="text-primary text-decoration-none">
                                            Lihat SK PPAT
                                        </a>
                                    @else
                                        <span class="text-muted">
                                            Tidak ada file
                                        </span>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8 d-flex">
                        <div class="card w-100">
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Nama Lengkap</p>
                                    </div>

                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">
                                            {{ $item?->nama_perusahaan ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Email</p>
                                    </div>

                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">
                                            {{ $item?->email ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Phone</p>
                                    </div>

                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">
                                            {{ $item?->telepon ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Mobile</p>
                                    </div>

                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">
                                            {{ $item?->mobile ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Address</p>
                                    </div>

                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">
                                            {{ $item?->alamat ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Dokumen</p>
                                    </div>

                                    <div class="col-sm-9">

                                        @if ($item?->foto_ktp)
                                            <a href="{{ Storage::url($item->foto_ktp) }}" target="_blank"
                                                class="text-primary text-decoration-none">
                                                Lihat KTP
                                            </a>
                                        @else
                                            <span class="text-muted">
                                                Tidak ada file
                                            </span>
                                        @endif

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
