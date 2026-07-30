@can('data-pendukung/pendirian-lembaga/view')
    <div class="mb-5">
        @if (count($jobDivisi->pendirianLembaga) > 0)
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold text-dark mb-0">
                    <i class="bi bi-building-fill-gear text-primary me-2"></i>Daftar Pendirian Lembaga Terdaftar
                </h6>
            </div>

            <!-- Card Table Modern Pendirian Lembaga -->
            <div class="card border border-light-subtle shadow-sm rounded-3 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-nowrap">
                        <thead class="bg-light bg-opacity-50 text-secondary small fw-bold border-bottom border-light-subtle">
                            <tr>
                                <th class="py-3 ps-4">NAMA LEMBAGA</th>
                                <th class="py-3">BIDANG USAHA</th>
                                <th class="py-3">MODAL DASAR</th>
                                <th class="py-3">MODAL SETOR</th>
                                <th class="py-3">PEMEGANG SAHAM</th>
                                <th class="py-3">ALAMAT</th>
                                <th class="py-3 text-end pe-4">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @foreach ($jobDivisi->pendirianLembaga as $item)
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">
                                        {{ $item->nama_lembaga ?? '-' }}
                                    </td>
                                    <td>
                                        <span class="text-dark">{{ $item->bidang_usaha ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted">Rp
                                            {{ number_format($item->modal_dasar ?? 0, 0, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted">Rp
                                            {{ number_format($item->modal_setor ?? 0, 0, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $item->pemegang_saham ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted text-wrap" style="max-width: 200px; display: inline-block;">
                                            {{ $item->alamat ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-inline-flex gap-1">
                                            @can('data-pendukung/pendirian-lembaga/edit')
                                                <a href="{{ route('job.divisi-data-pendukung-pendirian-lembaga.edit', $item->id) }}"
                                                    class="btn btn-sm btn-outline-secondary border-0 rounded-circle p-2"
                                                    title="Edit Pendirian Lembaga">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" viewBox="0 0 16 16">
                                                        <path
                                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                        <path fill-rule="evenodd"
                                                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                    </svg>
                                                </a>
                                            @endcan
                                            @can('data-pendukung/pendirian-lembaga/delete')
                                                <form
                                                    action="{{ route('job.divisi-data-pendukung-pendirian-lembaga.delete', $item->id) }}"
                                                    method="POST" class="confirm_delete d-inline"
                                                    data-message="pendirian lembaga {{ $item->nama_lembaga }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-danger border-0 rounded-circle p-2"
                                                        title="Hapus Pendirian Lembaga">
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
                            class="bi bi-building-fill-gear" viewBox="0 0 16 16">
                            <path
                                d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6.5a2.5 2.5 0 0 1-1 2V8H3v1.5a2.5 2.5 0 0 1-1-2V1zm11 0H3v5h10V1zm-3.5 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z" />
                            <path
                                d="M14.5 10a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM12 11.5a2.5 2.5 0 1 1 5 0 2.5 2.5 0 0 1-5 0z" />
                        </svg>
                    </div>
                    <h6 class="fw-bold text-dark">Belum Ada Data Pendirian Lembaga</h6>
                    <p class="text-muted small mb-0">Silakan gunakan form untuk menambahkan data pendirian lembaga.</p>
                </div>
            </div>
        @endif
    </div>
@endcan

@can('data-pendukung/pendirian-lembaga/create')
    @if (!count($jobDivisi->pendirianLembaga))
        <form action="{{ route('job.divisi-data-pendukung.store') }}" method="post" enctype="multipart/form-data"
            id="form_pendirian_lembaga">
            @csrf
            <input type="text" hidden name="job_divisi_id" value="{{ $jobDivisi->id }}">

            @php
                $rowsPendirianLembaga = old('pendirian_lembaga', [
                    [
                        'nama_lembaga' => '',
                        'bidang_usaha' => '',
                        'modal_dasar' => '',
                        'modal_setor' => '',
                        'pemegang_saham' => '',
                        'jajaran_direksi' => '',
                        'jajaran_komisaris' => '',
                        'alamat' => '',
                    ],
                ]);

                $lastIndex = is_array($rowsPendirianLembaga) ? array_key_last($rowsPendirianLembaga) : -1;
                if ($lastIndex === null) {
                    $lastIndex = -1;
                }
            @endphp

            <div class="list_card_pendirian_lembaga">
                @foreach ($rowsPendirianLembaga as $i => $row)
                    <div class="card shadow-sm border border-light-subtle rounded-3 card_pendirian_lembaga_{{ $i }} @if ($i > 0) mt-4 @endif"
                        data-index="{{ $i }}">
                        <div
                            class="card-header bg-light bg-opacity-50 py-3 border-bottom border-light-subtle d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-dark">
                                <i class="bi bi-bank2 me-2 text-primary"></i>Form Data Pendirian Lembaga
                            </h6>
                            @if ($i > 0)
                                <button type="button" class="btn btn-sm btn-outline-danger border-0 px-2 py-1"
                                    onclick="removePendirianLembaga({{ $i }})" title="Hapus Form Ini">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            @endif
                        </div>
                        <div class="card-body pt-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-secondary small fw-bold required">NAMA LEMBAGA</label>
                                    <input type="text" name="pendirian_lembaga[{{ $i }}][nama_lembaga]"
                                        class="form-control @error("pendirian_lembaga.$i.nama_lembaga") is-invalid @enderror"
                                        value="{{ old("pendirian_lembaga.$i.nama_lembaga", $row['nama_lembaga'] ?? '') }}"
                                        placeholder="Masukkan nama lembaga">
                                    @error("pendirian_lembaga.$i.nama_lembaga")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-secondary small fw-bold required">BIDANG USAHA</label>
                                    <input type="text" name="pendirian_lembaga[{{ $i }}][bidang_usaha]"
                                        class="form-control @error("pendirian_lembaga.$i.bidang_usaha") is-invalid @enderror"
                                        value="{{ old("pendirian_lembaga.$i.bidang_usaha", $row['bidang_usaha'] ?? '') }}"
                                        placeholder="Masukkan bidang usaha">
                                    @error("pendirian_lembaga.$i.bidang_usaha")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-secondary small fw-bold required">MODAL DASAR</label>
                                    <input type="text" name="pendirian_lembaga[{{ $i }}][modal_dasar]"
                                        class="form-control money @error("pendirian_lembaga.$i.modal_dasar") is-invalid @enderror"
                                        value="{{ old("pendirian_lembaga.$i.modal_dasar", $row['modal_dasar'] ?? '') }}"
                                        placeholder="0">
                                    @error("pendirian_lembaga.$i.modal_dasar")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-secondary small fw-bold required">MODAL SETOR</label>
                                    <input type="text" name="pendirian_lembaga[{{ $i }}][modal_setor]"
                                        class="form-control money @error("pendirian_lembaga.$i.modal_setor") is-invalid @enderror"
                                        value="{{ old("pendirian_lembaga.$i.modal_setor", $row['modal_setor'] ?? '') }}"
                                        placeholder="0">
                                    @error("pendirian_lembaga.$i.modal_setor")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label text-secondary small fw-bold required">PEMEGANG SAHAM</label>
                                    <textarea name="pendirian_lembaga[{{ $i }}][pemegang_saham]" rows="3"
                                        class="form-control @error("pendirian_lembaga.$i.pemegang_saham") is-invalid @enderror"
                                        placeholder="Masukkan pemegang saham">{{ old("pendirian_lembaga.$i.pemegang_saham", $row['pemegang_saham'] ?? '') }}</textarea>
                                    @error("pendirian_lembaga.$i.pemegang_saham")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-secondary small fw-bold">JAJARAN DIREKSI</label>
                                    <textarea name="pendirian_lembaga[{{ $i }}][jajaran_direksi]" rows="3"
                                        class="form-control @error("pendirian_lembaga.$i.jajaran_direksi") is-invalid @enderror"
                                        placeholder="Masukkan jajaran direksi">{{ old("pendirian_lembaga.$i.jajaran_direksi", $row['jajaran_direksi'] ?? '') }}</textarea>
                                    @error("pendirian_lembaga.$i.jajaran_direksi")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-secondary small fw-bold">JAJARAN KOMISARIS</label>
                                    <textarea name="pendirian_lembaga[{{ $i }}][jajaran_komisaris]" rows="3"
                                        class="form-control @error("pendirian_lembaga.$i.jajaran_komisaris") is-invalid @enderror"
                                        placeholder="Masukkan jajaran komisaris">{{ old("pendirian_lembaga.$i.jajaran_komisaris", $row['jajaran_komisaris'] ?? '') }}</textarea>
                                    @error("pendirian_lembaga.$i.jajaran_komisaris")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label text-secondary small fw-bold required">ALAMAT</label>
                                    <textarea name="pendirian_lembaga[{{ $i }}][alamat]" rows="3"
                                        class="form-control @error("pendirian_lembaga.$i.alamat") is-invalid @enderror"
                                        placeholder="Masukkan alamat lengkap">{{ old("pendirian_lembaga.$i.alamat", $row['alamat'] ?? '') }}</textarea>
                                    @error("pendirian_lembaga.$i.alamat")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Tombol Tambah Lebih Elegan -->
            <div class="mt-4">
                <button type="button"
                    class="btn btn-outline-primary w-100 py-3 border-2 border-dashed fw-bold rounded-3 add_more_pendirian_lembaga shadow-sm transition-hover"
                    style="border-style: dashed !important;">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Form Pendirian Lembaga Lainnya
                </button>
            </div>

            @if ($jobDivisi->status !== 'Batal Akad')
                <div class="d-flex justify-content-end mt-4 mb-5">
                    <button type="submit"
                        class="btn btn-primary px-5 py-2 fw-bold shadow-sm btn__simpan_pendirian_lembaga">
                        Simpan Pendirian Lembaga
                    </button>
                </div>
            @endif
        </form>

        <!-- Modal Konfirmasi Hapus Form Pendirian Lembaga -->
        <div class="modal fade" id="modalDeletePendirianLembaga" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content border-0 shadow">
                    <div class="modal-body text-center p-4">
                        <div class="text-danger mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor"
                                class="bi bi-exclamation-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                <path
                                    d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z" />
                            </svg>
                        </div>
                        <h6 class="fw-bold mb-1">Hapus form ini?</h6>
                        <p class="text-muted small mb-4">Input data di baris ini akan hilang dan tidak disimpan.</p>
                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-light btn-sm px-3 border"
                                data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-danger btn-sm px-3 fw-bold"
                                id="btn-confirm-remove-pendirian-lembaga">Ya, Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @push('addScript')
            <script>
                $(document).ready(function() {
                    $(".btn__simpan_pendirian_lembaga").on("click", function() {
                        $(this).prop("disabled", true);
                        $(this).text("Menyimpan...");
                        $(".loading__global").show();
                        $("#form_pendirian_lembaga").submit();
                    });

                    let idx_pendirian_lembaga = {{ $lastIndex }};
                    let pendirianLembagaIndexToRemove = null;

                    const pendirianLembagaTemplate = (i) => `
                    <div class="card shadow-sm border border-light-subtle rounded-3 card_pendirian_lembaga_${i} mt-4" data-index="${i}" style="display:none;">
                        <div class="card-header bg-light bg-opacity-50 py-3 border-bottom border-light-subtle d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-dark">
                                <i class="bi bi-bank2 me-2 text-primary"></i>Form Data Pendirian Lembaga
                            </h6>
                            <button type="button" class="btn btn-sm btn-outline-danger border-0 px-2 py-1" onclick="removePendirianLembaga(${i})" title="Hapus Form Ini">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </div>
                        <div class="card-body pt-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-secondary small fw-bold required">NAMA LEMBAGA</label>
                                    <input type="text" class="form-control" name="pendirian_lembaga[${i}][nama_lembaga]" placeholder="Masukkan nama lembaga">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-secondary small fw-bold required">BIDANG USAHA</label>
                                    <input type="text" class="form-control" name="pendirian_lembaga[${i}][bidang_usaha]" placeholder="Masukkan bidang usaha">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-secondary small fw-bold required">MODAL DASAR</label>
                                    <input type="text" class="form-control money" name="pendirian_lembaga[${i}][modal_dasar]" placeholder="0">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-secondary small fw-bold required">MODAL SETOR</label>
                                    <input type="text" class="form-control money" name="pendirian_lembaga[${i}][modal_setor]" placeholder="0">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label text-secondary small fw-bold required">PEMEGANG SAHAM</label>
                                    <textarea rows="3" class="form-control" name="pendirian_lembaga[${i}][pemegang_saham]" placeholder="Masukkan pemegang saham"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-secondary small fw-bold">JAJARAN DIREKSI</label>
                                    <textarea rows="3" class="form-control" name="pendirian_lembaga[${i}][jajaran_direksi]" placeholder="Masukkan jajaran direksi"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-secondary small fw-bold">JAJARAN KOMISARIS</label>
                                    <textarea rows="3" class="form-control" name="pendirian_lembaga[${i}][jajaran_komisaris]" placeholder="Masukkan jajaran komisaris"></textarea>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label text-secondary small fw-bold required">ALAMAT</label>
                                    <textarea rows="3" class="form-control" name="pendirian_lembaga[${i}][alamat]" placeholder="Masukkan alamat lengkap"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>`;

                    $(".add_more_pendirian_lembaga").on("click", function() {
                        idx_pendirian_lembaga++;
                        let i = idx_pendirian_lembaga;

                        $(".list_card_pendirian_lembaga").append(pendirianLembagaTemplate(i));

                        $(`.card_pendirian_lembaga_${i}`).fadeIn(300);

                        $('.money').mask('#.##0', {
                            reverse: true
                        });
                    });

                    window.removePendirianLembaga = function(idx) {
                        pendirianLembagaIndexToRemove = idx;
                        let myModal = new bootstrap.Modal(document.getElementById('modalDeletePendirianLembaga'));
                        myModal.show();
                    };

                    $("#btn-confirm-remove-pendirian-lembaga").on("click", function() {
                        if (pendirianLembagaIndexToRemove !== null) {
                            $(`.list_card_pendirian_lembaga .card_pendirian_lembaga_${pendirianLembagaIndexToRemove}`)
                                .fadeOut(300, function() {
                                    $(this).remove();
                                });
                            $('#modalDeletePendirianLembaga').modal('hide');
                            pendirianLembagaIndexToRemove = null;
                        }
                    });

                    $('.money').mask('#.##0', {
                        reverse: true
                    });
                });
            </script>
        @endpush
    @endif
@endcan
