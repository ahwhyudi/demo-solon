@extends('layouts.admin')

@section('title')
    Pendirian Lembaga {{ $jobDivisi->kode }}
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <form action="{{ route('job.divisi-data-pendukung-pendirian-lembaga.update') }}" method="POST"
                id="form_pendirian_lembaga">
                @csrf
                <input type="hidden" name="id" value="{{ $pendirianLembaga->id }}">

                <!-- Card Informasi Pendirian Lembaga -->
                <div class="card shadow-sm border border-light-subtle mb-4 rounded-3">
                    <div class="card-header bg-light bg-opacity-50 py-3 border-bottom border-light-subtle">
                        <h5 class="mb-0 fw-bold text-dark">Informasi Pendirian Lembaga</h5>
                    </div>
                    <div class="card-body pt-3">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-bold required">Nama Lembaga</label>
                                <input type="text" name="nama_lembaga" class="form-control @error('nama_lembaga') is-invalid @enderror"
                                    value="{{ old('nama_lembaga', $pendirianLembaga->nama_lembaga) }}">
                                @error('nama_lembaga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-bold required">Bidang Usaha</label>
                                <input type="text" name="bidang_usaha" class="form-control @error('bidang_usaha') is-invalid @enderror"
                                    value="{{ old('bidang_usaha', $pendirianLembaga->bidang_usaha) }}">
                                @error('bidang_usaha')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-bold required">Modal Dasar</label>
                                <input type="text" name="modal_dasar" class="form-control money @error('modal_dasar') is-invalid @enderror"
                                    value="{{ old('modal_dasar', number_format($pendirianLembaga->modal_dasar, 0, ',', '.')) }}">
                                @error('modal_dasar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-bold required">Modal Setor</label>
                                <input type="text" name="modal_setor" class="form-control money @error('modal_setor') is-invalid @enderror"
                                    value="{{ old('modal_setor', number_format($pendirianLembaga->modal_setor, 0, ',', '.')) }}">
                                @error('modal_setor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label text-secondary small fw-bold required">Pemegang Saham</label>
                                <textarea name="pemegang_saham" rows="3" class="form-control @error('pemegang_saham') is-invalid @enderror">{{ old('pemegang_saham', $pendirianLembaga->pemegang_saham) }}</textarea>
                                @error('pemegang_saham')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-bold">Jajaran Direksi</label>
                                <textarea name="jajaran_direksi" rows="3" class="form-control @error('jajaran_direksi') is-invalid @enderror">{{ old('jajaran_direksi', $pendirianLembaga->jajaran_direksi) }}</textarea>
                                @error('jajaran_direksi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-bold">Jajaran Komisaris</label>
                                <textarea name="jajaran_komisaris" rows="3" class="form-control @error('jajaran_komisaris') is-invalid @enderror">{{ old('jajaran_komisaris', $pendirianLembaga->jajaran_komisaris) }}</textarea>
                                @error('jajaran_komisaris')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label text-secondary small fw-bold required">Alamat</label>
                                <textarea name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $pendirianLembaga->alamat) }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('job.divisi.show', $jobDivisi->id) }}#tabs-data-pendukung"
                        class="btn btn-light px-4 border">Batal</a>
                    @if ($jobDivisi->status !== 'Batal Akad')
                        <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">Simpan Perubahan</button>
                    @endif
                </div>

            </form>
        </div>
    </div>

    @push('addScript')
        <script>
            $('.money').mask('#.##0', {
                reverse: true
            });
        </script>
    @endpush
@endsection