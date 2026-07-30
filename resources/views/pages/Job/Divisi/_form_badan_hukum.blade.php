@can('data-pendukung/badan-hukum/view')
    <div class="mb-5">
        @if (count($jobDivisi->badanHukum) > 0)
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold text-dark mb-0">
                    <i class="bi bi-building-fill text-primary me-2"></i>Daftar Badan Hukum (PT / CV)
                </h6>
            </div>

            <!-- Card Table Modern Badan Hukum -->
            <div class="card border border-light-subtle shadow-sm rounded-3 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-nowrap">
                        <thead class="bg-light bg-opacity-50 text-secondary small fw-bold border-bottom border-light-subtle">
                            <tr>
                                <th class="py-3 ps-4">NAMA PT & DIRUT</th>
                                <th class="py-3">LEGALITAS (NPWP / NIB)</th>
                                <th class="py-3">KONTAK & ALAMAT</th>
                                <th class="py-3">LAMPIRAN DOKUMEN</th>
                                <th class="py-3 text-end pe-4">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @foreach ($jobDivisi->badanHukum as $index => $item)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $item->nama_pt }}</div>
                                        <small class="text-muted" style="font-size: 0.75rem;">Dirut: {{ $item->nama_dirut }}</small>
                                    </td>
                                    <td>
                                        <div class="small text-dark"><strong>NPWP:</strong> {{ $item->npwp ?? '-' }}</div>
                                        <div class="small text-muted"><strong>NIB:</strong> {{ $item->nib ?? '-' }}</div>
                                    </td>
                                    <td>
                                        <div class="small text-dark"><strong>Telp:</strong> {{ $item->phone ?? '-' }}</div>
                                        <div class="small text-muted text-truncate" style="max-width: 180px;"
                                            title="{{ $item->alamat }}"><strong>Alamat:</strong> {{ $item->alamat ?? '-' }}</div>
                                    </td>
                                    <td>
                                        @if ($item->files && $item->files->count() > 0)
                                            <!-- Grid container berukuran kompak agar rapi di dalam tabel -->
                                            <div class="row g-1" style="max-width: 320px;">
                                                @foreach ($item->files as $f)
                                                    <div class="col-6">
                                                        <a href="{{ asset('storage/' . $f->file_path) }}" target="_blank"
                                                            class="d-flex align-items-center justify-content-between p-2 rounded-2 bg-white border border-light-subtle text-decoration-none shadow-sm h-100 transition-hover"
                                                            title="Buka file: {{ $f->file_name }}">
                                                            <div class="d-flex align-items-center overflow-hidden me-1">
                                                                <!-- Aksen Kotak Warna Folder Amber -->
                                                                <div class="bg-warning bg-opacity-10 p-1 rounded me-2 border border-warning-subtle d-flex align-items-center justify-content-center flex-shrink-0"
                                                                    style="width: 24px; height: 24px;">
                                                                    <i class="bi bi-file-earmark-text text-warning"
                                                                        style="font-size: 0.8rem;"></i>
                                                                </div>
                                                                <span class="text-truncate text-dark fw-medium"
                                                                    style="font-size: 0.75rem;">
                                                                    {{ $f->file_name ?? 'Dokumen' }}
                                                                </span>
                                                            </div>
                                                            <i class="bi bi-box-arrow-up-right text-muted flex-shrink-0"
                                                                style="font-size: 0.65rem;"></i>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted small fst-italic">
                                                <i class="bi bi-slash-circle me-1"></i>Tidak ada file
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-inline-flex gap-1">
                                            @can('data-pendukung/badan-hukum/edit')
                                                <a href="{{ route('job.divisi-data-pendukung-badan-hukum.edit', $item->id) }}"
                                                    class="btn btn-sm btn-outline-secondary border-0 rounded-circle p-2"
                                                    title="Edit Badan Hukum">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" viewBox="0 0 16 16">
                                                        <path
                                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                        <path fill-rule="evenodd"
                                                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                    </svg>
                                                </a>
                                            @endcan
                                            @can('data-pendukung/badan-hukum/delete')
                                                <form action="{{ route('job.divisi-data-pendukung-badan-hukum.delete', $item->id) }}"
                                                    method="POST" class="confirm_delete d-inline"
                                                    data-message="data Badan Hukum {{ $item->nama_pt }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-danger border-0 rounded-circle p-2"
                                                        title="Hapus Badan Hukum">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            fill="currentColor" viewBox="0 0 16 16">
                                                            <path
                                                                d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                                            <path fill-rule="evenodd"
                                                                d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <!-- Empty State Modern -->
            <div class="card border border-light-subtle shadow-sm rounded-3 p-5 text-center bg-light bg-opacity-25">
                <div class="py-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex p-3 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                            class="bi bi-building-fill" viewBox="0 0 16 16">
                            <path d="M3 0a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h3v-3.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V16h3a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1H3zm1 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3.5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5zM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3.5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5zM4 8.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3.5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5z"/>
                        </svg>
                    </div>
                    <h6 class="fw-bold text-dark">Belum Ada Data Badan Hukum</h6>
                    <p class="text-muted small mb-0">Silakan tambahkan data badan hukum dan dokumen lampirannya.</p>
                </div>
            </div>
        @endif
    </div>
