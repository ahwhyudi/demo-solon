@extends('layouts.admin')

@section('title', 'Edit Objek ' . $jobDivisi->kode)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-12">
        <!-- FORM 1: FORM UTAMA UPDATE OBJEK -->
        <form
            action="{{ route('job.divisi-data-pendukung-objek.update') }}"
            method="POST"
            enctype="multipart/form-data"
            id="form_update"
        >
            @csrf
            <input type="hidden" name="id" value="{{ $objek->id }}">
            <input type="hidden" name="job_divisi_id" value="{{ $jobDivisi->id }}">

            <!-- Card Informasi Objek -->
            <div class="card shadow-sm border border-light-subtle mb-4 rounded-3">
                <div class="card-header bg-light bg-opacity-50 py-3 border-bottom border-light-subtle">
                    <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-geo-alt-fill me-2 text-primary"></i>Form Data Objek</h5>
                </div>
                <div class="card-body pt-3">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label text-secondary small fw-bold required">DESA</label>
                            <select name="desa_id" class="form-select select2 @error('desa_id') is-invalid @enderror">
                                <option value="">Pilih Desa</option>
                                @foreach ($desa as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('desa_id', $objek->desa_id) == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}, {{ $item->kecamatan->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('desa_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-secondary small fw-bold required">JENIS SERTIFIKAT</label>
                            <select name="jenis_sertifikat" class="form-select @error('jenis_sertifikat') is-invalid @enderror">
                                <option value="" disabled>Pilih Jenis Sertifikat</option>
                                <option value="SHM" {{ old('jenis_sertifikat', $objek->jenis_sertifikat) == 'SHM' ? 'selected' : '' }}>SHM - Sertifikat Hak Milik</option>
                                <option value="HGB" {{ old('jenis_sertifikat', $objek->jenis_sertifikat) == 'HGB' ? 'selected' : '' }}>HGB - Hak Guna Bangunan</option>
                                <option value="HGU" {{ old('jenis_sertifikat', $objek->jenis_sertifikat) == 'HGU' ? 'selected' : '' }}>HGU - Hak Guna Usaha</option>
                                <option value="HAK PAKAI" {{ old('jenis_sertifikat', $objek->jenis_sertifikat) == 'HAK PAKAI' ? 'selected' : '' }}>HAK PAKAI</option>
                                <option value="HPL" {{ old('jenis_sertifikat', $objek->jenis_sertifikat) == 'HPL' ? 'selected' : '' }}>HPL - Hak Pengelolaan</option>
                                <option value="HMSRS" {{ old('jenis_sertifikat', $objek->jenis_sertifikat) == 'HMSRS' ? 'selected' : '' }}>HMSRS - Hak Milik Satuan Rumah Susun</option>
                                <option value="SHSRS" {{ old('jenis_sertifikat', $objek->jenis_sertifikat) == 'SHSRS' ? 'selected' : '' }}>SHSRS - Sertifikat Hak Satuan Rumah Susun</option>
                                <option value="SERTIFIKAT WAKAF" {{ old('jenis_sertifikat', $objek->jenis_sertifikat) == 'SERTIFIKAT WAKAF' ? 'selected' : '' }}>SERTIFIKAT WAKAF</option>
                                <option value="HAK TANGGUNGAN" {{ old('jenis_sertifikat', $objek->jenis_sertifikat) == 'HAK TANGGUNGAN' ? 'selected' : '' }}>HAK TANGGUNGAN</option>
                                <option value="SERTIFIKAT ROYA" {{ old('jenis_sertifikat', $objek->jenis_sertifikat) == 'SERTIFIKAT ROYA' ? 'selected' : '' }}>SERTIFIKAT ROYA</option>
                                <option value="GIRIK" {{ old('jenis_sertifikat', $objek->jenis_sertifikat) == 'GIRIK' ? 'selected' : '' }}>GIRIK</option>
                                <option value="LETTER C" {{ old('jenis_sertifikat', $objek->jenis_sertifikat) == 'LETTER C' ? 'selected' : '' }}>LETTER C</option>
                                <option value="PETOK D" {{ old('jenis_sertifikat', $objek->jenis_sertifikat) == 'PETOK D' ? 'selected' : '' }}>PETOK D</option>
                                <option value="VERPONDING" {{ old('jenis_sertifikat', $objek->jenis_sertifikat) == 'VERPONDING' ? 'selected' : '' }}>VERPONDING</option>
                                <option value="KOHIR" {{ old('jenis_sertifikat', $objek->jenis_sertifikat) == 'KOHIR' ? 'selected' : '' }}>KOHIR</option>
                                <option value="PATOK" {{ old('jenis_sertifikat', $objek->jenis_sertifikat) == 'PATOK' ? 'selected' : '' }}>PATOK</option>
                                <option value="BPKB" {{ old('jenis_sertifikat', $objek->jenis_sertifikat) == 'BPKB' ? 'selected' : '' }}>BPKB</option>
                                <option value="IMB" {{ old('jenis_sertifikat', $objek->jenis_sertifikat) == 'IMB' ? 'selected' : '' }}>IMB - Izin Mendirikan Bangunan</option>
                                <option value="PBG" {{ old('jenis_sertifikat', $objek->jenis_sertifikat) == 'PBG' ? 'selected' : '' }}>PBG - Persetujuan Bangunan Gedung</option>
                                <option value="SK" {{ old('jenis_sertifikat', $objek->jenis_sertifikat) == 'SK' ? 'selected' : '' }}>SK - Surat Keputusan</option>
                                <option value="SURAT UKUR" {{ old('jenis_sertifikat', $objek->jenis_sertifikat) == 'SURAT UKUR' ? 'selected' : '' }}>SURAT UKUR</option>
                                <option value="SPPT PBB" {{ old('jenis_sertifikat', $objek->jenis_sertifikat) == 'SPPT PBB' ? 'selected' : '' }}>SPPT PBB</option>
                                <option value="BPHTB" {{ old('jenis_sertifikat', $objek->jenis_sertifikat) == 'BPHTB' ? 'selected' : '' }}>BPHTB</option>
                                <option value="SSB" {{ old('jenis_sertifikat', $objek->jenis_sertifikat) == 'SSB' ? 'selected' : '' }}>SSB - Surat Setoran Bea</option>
                            </select>
                            @error('jenis_sertifikat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-secondary small fw-bold required">NO. SERTIFIKAT</label>
                            <input type="text" class="form-control @error('no_sertifikat') is-invalid @enderror"
                                name="no_sertifikat" value="{{ old('no_sertifikat', $objek->no_sertifikat) }}" placeholder="Masukkan nomor sertifikat">
                            @error('no_sertifikat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-secondary small fw-bold required">NAMA PEMILIK</label>
                            <input type="text" class="form-control @error('nama_pemilik') is-invalid @enderror"
                                name="nama_pemilik" value="{{ old('nama_pemilik', $objek->nama_pemilik) }}" placeholder="Masukkan nama pemilik">
                            @error('nama_pemilik')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-secondary small fw-bold">LUAS TANAH</label>
                            <input type="text" class="form-control @error('luas_tanah') is-invalid @enderror"
                                name="luas_tanah" value="{{ old('luas_tanah', $objek->luas_tanah) }}" placeholder="Contoh: 150 m2">
                            @error('luas_tanah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-secondary small fw-bold">NO. SPPT</label>
                            <input type="text" class="form-control @error('no_sppt') is-invalid @enderror"
                                name="no_sppt" value="{{ old('no_sppt', $objek->no_sppt) }}" placeholder="Masukkan nomor SPPT">
                            @error('no_sppt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-secondary small fw-bold">PBB</label>
                            <input type="text" class="form-control @error('pbb') is-invalid @enderror"
                                name="pbb" value="{{ old('pbb', $objek->pbb) }}" placeholder="Masukkan nilai PBB">
                            @error('pbb')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-secondary small fw-bold">NJOP</label>
                            <input type="text" class="form-control @error('njop') is-invalid @enderror"
                                name="njop" value="{{ old('njop', $objek->njop) }}" placeholder="Masukkan nilai NJOP">
                            @error('njop')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-secondary small fw-bold">NIB</label>
                            <input type="text" class="form-control @error('nib') is-invalid @enderror"
                                name="nib" value="{{ old('nib', $objek->nib) }}" placeholder="Masukkan NIB">
                            @error('nib')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label text-secondary small fw-bold required">ALAMAT</label>
                            <input type="text" value="{{ old('alamat', $objek->alamat) }}"
                                class="form-control @error('alamat') is-invalid @enderror" name="alamat" placeholder="Masukkan alamat lengkap objek">
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if ($dataPendukungObjek)
                            @if (in_array('nilai_ht', $dataPendukungObjek))
                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold">NILAI HT</label>
                                    <input type="text" class="form-control money @error('nilai_ht') is-invalid @enderror"
                                        name="nilai_ht"
                                        value="{{ old('nilai_ht', $objek->nilai_ht ? number_format($objek->nilai_ht, 0, ',', '.') : '') }}" placeholder="0">
                                    @error('nilai_ht')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            @if (in_array('nilai_transaksi', $dataPendukungObjek))
                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold">NILAI TRANSAKSI</label>
                                    <input type="text"
                                        class="form-control money @error('nilai_transaksi') is-invalid @enderror"
                                        name="nilai_transaksi"
                                        value="{{ old('nilai_transaksi', $objek->nilai_transaksi ? number_format($objek->nilai_transaksi, 0, ',', '.') : '') }}" placeholder="0">
                                    @error('nilai_transaksi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            @if (in_array('nilai_plafond', $dataPendukungObjek))
                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold">NILAI PLAFOND</label>
                                    <input type="text"
                                        class="form-control money @error('nilai_plafond') is-invalid @enderror"
                                        name="nilai_plafond"
                                        value="{{ old('nilai_plafond', $objek->nilai_plafond ? number_format($objek->nilai_plafond, 0, ',', '.') : '') }}" placeholder="0">
                                    @error('nilai_plafond')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Card Dokumen & Lampiran -->
            <div class="card shadow-sm border border-light-subtle mb-4 rounded-3">
                <div class="card-header bg-light bg-opacity-50 py-3 border-bottom border-light-subtle d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-dark">Dokumen Lampiran</h5>
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle px-2 py-1">
                        {{ $objek->files->count() }} File Tersimpan
                    </span>
                </div>
                <div class="card-body pt-3">
                    <!-- Daftar File Lama -->
                    @if ($objek->files->count() > 0)
                        <div class="row g-2 mb-4">
                            @foreach ($objek->files as $f)
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
                                        <button type="button" form="delete-file-form-objek-{{ $f->id }}"
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
            <div class="d-flex justify-content-end gap-2 mb-5">
                <a href="{{ route('job.divisi.show', $jobDivisi->id) }}#tabs-data-pendukung"
                    class="btn btn-light px-4 border">Batal</a>
                @if ($jobDivisi->status !== 'Batal Akad')
                    <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">Simpan Perubahan</button>
                @endif
            </div>
        </form>
        <!-- END FORM 1 -->

        <!-- FORM 2: FORM HAPUS FILE (DI LUAR FORM UTAMA) -->
        @if ($objek->files->count() > 0)
            @foreach ($objek->files as $f)
                <form id="delete-file-form-objek-{{ $f->id }}"
                    action="{{ route('job.divisi-data-pendukung-objek.file.destroy', $f->id) }}" method="POST"
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
        $(document).ready(function() {
            $("#form_update").on("submit", function() {
                $(".loading__global").show();
            });
        });

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