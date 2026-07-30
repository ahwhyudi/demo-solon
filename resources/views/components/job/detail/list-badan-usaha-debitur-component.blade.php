<div class="mb-5">
        @if (count($listBadanUsahaDebitur) > 0)
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold text-dark mb-0">
                    <i class="bi bi-building-check text-primary me-2"></i>Daftar Badan Usaha Debitur Terdaftar
                </h6>
            </div>

            <!-- Card Table Modern Badan Usaha Debitur -->
            <div class="card border border-light-subtle shadow-sm rounded-3 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-nowrap">
                        <thead class="bg-light bg-opacity-50 text-secondary small fw-bold border-bottom border-light-subtle">
                            <tr>
                                <th class="py-3 ps-4">NAMA BADAN USAHA</th>
                                <th class="py-3">NAMA PERWAKILAN</th>
                                <th class="py-3">NOMOR TELEPON</th>
                                <th class="py-3">EMAIL</th>
                                <th class="py-3">LAMPIRAN DOKUMEN</th>
                                <th class="py-3 text-end pe-4">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @foreach ($listBadanUsahaDebitur as $item)
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">
                                        {{ $item->nama_badan_usaha ?? '-' }}
                                    </td>
                                    <td>
                                        <span class="text-dark">{{ $item->nama_perwakilan ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $item->no_telepon ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $item->email ?? '-' }}</span>
                                    </td>
                                    <td>
                                        @if ($item->files && $item->files->count() > 0)
                                        <div class="d-flex flex-column gap-1">
                                            @foreach ($item->files as $f)
                                                <a href="{{ asset('storage/' . $f->file_path) }}" target="_blank"
                                                    class="d-inline-flex align-items-center px-2 py-1 rounded-2 bg-white border border-light-subtle text-decoration-none shadow-sm transition-hover"
                                                    title="Buka: {{ $f->file_name ?? 'Dokumen' }}">
                                                    <div class="bg-warning bg-opacity-10 p-1 rounded me-2 border border-warning-subtle d-flex align-items-center justify-content-center flex-shrink-0"
                                                        style="width: 20px; height: 20px;">
                                                        <i class="bi bi-file-earmark-text text-warning"
                                                            style="font-size: 0.75rem;"></i>
                                                    </div>
                                                    <span class="text-truncate text-dark fw-medium"
                                                        style="font-size: 0.75rem; max-width: 120px;">
                                                        {{ $f->file_name ?? 'Lihat File' }}
                                                    </span>
                                                    <i class="bi bi-box-arrow-up-right text-muted ms-1 flex-shrink-0"
                                                        style="font-size: 0.6rem;"></i>
                                                </a>
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
                                            @can('data-pendukung/badan-usaha-debitur/edit')
                                                <a href="{{ route('job.divisi-data-pendukung-badan-usaha-debitur.edit', $item->id) }}"
                                                    class="btn btn-sm btn-outline-secondary border-0 rounded-circle p-2"
                                                    title="Edit Badan Usaha Debitur">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" viewBox="0 0 16 16">
                                                        <path
                                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                        <path fill-rule="evenodd"
                                                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                    </svg>
                                                </a>
                                            @endcan
                                            @can('data-pendukung/badan-usaha-debitur/delete')
                                                <form action="{{ route('job.divisi-data-pendukung-badan-usaha-debitur.delete', $item->id) }}"
                                                    method="POST" class="confirm_delete d-inline"
                                                    data-message="badan usaha debitur {{ $item->nama_badan_usaha }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-danger border-0 rounded-circle p-2"
                                                        title="Hapus Badan Usaha Debitur">
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
                            class="bi bi-building-check" viewBox="0 0 16 16">
                            <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm1.679-4.93-1.335 1.335a.5.5 0 0 1-.707 0l-.636-.636a.5.5 0 1 1 .707-.707l.283.283 1.132-1.132a.5.5 0 0 1 .708.708Z"/>
                            <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6.5a3.5 3.5 0 0 0-5.32 2.766L8 10.5 3 13V3H2V1Zm11 0H3v9h5.5a3.5 3.5 0 0 1 4.5 4.5V11h1V1Z"/>
                        </svg>
                    </div>
                    <h6 class="fw-bold text-dark">Belum Ada Data Badan Usaha Debitur</h6>
                    <p class="text-muted small mb-0">Silakan gunakan form untuk menambahkan data badan usaha debitur.</p>
                </div>
            </div>
        @endif
    </div>