@endcan

@can('data-pendukung/badan-hukum/create')
    @if (!count($jobDivisi->badanHukum))
        <form action="{{ route('job.divisi-data-pendukung.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="job_divisi_id" value="{{ $jobDivisi->id }}">

            <!-- Card Form Tambah Baru yang Konsisten -->
            <div class="card shadow-sm border border-light-subtle mb-4 rounded-3">
                <div class="card-header bg-light bg-opacity-50 py-3 border-bottom border-light-subtle">
                    <h6 class="mb-0 fw-bold text-dark">Tambah Data Badan Hukum</h6>
                </div>
                <div class="card-body pt-3">
                    @php
                        $oldObjek =
                            old('badan_hukum') ??
                            (session('form_data.badan_hukum') ?? [
                                [
                                    'nama_pt' => '',
                                    'npwp' => '',
                                    'nib' => '',
                                    'nama_dirut' => '',
                                    'alamat' => '',
                                    'phone' => '',
                                ],
                            ]);
                    @endphp

                    @foreach ($oldObjek as $idx => $o)
                        <div class="p-3 bg-white border border-light-subtle rounded-3 mb-3 shadow-sm">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold required">NAMA PT</label>
                                    <input type="text"
                                        class="form-control @error("badan_hukum.$idx.nama_pt") is-invalid @enderror"
                                        name="badan_hukum[{{ $idx }}][nama_pt]"
                                        value="{{ old("badan_hukum.$idx.nama_pt", $o['nama_pt'] ?? '') }}">
                                    @error("badan_hukum.$idx.nama_pt")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold required">NPWP</label>
                                    <input type="text"
                                        class="form-control @error("badan_hukum.$idx.npwp") is-invalid @enderror"
                                        name="badan_hukum[{{ $idx }}][npwp]"
                                        value="{{ old("badan_hukum.$idx.npwp", $o['npwp'] ?? '') }}">
                                    @error("badan_hukum.$idx.npwp")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold required">NIB</label>
                                    <input type="text"
                                        class="form-control @error("badan_hukum.$idx.nib") is-invalid @enderror"
                                        name="badan_hukum[{{ $idx }}][nib]"
                                        value="{{ old("badan_hukum.$idx.nib", $o['nib'] ?? '') }}">
                                    @error("badan_hukum.$idx.nib")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-secondary small fw-bold required">NAMA DIREKTUR
                                        UTAMA</label>
                                    <input type="text"
                                        class="form-control @error("badan_hukum.$idx.nama_dirut") is-invalid @enderror"
                                        name="badan_hukum[{{ $idx }}][nama_dirut]"
                                        value="{{ old("badan_hukum.$idx.nama_dirut", $o['nama_dirut'] ?? '') }}">
                                    @error("badan_hukum.$idx.nama_dirut")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-secondary small fw-bold required">NO. TELEPON</label>
                                    <input type="text"
                                        class="form-control @error("badan_hukum.$idx.phone") is-invalid @enderror"
                                        name="badan_hukum[{{ $idx }}][phone]"
                                        value="{{ old("badan_hukum.$idx.phone", $o['phone'] ?? '') }}">
                                    @error("badan_hukum.$idx.phone")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label text-secondary small fw-bold required">ALAMAT</label>
                                    <textarea class="form-control @error("badan_hukum.$idx.alamat") is-invalid @enderror"
                                        name="badan_hukum[{{ $idx }}][alamat]" rows="2">{{ old("badan_hukum.$idx.alamat", $o['alamat'] ?? '') }}</textarea>
                                    @error("badan_hukum.$idx.alamat")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Input Multiple Upload untuk Lampiran Baru -->
                                <div class="col-md-12 mt-3">
                                    <div
                                        class="p-3 border border-dashed border-secondary border-opacity-25 rounded-3 bg-light bg-opacity-25">
                                        <label class="form-label text-secondary small fw-bold mb-1">LAMPIRAN DOKUMEN BADAN
                                            HUKUM</label>
                                        <p class="text-muted small mb-2">Unggah dokumen legalitas (Akta, NIB, NPWP)
                                            sekaligus.</p>
                                        <input type="file" name="badan_hukum[{{ $idx }}][files][]"
                                            class="form-control bg-white" multiple>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if ($jobDivisi->status !== 'Batal Akad')
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">Simpan Badan
                                Hukum</button>
                        </div>
                    @endif
                </div>
            </div>
        </form>
    @endif
@endcan
