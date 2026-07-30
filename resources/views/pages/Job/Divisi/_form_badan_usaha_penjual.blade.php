@can('data-pendukung/badan-usaha-penjual/view')
    <x-job.detail.list-badan-usaha-penjual-component :listBadanUsahaPenjual="$jobDivisi->listBadanUsahaPenjual" />
@endcan

@can('data-pendukung/badan-usaha-penjual/create')
    @if (!count($jobDivisi->listBadanUsahaPenjual))
        <form action="{{ route('job.divisi-data-pendukung.store') }}" method="post" enctype="multipart/form-data"
            id="form_badan_usaha_penjual">
            @csrf
            <input type="text" hidden name="job_divisi_id" value="{{ $jobDivisi->id }}">
            
            <div class="body__badan_usaha_penjual">
                @php
                    $rowsPenjual = old('badan_usaha_penjual', [
                        [
                            'nama_badan_usaha' => '',
                            'nama_perwakilan' => '',
                            'nomor_telepon' => '',
                            'email' => '',
                        ],
                    ]);

                    $lastIndex = is_array($rowsPenjual) ? array_key_last($rowsPenjual) : -1;
                    if ($lastIndex === null) {
                        $lastIndex = -1;
                    }
                @endphp

                @foreach ($rowsPenjual as $i => $row)
                    <div class="card shadow-sm border border-light-subtle rounded-3 card_badan_usaha_penjual_{{ $i }} @if ($i > 0) mt-4 @endif" data-index="{{ $i }}">
                        <div class="card-header bg-light bg-opacity-50 py-3 border-bottom border-light-subtle d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-dark">
                                <i class="bi bi-building-fill-add me-2 text-primary"></i>Form Data Badan Usaha Penjual
                            </h6>
                            @if ($i > 0)
                                <button type="button" class="btn btn-sm btn-outline-danger border-0 px-2 py-1" onclick="removeBadanUsahaPenjual({{ $i }})" title="Hapus Form Ini">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            @endif
                        </div>
                        <div class="card-body pt-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-secondary small fw-bold required">NAMA BADAN USAHA</label>
                                    <input type="text"
                                        name="badan_usaha_penjual[{{ $i }}][nama_badan_usaha]"
                                        class="form-control @error("badan_usaha_penjual.$i.nama_badan_usaha") is-invalid @enderror"
                                        value="{{ old("badan_usaha_penjual.$i.nama_badan_usaha", $row['nama_badan_usaha'] ?? '') }}"
                                        placeholder="Masukkan nama badan usaha">
                                    @error("badan_usaha_penjual.$i.nama_badan_usaha")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-secondary small fw-bold required">NAMA PERWAKILAN</label>
                                    <input type="text"
                                        name="badan_usaha_penjual[{{ $i }}][nama_perwakilan]"
                                        class="form-control @error("badan_usaha_penjual.$i.nama_perwakilan") is-invalid @enderror"
                                        value="{{ old("badan_usaha_penjual.$i.nama_perwakilan", $row['nama_perwakilan'] ?? '') }}"
                                        placeholder="Masukkan nama perwakilan">
                                    @error("badan_usaha_penjual.$i.nama_perwakilan")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-secondary small fw-bold required">NOMOR TELEPON</label>
                                    <input type="number"
                                        name="badan_usaha_penjual[{{ $i }}][nomor_telepon]"
                                        class="form-control @error("badan_usaha_penjual.$i.nomor_telepon") is-invalid @enderror"
                                        value="{{ old("badan_usaha_penjual.$i.nomor_telepon", $row['nomor_telepon'] ?? '') }}"
                                        inputmode="tel" placeholder="Contoh: 081234567890">
                                    @error("badan_usaha_penjual.$i.nomor_telepon")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-secondary small fw-bold required">EMAIL</label>
                                    <input type="email" name="badan_usaha_penjual[{{ $i }}][email]"
                                        class="form-control @error("badan_usaha_penjual.$i.email") is-invalid @enderror"
                                        value="{{ old("badan_usaha_penjual.$i.email", $row['email'] ?? '') }}"
                                        autocomplete="email" placeholder="email@domain.com">
                                    @error("badan_usaha_penjual.$i.email")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <div class="p-3 border border-2 border-dashed border-secondary border-opacity-25 rounded-3 bg-light bg-opacity-25">
                                        <label class="form-label text-dark small fw-bold mb-1">DOKUMEN LAMPIRAN</label>
                                        <p class="text-muted small mb-2" style="font-size: 0.75rem;">Unggah beberapa dokumen pendukung badan usaha penjual sekaligus.</p>
                                        <input type="file" name="badan_usaha_penjual[{{ $i }}][files][]"
                                            class="form-control bg-white @error("badan_usaha_penjual.$i.files.*") is-invalid @enderror" multiple>
                                        @error("badan_usaha_penjual.$i.files.*")
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Tombol Tambah Lebih Elegan -->
            <div class="mt-4">
                <button type="button" class="btn btn-outline-primary w-100 py-3 border-2 border-dashed fw-bold rounded-3 add_more_badan_usaha_penjual shadow-sm transition-hover" style="border-style: dashed !important;">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Form Badan Usaha Penjual Lainnya
                </button>
            </div>

            @if ($jobDivisi->status !== 'Batal Akad')
                <div class="d-flex justify-content-end mt-4 mb-5">
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm btn__simpan_badan_usaha_penjual">
                        Simpan Badan Usaha Penjual
                    </button>
                </div>
            @endif
        </form>

        <!-- Modal Konfirmasi Hapus Form Badan Usaha Penjual -->
        <div class="modal fade" id="modalDeleteBadanUsahaPenjual" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content border-0 shadow">
                    <div class="modal-body text-center p-4">
                        <div class="text-danger mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-exclamation-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                            </svg>
                        </div>
                        <h6 class="fw-bold mb-1">Hapus form ini?</h6>
                        <p class="text-muted small mb-4">Input data di baris ini akan hilang dan tidak disimpan.</p>
                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-light btn-sm px-3 border" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-danger btn-sm px-3 fw-bold" id="btn-confirm-remove-badan-usaha-penjual">Ya, Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @push('addScript')
            <script>
                $(document).ready(function() {
                    $(".btn__simpan_badan_usaha_penjual").on("click", function() {
                        $(this).prop("disabled", true);
                        $(this).text("Menyimpan...");
                        $(".loading__global").show();
                        $("#form_badan_usaha_penjual").submit();
                    });

                    let idx_badan_usaha_penjual = {{ $lastIndex }};
                    let badanUsahaPenjualIndexToRemove = null;

                    const badanUsahaPenjualTemplate = (i) => `
                    <div class="card shadow-sm border border-light-subtle rounded-3 card_badan_usaha_penjual_${i} mt-4" data-index="${i}" style="display:none;">
                        <div class="card-header bg-light bg-opacity-50 py-3 border-bottom border-light-subtle d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-dark">
                                <i class="bi bi-building-fill-add me-2 text-primary"></i>Form Data Badan Usaha Penjual
                            </h6>
                            <button type="button" class="btn btn-sm btn-outline-danger border-0 px-2 py-1" onclick="removeBadanUsahaPenjual(${i})" title="Hapus Form Ini">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </div>
                        <div class="card-body pt-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-secondary small fw-bold required">NAMA BADAN USAHA</label>
                                    <input type="text" class="form-control" name="badan_usaha_penjual[${i}][nama_badan_usaha]" placeholder="Masukkan nama badan usaha">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-secondary small fw-bold required">NAMA PERWAKILAN</label>
                                    <input type="text" class="form-control" name="badan_usaha_penjual[${i}][nama_perwakilan]" placeholder="Masukkan nama perwakilan">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-secondary small fw-bold required">NOMOR TELEPON</label>
                                    <input type="number" class="form-control" inputmode="tel" name="badan_usaha_penjual[${i}][nomor_telepon]" placeholder="Contoh: 081234567890">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-secondary small fw-bold required">EMAIL</label>
                                    <input type="email" class="form-control" autocomplete="email" name="badan_usaha_penjual[${i}][email]" placeholder="email@domain.com">
                                </div>
                                <div class="col-12">
                                    <div class="p-3 border border-2 border-dashed border-secondary border-opacity-25 rounded-3 bg-light bg-opacity-25">
                                        <label class="form-label text-dark small fw-bold mb-1">DOKUMEN LAMPIRAN</label>
                                        <p class="text-muted small mb-2" style="font-size: 0.75rem;">Unggah beberapa dokumen pendukung badan usaha penjual sekaligus.</p>
                                        <input type="file" class="form-control bg-white" name="badan_usaha_penjual[${i}][files][]" multiple>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;

                    $(".add_more_badan_usaha_penjual").on("click", function() {
                        idx_badan_usaha_penjual++;
                        let i = idx_badan_usaha_penjual;

                        $(".body__badan_usaha_penjual").append(badanUsahaPenjualTemplate(i));
                        $(`.card_badan_usaha_penjual_${i}`).fadeIn(300);
                    });

                    window.removeBadanUsahaPenjual = function(idx) {
                        badanUsahaPenjualIndexToRemove = idx;
                        let myModal = new bootstrap.Modal(document.getElementById('modalDeleteBadanUsahaPenjual'));
                        myModal.show();
                    };

                    $("#btn-confirm-remove-badan-usaha-penjual").on("click", function() {
                        if (badanUsahaPenjualIndexToRemove !== null) {
                            $(`.body__badan_usaha_penjual .card_badan_usaha_penjual_${badanUsahaPenjualIndexToRemove}`).fadeOut(300, function() {
                                $(this).remove();
                            });
                            $('#modalDeleteBadanUsahaPenjual').modal('hide');
                            badanUsahaPenjualIndexToRemove = null;
                        }
                    });
                });
            </script>
        @endpush
    @endif
@endcan