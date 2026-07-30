@extends('layouts.admin')

@section('title', 'Edit Pembeli ' . $jobDivisi->kode)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-12">
        <!-- FORM 1: FORM UTAMA UPDATE PEMBELI -->
        <form
            action="{{ route('job.divisi-data-pendukung-pembeli.update') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf
            <input type="hidden" name="id" value="{{ $pembeli->id }}">

            <!-- Card Informasi Pembeli -->
            <div class="card shadow-sm border border-light-subtle mb-4 rounded-3">
                <div class="card-header bg-light bg-opacity-50 py-3 border-bottom border-light-subtle">
                    <h5 class="mb-0 fw-bold text-dark">Informasi Pembeli</h5>
                </div>
                <div class="card-body pt-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-bold required">Nama Lengkap</label>
                            <input
                                type="text"
                                name="nama"
                                class="form-control @error('nama') is-invalid @enderror"
                                value="{{ old('nama', $pembeli->nama) }}"
                            >
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-bold required">Nomor Telepon</label>
                            <input
                                type="number"
                                name="nomor_telepon"
                                class="form-control @error('nomor_telepon') is-invalid @enderror"
                                value="{{ old('nomor_telepon', $pembeli->nomor_telepon) }}"
                            >
                            @error('nomor_telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-bold required">Email</label>
                            <input
                                type="email"
                                name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $pembeli->email) }}"
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Dokumen & Lampiran -->
            <div class="card shadow-sm border border-light-subtle mb-4 rounded-3">
                <div class="card-header bg-light bg-opacity-50 py-3 border-bottom border-light-subtle d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-dark">Dokumen Lampiran</h5>
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle px-2 py-1">
                        {{ $pembeli->files->count() }} File Tersimpan
                    </span>
                </div>
                <div class="card-body pt-3">
                    <!-- Daftar File Lama -->
                    @if ($pembeli->files->count() > 0)
                        <div class="row g-2 mb-4">
                            @foreach ($pembeli->files as $f)
                                <div class="col-md-6">
                                    <div class="p-3 border border-light-subtle rounded-3 d-flex align-items-center justify-content-between bg-white shadow-sm transition-hover">
                                        <div class="d-flex align-items-center overflow-hidden me-2">
                                            <div class="bg-primary bg-opacity-10 p-2 rounded border border-primary-subtle me-3 text-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z" />
                                                </svg>
                                            </div>
                                            <div class="text-truncate">
                                                <a href="{{ asset('storage/' . $f->file_path) }}" target="_blank"
                                                    class="fw-bold text-dark text-decoration-none d-block text-truncate small hover-primary">
                                                    {{ $f->file_name ?? 'Dokumen Terlampir' }}
                                                </a>
                                                <small class="text-muted" style="font-size: 0.75rem;">Diunggah {{ $f->created_at->format('d M Y, H:i') }}</small>
                                            </div>
                                        </div>

                                        <!-- Tombol Hapus Terhubung ke Form di Bawah -->
                                        <button type="button" form="delete-file-form-pembeli-{{ $f->id }}"
                                            class="btn btn-sm btn-outline-danger border-0 rounded-circle p-2 confirm-delete-file"
                                            data-message="file lampiran {{ $f->file_name }}" title="Hapus file">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Input Upload Baru (Multiple) -->
                    <div class="p-4 border border-2 border-dashed border-secondary border-opacity-25 rounded-3 text-center bg-light bg-opacity-25">
                        <label class="form-label fw-bold mb-1 cursor-pointer text-dark">Tambah File Baru</label>
                        <p class="text-muted small mb-3">Pilih beberapa file sekaligus untuk ditambahkan (tidak menimpa file lama)</p>
                        <input type="file" name="files[]" class="form-control bg-white @error('files.*') is-invalid @enderror" multiple>
                        @error('files.*')
                            <div class="invalid-feedback text-start">{{ $message }}</div>
                        @enderror
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
        <!-- END FORM 1 -->

        <!-- FORM 2: FORM HAPUS FILE (DI LUAR FORM UTAMA) -->
        @if ($pembeli->files->count() > 0)
            @foreach ($pembeli->files as $f)
                <form id="delete-file-form-pembeli-{{ $f->id }}"
                    action="{{ route('job.divisi-data-pendukung-pembeli.file.destroy', $f->id) }}" method="POST"
                    class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach
        @endif
        <!-- END FORM 2 -->
    </div>
</div>
@endsection

@push('addScript')
    <script>
        document.querySelectorAll('.confirm-delete-file').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();

                const formId = this.getAttribute('form');
                const targetForm = document.getElementById(formId);
                const message = this.getAttribute('data-message') || 'file ini';

                if (!targetForm) return;

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Hapus Dokumen?',
                        text: 'Anda yakin ingin menghapus ' + message + '?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            targetForm.submit();
                        }
                    });
                } else {
                    if (confirm('Anda yakin ingin menghapus ' + message + '?')) {
                        targetForm.submit();
                    }
                }
            });
        });
    </script>
@endpush