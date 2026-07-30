@extends('layouts.admin')

@section('title', 'Edit Badan Hukum')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <!-- FORM UTAMA: UPDATE BADAN HUKUM -->
            <form action="{{ route('job.divisi-data-pendukung-badan-hukum.update') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $badanHukum->id }}">

                <!-- Card Utama Informasi Badan Hukum -->
                <div class="card shadow-sm border border-light-subtle mb-4 rounded-3">
                    <div class="card-header bg-light bg-opacity-50 py-3 border-bottom border-light-subtle">
                        <h5 class="mb-0 fw-bold text-dark">Informasi Badan Hukum</h5>
                    </div>
                    <div class="card-body pt-3">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold required">NAMA PT</label>
                                <input type="text" name="nama_pt"
                                    class="form-control @error('nama_pt') is-invalid @enderror"
                                    value="{{ old('nama_pt', $badanHukum->nama_pt) }}">
                                @error('nama_pt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold required">NPWP</label>
                                <input type="text" name="npwp"
                                    class="form-control @error('npwp') is-invalid @enderror"
                                    value="{{ old('npwp', $badanHukum->npwp) }}">
                                @error('npwp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold required">NIB</label>
                                <input type="text" name="nib" class="form-control @error('nib') is-invalid @enderror"
                                    value="{{ old('nib', $badanHukum->nib) }}">
                                @error('nib')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-bold required">NAMA DIREKTUR UTAMA</label>
                                <input type="text" name="nama_dirut"
                                    class="form-control @error('nama_dirut') is-invalid @enderror"
                                    value="{{ old('nama_dirut', $badanHukum->nama_dirut) }}">
                                @error('nama_dirut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-bold required">NO. TELEPON</label>
                                <input type="text" name="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', $badanHukum->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label text-secondary small fw-bold required">ALAMAT</label>
                                <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3">{{ old('alamat', $badanHukum->alamat) }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Dokumen & Lampiran -->
                <div class="card shadow-sm border border-light-subtle mb-4 rounded-3">
                    <div
                        class="card-header bg-light bg-opacity-50 py-3 border-bottom border-light-subtle d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-dark">Dokumen Lampiran</h5>
                        <span
                            class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle px-2 py-1">{{ $badanHukum->files->count() }}
                            File Tersimpan</span>
                    </div>
                    <div class="card-body pt-3">

                        <!-- Daftar File Lama (Desain Folder Modern) -->
                        @if ($badanHukum->files->count() > 0)
                            <div class="row g-2 mb-4">
                                @foreach ($badanHukum->files as $f)
                                    <div class="col-md-6">
                                        <div
                                            class="p-3 border border-light-subtle rounded-3 d-flex align-items-center justify-content-between bg-white shadow-sm transition-hover">
                                            <div class="d-flex align-items-center overflow-hidden me-2">
                                                <!-- Sentuhan Aksen Warna Folder Amber -->
                                                <div class="bg-warning bg-opacity-10 p-2 rounded border border-warning-subtle me-3 text-warning d-flex align-items-center justify-content-center flex-shrink-0"
                                                    style="width: 36px; height: 36px;">
                                                    <i class="bi bi-folder2-open" style="font-size: 1.1rem;"></i>
                                                </div>
                                                <div class="text-truncate">
                                                    <a href="{{ asset('storage/' . $f->file_path) }}" target="_blank"
                                                        class="fw-bold text-dark text-decoration-none d-block text-truncate small hover-primary">
                                                        {{ $f->file_name ?? 'Dokumen Terlampir' }}
                                                    </a>
                                                    <small class="text-muted" style="font-size: 0.75rem;">Diunggah
                                                        {{ $f->created_at->format('d M Y, H:i') }}</small>
                                                </div>
                                            </div>

                                            <!-- Tombol Hapus Inline Terpisah dari Form Utama -->
                                            <!-- Tombol Hapus Inline Terpisah dari Form Utama -->
                                            <button type="submit" form="delete-file-form-{{ $f->id }}"
                                                class="btn btn-sm btn-outline-danger border-0 rounded-circle p-2 confirm_delete"
                                                data-message="file lampiran {{ $f->file_name }}" title="Hapus file">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" viewBox="0 0 16 16">
                                                    <path
                                                        d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                                    <path fill-rule="evenodd"
                                                        d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Input Upload Multiple -->
                        <div
                            class="p-4 border border-2 border-dashed border-secondary border-opacity-25 rounded-3 text-center bg-light bg-opacity-25">
                            <label class="form-label fw-bold mb-1 cursor-pointer text-dark">Tambah File Baru</label>
                            <p class="text-muted small mb-3">Pilih beberapa file sekaligus untuk ditambahkan (tidak menimpa
                                file lama)</p>
                            <input type="file" name="files[]" class="form-control bg-white" multiple>
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
            <!-- END FORM UTAMA -->

            <!-- FORM HAPUS FILE TERPISAH -->
            @foreach ($badanHukum->files as $f)
                <form id="delete-file-form-{{ $f->id }}"
                    action="{{ route('job.divisi-data-pendukung-badan-hukum.file.destroy', $f->id) }}" method="POST"
                    class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach
        </div>
    </div>
    @push('addScript')
        <script>
            $(document).on("click", ".confirm_delete", function(e) {
                e.preventDefault();

                // Cek apakah tombol punya atribut "form", kalau ada pilih form ID itu, kalau tidak cari closest form
                let targetFormId = $(this).attr("form");
                let form = targetFormId ? $(`#${targetFormId}`) : $(this).closest("form");

                let message = $(this).data("message") || "data ini";

                Swal.fire({
                    title: "Apakah Anda yakin?",
                    text: `Anda akan menghapus ${message}!`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        </script>
    @endpush
@endsection
