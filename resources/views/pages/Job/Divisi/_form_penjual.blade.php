@can('data-pendukung/penjual/view')
    <x-job.detail.list-penjual-component :listPenjual="$jobDivisi->penjual" />
@endcan

@can('data-pendukung/penjual/create')
    @if (!count($jobDivisi->penjual))
        @php
            // Ambil old input; jika kosong, buat 1 baris default
            $oldPenjuals =
                old('penjual') ??
                (session('form_data.penjual') ?? [['nama_lengkap' => '', 'nik' => '', 'phone' => '', 'email' => '']]);

            // Tentukan index terakhir untuk JS (agar append lanjut rapi)
            $lastIndex = is_array($oldPenjuals) ? array_key_last($oldPenjuals) : -1;
            if ($lastIndex === null) {
                $lastIndex = -1;
            }
        @endphp

        <form action="{{ route('job.divisi-data-pendukung.store') }}" method="post" enctype="multipart/form-data" id="form_penjual">
            @csrf
            <input type="text" hidden name="job_divisi_id" value="{{ $jobDivisi->id }}">

            <div class="body__penjual">
                @foreach ($oldPenjuals as $key => $item)
                    <div class="card shadow-sm border border-light-subtle rounded-3 card_{{ $key }} @if ($key > 0) mt-4 @endif" data-index="{{ $key }}">
                        <div class="card-header bg-light bg-opacity-50 py-3 border-bottom border-light-subtle d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-dark">
                                <i class="bi bi-person-badge-fill me-2 text-primary"></i>Form Data Penjual
                            </h6>
                            @if ($key > 0)
                                <button type="button" class="btn btn-sm btn-outline-danger border-0 px-2 py-1" onclick="removePenjual({{ $key }})" title="Hapus Form Ini">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            @endif
                        </div>
                        <div class="card-body pt-3">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold required">NAMA LENGKAP</label>
                                    <input type="text"
                                        class="form-control @error("penjual.$key.nama_lengkap") is-invalid @enderror"
                                        name="penjual[{{ $key }}][nama_lengkap]"
                                        value="{{ old("penjual.$key.nama_lengkap", $item['nama_lengkap'] ?? '') }}"
                                        placeholder="Masukkan nama lengkap">
                                    @error("penjual.$key.nama_lengkap")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold required">NOMOR TELEPON</label>
                                    <input type="number"
                                        class="form-control @error("penjual.$key.phone") is-invalid @enderror"
                                        name="penjual[{{ $key }}][phone]"
                                        value="{{ old("penjual.$key.phone", $item['phone'] ?? '') }}"
                                        inputmode="tel" placeholder="Contoh: 081234567890">
                                    @error("penjual.$key.phone")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold required">EMAIL</label>
                                    <input type="email"
                                        class="form-control @error("penjual.$key.email") is-invalid @enderror"
                                        name="penjual[{{ $key }}][email]"
                                        value="{{ old("penjual.$key.email", $item['email'] ?? '') }}"
                                        autocomplete="email" placeholder="email@domain.com">
                                    @error("penjual.$key.email")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <div class="p-3 border border-2 border-dashed border-secondary border-opacity-25 rounded-3 bg-light bg-opacity-25">
                                        <label class="form-label text-dark small fw-bold mb-1">DOKUMEN LAMPIRAN PENJUAL</label>
                                        <p class="text-muted small mb-2" style="font-size: 0.75rem;">Anda dapat memilih beberapa file sekaligus dengan menahan tombol Ctrl / Shift.</p>
                                        {{-- Penyesuaian nama menjadi files[] dan penambahan multiple --}}
                                        <input type="file"
                                            class="form-control bg-white @error("penjual.$key.files.*") is-invalid @enderror"
                                            name="penjual[{{ $key }}][files][]" multiple>
                                        @error("penjual.$key.files.*")
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
                <button type="button" class="btn btn-outline-primary w-100 py-3 border-2 border-dashed fw-bold rounded-3 add_penjual shadow-sm transition-hover" style="border-style: dashed !important;">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Form Penjual Lainnya
                </button>
            </div>

            @if ($jobDivisi->status !== 'Batal Akad')
                <div class="d-flex justify-content-end mt-4 mb-5">
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm btn__simpan_penjual">
                        Simpan Data Penjual
                    </button>
                </div>
            @endif
        </form>

        <!-- Modal Konfirmasi Hapus Form Penjual -->
        <div class="modal fade" id="modalDeletePenjual" tabindex="-1" aria-hidden="true">
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
                            <button type="button" class="btn btn-danger btn-sm px-3 fw-bold" id="btn-confirm-remove-penjual">Ya, Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @push('addScript')
            <script>
                $(document).ready(function() {
                    $(".btn__simpan_penjual").on("click", function() {
                        $(this).prop("disabled", true);
                        $(this).text("Menyimpan...");
                        $(".loading__global").show();
                        $("#form_penjual").submit();
                    });

                    let i_penjual = {{ $lastIndex }};
                    let penjualIndexToRemove = null;

                    const penjualTemplate = (i) => `
                    <div class="card shadow-sm border border-light-subtle rounded-3 card_${i} mt-4" data-index="${i}" style="display:none;">
                        <div class="card-header bg-light bg-opacity-50 py-3 border-bottom border-light-subtle d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-dark">
                                <i class="bi bi-person-badge-fill me-2 text-primary"></i>Form Data Penjual
                            </h6>
                            <button type="button" class="btn btn-sm btn-outline-danger border-0 px-2 py-1" onclick="removePenjual(${i})" title="Hapus Form Ini">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </div>
                        <div class="card-body pt-3">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold required">NAMA LENGKAP</label>
                                    <input type="text" class="form-control" name="penjual[${i}][nama_lengkap]" placeholder="Masukkan nama lengkap">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold required">NOMOR TELEPON</label>
                                    <input type="number" class="form-control" inputmode="tel" name="penjual[${i}][phone]" placeholder="Contoh: 081234567890">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold required">EMAIL</label>
                                    <input type="email" class="form-control" autocomplete="email" name="penjual[${i}][email]" placeholder="email@domain.com">
                                </div>
                                <div class="col-12">
                                    <div class="p-3 border border-2 border-dashed border-secondary border-opacity-25 rounded-3 bg-light bg-opacity-25">
                                        <label class="form-label text-dark small fw-bold mb-1">DOKUMEN LAMPIRAN PENJUAL</label>
                                        <p class="text-muted small mb-2" style="font-size: 0.75rem;">Anda dapat memilih beberapa file sekaligus dengan menahan tombol Ctrl / Shift.</p>
                                        {{-- Penyesuaian nama menjadi files[] dan penambahan multiple --}}
                                        <input type="file" class="form-control bg-white" name="penjual[${i}][files][]" multiple>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;

                    $(".add_penjual").on("click", function() {
                        i_penjual++;
                        let i = i_penjual;

                        $(".body__penjual").append(penjualTemplate(i));
                        $(`.card_${i}`).fadeIn(300);
                    });

                    window.removePenjual = function(idx) {
                        penjualIndexToRemove = idx;
                        let myModal = new bootstrap.Modal(document.getElementById('modalDeletePenjual'));
                        myModal.show();
                    };

                    $("#btn-confirm-remove-penjual").on("click", function() {
                        if (penjualIndexToRemove !== null) {
                            $(`.body__penjual .card_${penjualIndexToRemove}`).fadeOut(300, function() {
                                $(this).remove();
                            });
                            $('#modalDeletePenjual').modal('hide');
                            penjualIndexToRemove = null;
                        }
                    });
                });
            </script>
        @endpush
    @endif
@endcan