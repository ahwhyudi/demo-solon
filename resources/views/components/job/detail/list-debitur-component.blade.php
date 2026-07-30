@can('data-pendukung/debitur/view')
    <div class="mb-5">
        @if (count($listDebitur) > 0)
            <!-- Header & Tombol Export -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold text-dark mb-0">
                    <i class="bi bi-people-fill text-primary me-2"></i>Daftar Debitur Terdaftar
                </h6>
                <a href="{{ route('job.export-debitur-job-divisi', $listDebitur[0]->job_divisi_id) }}"
                    class="btn btn-success btn px-3 shadow-sm d-inline-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-file-earmark-excel me-1" viewBox="0 0 16 16">
                        <path
                            d="M5.884 6.68a.5.5 0 1 0-.768.64L7.349 10l-2.233 2.68a.5.5 0 0 0 .768.64L8 10.781l2.116 2.54a.5.5 0 0 0 .768-.641L8.651 10l2.233-2.68a.5.5 0 0 0-.768-.64L8 9.219l-2.116-2.54z" />
                        <path
                            d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                    </svg>
                    Export Excel
                </a>
            </div>

            <!-- Tabel Card Modern -->
            <div class="card border border-light-subtle shadow-sm rounded-3 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead
                            class="bg-light bg-opacity-50 text-secondary small fw-bold border-bottom border-light-subtle">
                            <tr>
                                <th class="py-3 ps-4" style="width: 25%;">NAMA LENGKAP</th>
                                <th class="py-3" style="width: 25%;">KONTAK</th>
                                <th class="py-3" style="width: 35%;">LAMPIRAN DOKUMEN</th>
                                <th class="py-3 text-end pe-4" style="width: 15%;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @foreach ($listDebitur as $debitur)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $debitur->nama }}</div>
                                        <small class="text-muted" style="font-size: 0.75rem;">Ditambahkan
                                            {{ $debitur->created_at ? $debitur->created_at->format('d/m/Y') : '-' }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center small text-dark mb-1">
                                            <i
                                                class="bi bi-telephone text-muted me-2"></i>{{ $debitur->nomor_telepon ?? '-' }}
                                        </div>
                                        <div class="d-flex align-items-center small text-muted">
                                            <i class="bi bi-envelope text-muted me-2"></i>{{ $debitur->email ?? '-' }}
                                        </div>
                                    </td>
                                    <td style="width: 45%;">
                                        @if ($debitur->files && $debitur->files->count() > 0)
                                            <!-- Menampilkan dokumen sejajar 2 kolom -->
                                            <div class="row g-1" style="max-width: 480px;">
                                                @foreach ($debitur->files as $f)
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
                                    <td class="text-end pe-4">
                                        <div class="d-inline-flex gap-1">
                                            @can('data-pendukung/debitur/edit')
                                                <a href="{{ route('job.divisi-data-pendukung-debitur.edit', $debitur->id) }}"
                                                    class="btn btn-sm btn-outline-secondary border-0 rounded-circle p-2"
                                                    title="Edit Debitur">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" viewBox="0 0 16 16">
                                                        <path
                                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                        <path fill-rule="evenodd"
                                                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                    </svg>
                                                </a>
                                            @endcan
                                            @can('data-pendukung/debitur/delete')
                                                <form
                                                    action="{{ route('job.divisi-data-pendukung-debitur.delete', $debitur->id) }}"
                                                    method="POST" class="confirm_delete d-inline"
                                                    data-message="debitur {{ $debitur->nama }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-danger border-0 rounded-circle p-2"
                                                        title="Hapus Debitur">
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
            <!-- Empty State Modern (Tampil saat belum ada data) -->
            <div class="card border border-light-subtle shadow-sm rounded-3 p-5 text-center bg-light bg-opacity-25">
                <div class="py-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex p-3 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                            class="bi bi-folder2-open" viewBox="0 0 16 16">
                            <path
                                d="M1 3.5A1.5 1.5 0 0 1 2.5 2h2.764c.958 0 1.76.56 2.311 1.184C7.985 3.648 8.48 4 9 4h4.5A1.5 1.5 0 0 1 15 5.5v.64c.57.265.94.876.856 1.546l-.64 5.124A2.5 2.5 0 0 1 12.733 15H3.266a2.5 2.5 0 0 1-2.481-2.19l-.64-5.124A1.5 1.5 0 0 1 1 6.14V3.5zM2 6h12v-.5a.5.5 0 0 0-.5-.5H9c-.964 0-1.71-.629-2.174-1.154C6.374 3.334 5.82 3 5.264 3H2.5a.5.5 0 0 0-.5.5V6zm-.367 1a.5.5 0 0 0-.496.562l.64 5.124A1.5 1.5 0 0 0 3.266 14h9.468a1.5 1.5 0 0 0 1.489-1.314l.64-5.124A.5.5 0 0 0 14.367 7H1.633z" />
                        </svg>
                    </div>
                    <h6 class="fw-bold text-dark">Belum Ada Data Debitur</h6>
                    <p class="text-muted small mb-0">Silakan gunakan form di bawah untuk menambahkan data debitur dan
                        lampirannya.</p>
                </div>
            </div>
        @endif
    </div>
@endcan
