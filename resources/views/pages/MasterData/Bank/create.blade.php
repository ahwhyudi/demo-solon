@extends('layouts.admin')

@section('title')
    Tambah Data Bank
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('master-data.bank.store') }}" method="post">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="parent_id" class="form-label">Parent</label>
                        <select name="parent_id" id="parent_id" class="form-select select2"
                            data-placeholder="Pilih Parent Bank">
                            <option value=""></option>
                            @foreach ($bank as $item)
                                <option value="{{ $item->id }}" {{ old('parent_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="nama" class="form-label required">Nama Bank</label>
                        <input type="text" name="nama" id="nama" class="form-control"
                            value="{{ old('nama') }}">
                    </div>

                    <div class="col-md-6">
                        <label for="nama_pimpinan_sekarang" class="form-label required">Nama Pimpinan Sekarang</label>
                        <input type="text" name="nama_pimpinan_sekarang" id="nama_pimpinan_sekarang" class="form-control"
                            value="{{ old('nama_pimpinan_sekarang') }}">
                    </div>

                    <div class="col-md-6">
                        <label for="nama_pimpinan_selanjutnya" class="form-label">Nama Pimpinan Selanjutnya</label>
                        <input type="text" name="nama_pimpinan_selanjutnya" id="nama_pimpinan_selanjutnya"
                            class="form-control" value="{{ old('nama_pimpinan_selanjutnya') }}">
                    </div>

                    <div class="col-md-6">
                        <label for="start_kemitraan" class="form-label">Start Kemitraan</label>
                        <input type="date" name="start_kemitraan" id="start_kemitraan" class="form-control"
                            value="{{ old('start_kemitraan') }}">
                    </div>

                    <div class="col-md-6">
                        <label for="end_kemitraan" class="form-label">End Kemitraan</label>
                        <input type="date" name="end_kemitraan" id="end_kemitraan" class="form-control"
                            value="{{ old('end_kemitraan') }}">
                    </div>
                </div>

                <div class="mt-3">
                    @include('pages.MasterData.Bank._add_kepala_legal_bank')
                </div>
                <div class="mt-3">
                    @include('pages.MasterData.Bank._add_legal_bank')
                </div>
                <div class="mt-3">
                    @include('pages.MasterData.Bank._add_kepala_marketing_bank')
                </div>
                <div class="mt-3">
                    @include('pages.MasterData.Bank._add_marketing_bank')
                </div>

                <button type="submit" class="btn btn-primary mt-4">Simpan</button>
            </form>
        </div>
    </div>
@endsection
