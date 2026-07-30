<div class="mb-5">
        @if (count($listObjek) > 0)
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold text-dark mb-0">
                    <i class="bi bi-geo-alt-fill text-primary me-2"></i>Daftar Objek Terdaftar
                </h6>
            </div>

            <!-- Card Table Modern Objek -->
            <div class="card border border-light-subtle shadow-sm rounded-3 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-nowrap">
                        <thead class="bg-light bg-opacity-50 text-secondary small fw-bold border-bottom border-light-subtle">
                            <tr>
                                <th class="py-3 ps-4">PROVINSI</th>
                                <th class="py-3">KOTA</th>
                                <th class="py-3">KECAMATAN</th>
                                <th class="py-3">DESA</th>
                                <th class="py-3">JENIS SERTIFIKAT</th>
                                <th class="py-3">NO. SERTIFIKAT</th>
                                <th class="py-3">NAMA PEMILIK</th>
                                <th class="py-3">LUAS TANAH</th>
                                <th class="py-3">LAMPIRAN DOKUMEN</th>
                                <th class="py-3">ALAMAT</th>
                                <th class="py-3 text-end pe-4">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @foreach ($listObjek as $item)
                                <tr>
                                    <td class="ps-4 text-dark">{{ $item->desa->kecamatan->kota->provinsi->name ?? '-' }}</td>
                                    <td><span class="text-dark">{{ $item->desa->kecamatan->kota->name ?? '-' }}</span></td>
                                    <td><span class="text-muted">{{ $item->desa->kecamatan->name ?? '-' }}</span></td>
                                    <td><span class="text-muted">{{ $item->desa->name ?? '-' }}</span></td>
                                    <td><span class="text-dark fw-medium">{{ $item->jenis_sertifikat ?? '-' }}</span></td>
                                    <td><span class="text-dark fw-bold">{{ $item->no_sertifikat ?? '-' }}</span></td>
                                    <td><span class="text-dark">{{ $item->nama_pemilik ?? '-' }}</span></td>
                                    <td><span class="text-muted">{{ $item->luas_tanah ?? '-' }}</span></td>
                                    <td style="width: 45%;">
                                        @if ($item->files && $item->files->count() > 0)
                                            <!-- Menampilkan dokumen sejajar 2 kolom -->
                                            <div class="row g-1" style="max-width: 480px;">
                                                @foreach ($item->files as $f)
                                                    <div class="col-6">
                                                        <a href="{{ asset('storage/' . $f->file_path) }}" target="_blank"
                                                            class="d-flex align-items-center justify-content-between p-2 rounded-2 bg-white border border-light-subtle text-decoration-none shadow-sm h-100 transition-hover"
                                                            title="Buka file: {{ $f->file_name }}">
                                                            <div class="d-flex align-items-center overflow-hidden me-1">
                                                                <!-- Sentuhan Warna Folder (Amber/Warning Tint) -->
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
                                                <i class="bi bi-slash-circle me-1"></i>Belum ada lampiran
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-muted text-wrap" style="max-width: 180px; display: inline-block;">
                                            {{ $item->alamat ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-inline-flex gap-1">
                                            @can('data-pendukung/objek/edit')
                                                <a href="{{ route('job.divisi-data-pendukung-objek.edit', $item->id) }}"
                                                    class="btn btn-sm btn-outline-secondary border-0 rounded-circle p-2"
                                                    title="Edit Objek">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" viewBox="0 0 16 16">
                                                        <path
                                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                        <path fill-rule="evenodd"
                                                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                    </svg>
                                                </a>
                                            @endcan
                                            @can('data-pendukung/objek/delete')
                                                <form action="{{ route('job.divisi-data-pendukung-objek.delete', $item->id) }}"
                                                    method="POST" class="confirm_delete d-inline"
                                                    data-message="objek {{ $item->no_sertifikat ?? $item->nama_pemilik }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-danger border-0 rounded-circle p-2"
                                                        title="Hapus Objek">
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
                            class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                            <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                        </svg>
                    </div>
                    <h6 class="fw-bold text-dark">Belum Ada Data Objek</h6>
                    <p class="text-muted small mb-0">Silakan gunakan form untuk menambahkan data objek.</p>
                </div>
            </div>
        @endif
    </div>