@can('data-pendukung/bank/view')
    <x-job.detail.list-bank-component :listBank="$jobDivisi->listBank" />
@endcan
@can('data-pendukung/bank/create')
    @if (!count($jobDivisi->listBank))
        @php
            $formData = session('form_data', []);
            $oldBank =
                old('bank') ??
                (session('form_data.bank') ?? [
                    [
                        'bank' => '',
                        'pimpinan' => '',
                        'kepala_legal' => '',
                        'legal' => '',
                        'marketing' => '',
                        'kepala_marketing' => '',
                        'file' => [],
                    ],
                ]);

            $lastIndex = is_array($oldBank) ? array_key_last($oldBank) : -1;
            if ($lastIndex === null) {
                $lastIndex = -1;
            }
        @endphp

        <form action="{{ route('job.divisi-data-pendukung.store') }}" method="post" enctype="multipart/form-data" id="form_bank">
            @csrf
            <input type="text" hidden name="job_divisi_id" value="{{ $jobDivisi->id }}">

            <div class="card-body-bank">
                @foreach ($oldBank as $idx => $o)
                    <div class="card shadow-sm border border-light-subtle rounded-3 card_bank_{{ $idx }} @if ($idx > 0) mt-4 @endif" data-index="{{ $idx }}">
                        <div class="card-header bg-light bg-opacity-50 py-3 border-bottom border-light-subtle d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-dark">
                                <i class="bi bi-bank2 me-2 text-primary"></i>Form Data Bank
                            </h6>
                            @if ($idx > 0)
                                <button type="button" class="btn btn-sm btn-outline-danger border-0 px-2 py-1" onclick="removeBank({{ $idx }})" title="Hapus Form Ini">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            @endif
                        </div>
                        <div class="card-body pt-3">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold required">NAMA BANK</label>
                                    <select name="bank[{{ $idx }}][bank]"
                                        class="form-select select2 select_bank @error("bank.$idx.bank") is-invalid @enderror" data-placeholder="Pilih Bank"
                                        data-idx="{{ $idx }}">
                                        <option value=""></option>
                                        @foreach ($bank as $item)
                                            <option value="{{ $item->id }}"
                                                {{ old("bank.$idx.bank", $o['bank'] ?? '') == $item->id ? 'selected' : '' }}>
                                                {{ $item->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error("bank.$idx.bank")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold required">NAMA PIMPINAN</label>
                                    <input type="text"
                                        class="form-control pimpinan_bank_{{ $idx }} @error("bank.$idx.pimpinan") is-invalid @enderror"
                                        name="bank[{{ $idx }}][pimpinan]"
                                        value="{{ old("bank.$idx.pimpinan", $o['pimpinan'] ?? '') }}"
                                        placeholder="Masukkan nama pimpinan">
                                    @error("bank.$idx.pimpinan")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold required">KEPALA LEGAL</label>
                                    <div class="kepala_legal_bank_{{ $idx }}">
                                        <select name="bank[{{ $idx }}][kepala_legal]" class="form-select select2 @error("bank.$idx.kepala_legal") is-invalid @enderror"
                                            data-placeholder="Pilih Kepala Legal">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    @error("bank.$idx.kepala_legal")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold required">LEGAL</label>
                                    <div class="legal_bank_{{ $idx }}">
                                        <select name="bank[{{ $idx }}][legal]" class="form-select select2 @error("bank.$idx.legal") is-invalid @enderror"
                                            data-placeholder="Pilih Legal">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    @error("bank.$idx.legal")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold required">KEPALA MARKETING</label>
                                    <div class="kepala_marketing_bank_{{ $idx }}">
                                        <select name="bank[{{ $idx }}][kepala_marketing]" class="form-select select2 @error("bank.$idx.kepala_marketing") is-invalid @enderror"
                                            data-placeholder="Pilih Kepala Marketing">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    @error("bank.$idx.kepala_marketing")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold required">MARKETING</label>
                                    <div class="marketing_bank_{{ $idx }}">
                                        <select name="bank[{{ $idx }}][marketing]" class="form-select select2 @error("bank.$idx.marketing") is-invalid @enderror"
                                            data-placeholder="Pilih Marketing">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    @error("bank.$idx.marketing")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <div class="p-3 border border-2 border-dashed border-secondary border-opacity-25 rounded-3 bg-light bg-opacity-25">
                                        <label class="form-label text-dark small fw-bold mb-1">DOKUMEN LAMPIRAN BANK</label>
                                        <p class="text-muted small mb-2" style="font-size: 0.75rem;">Unggah beberapa dokumen pendukung bank (bisa pilih lebih dari satu file).</p>
                                        <div class="file_bank_{{ $idx }}">
                                            <!-- PERUBAHAN MULTIPLE UPLOAD DI SINI -->
                                            <input type="file"
                                                class="form-control bg-white @error("bank.$idx.file") is-invalid @enderror"
                                                name="bank[{{ $idx }}][file][]" multiple>
                                        </div>
                                        @error("bank.$idx.file")
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
                <button type="button" class="btn btn-outline-primary w-100 py-3 border-2 border-dashed fw-bold rounded-3 add_bank shadow-sm transition-hover" style="border-style: dashed !important;">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Form Bank Lainnya
                </button>
            </div>

            @if ($jobDivisi->status !== 'Batal Akad')
                <div class="d-flex justify-content-end mt-4 mb-5">
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm btn__simpan_data_bank">
                        Simpan Data Bank
                    </button>
                </div>
            @endif
        </form>

        <!-- Modal Konfirmasi Hapus Form Bank -->
        <div class="modal fade" id="modalDeleteBank" tabindex="-1" aria-hidden="true">
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
                            <button type="button" class="btn btn-danger btn-sm px-3 fw-bold" id="btn-confirm-remove-bank">Ya, Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @push('addScript')
            <script>
                $(document).ready(function() {
                    $(".btn__simpan_data_bank").on("click", function() {
                        $(this).prop("disabled", true);
                        $(this).text("Menyimpan...");
                        $(".loading__global").show();
                        $("#form_bank").submit();
                    });

                    const list_bank = @json($bank);

                    $(document).on("change", ".select_bank", function() {
                        const idx = $(this).attr('data-idx');
                        const id_bank = $(this).val();

                        const item = list_bank.find((item) => Number(item.id) == Number(id_bank));
                        if (item) {
                            $(`.pimpinan_bank_${idx}`).val(item.nama_pimpinan_sekarang);
                            generateItemLegal(item, idx);
                            generateItemMarketing(item, idx);
                        }
                    });

                    const generateItemLegal = (item, idx) => {
                        $(`.kepala_legal_bank_${idx} select`).empty();
                        $(`.legal_bank_${idx} select`).empty();

                        $(`.kepala_legal_bank_${idx} select`).append(
                            `<option value="">Pilih Kepala Legal</option>`
                        );
                        if (item.kepala_legal) {
                            item.kepala_legal.forEach((row) => {
                                $(`.kepala_legal_bank_${idx} select`).append(
                                    `<option value="${row.id}">${row.nama}</option>`
                                );
                            });
                        }

                        $(`.legal_bank_${idx} select`).append(
                            `<option value="">Pilih Legal</option>`
                        );
                        if (item.legal) {
                            item.legal.forEach((row) => {
                                $(`.legal_bank_${idx} select`).append(
                                    `<option value="${row.id}">${row.nama}</option>`
                                );
                            });
                        }
                    };

                    const generateItemMarketing = (item, idx) => {
                        $(`.kepala_marketing_bank_${idx} select`).empty();
                        $(`.marketing_bank_${idx} select`).empty();

                        $(`.kepala_marketing_bank_${idx} select`).append(
                            `<option value="">Pilih Kepala Marketing</option>`
                        );
                        if (item.kepala_marketing) {
                            item.kepala_marketing.forEach((row) => {
                                $(`.kepala_marketing_bank_${idx} select`).append(
                                    `<option value="${row.id}">${row.nama}</option>`
                                );
                            });
                        }

                        $(`.marketing_bank_${idx} select`).append(
                            `<option value="">Pilih Marketing</option>`
                        );
                        if (item.marketing) {
                            item.marketing.forEach((row) => {
                                $(`.marketing_bank_${idx} select`).append(
                                    `<option value="${row.id}">${row.nama}</option>`
                                );
                            });
                        }
                    };

                    let bankIndexToRemove = null;

                    window.removeBank = function(idx) {
                        bankIndexToRemove = idx;
                        let myModal = new bootstrap.Modal(document.getElementById('modalDeleteBank'));
                        myModal.show();
                    };

                    $("#btn-confirm-remove-bank").on("click", function() {
                        if (bankIndexToRemove !== null) {
                            $(`.card-body-bank .card_bank_${bankIndexToRemove}`).fadeOut(300, function() {
                                $(this).remove();
                            });
                            $('#modalDeleteBank').modal('hide');
                            bankIndexToRemove = null;
                        }
                    });

                    const bankTemplate = (i) => `
                    <div class="card shadow-sm border border-light-subtle rounded-3 card_bank_${i} mt-4" data-index="${i}" style="display:none;">
                        <div class="card-header bg-light bg-opacity-50 py-3 border-bottom border-light-subtle d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-dark">
                                <i class="bi bi-bank2 me-2 text-primary"></i>Form Data Bank
                            </h6>
                            <button type="button" class="btn btn-sm btn-outline-danger border-0 px-2 py-1" onclick="removeBank(${i})" title="Hapus Form Ini">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </div>
                        <div class="card-body pt-3">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold required">NAMA BANK</label>
                                    <select name="bank[${i}][bank]" class="form-select select2 select_bank" data-placeholder="Pilih Bank" data-idx="${i}">
                                        <option value=""></option>
                                        @foreach ($bank as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold required">NAMA PIMPINAN</label>
                                    <input type="text" class="form-control pimpinan_bank_${i}" name="bank[${i}][pimpinan]" placeholder="Masukkan nama pimpinan">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold required">KEPALA LEGAL</label>
                                    <div class="kepala_legal_bank_${i}">
                                        <select name="bank[${i}][kepala_legal]" class="form-select select2" data-placeholder="Pilih Kepala Legal">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold required">LEGAL</label>
                                    <div class="legal_bank_${i}">
                                        <select name="bank[${i}][legal]" class="form-select select2" data-placeholder="Pilih Legal">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold required">KEPALA MARKETING</label>
                                    <div class="kepala_marketing_bank_${i}">
                                        <select name="bank[${i}][kepala_marketing]" class="form-select select2" data-placeholder="Pilih Kepala Marketing">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold required">MARKETING</label>
                                    <div class="marketing_bank_${i}">
                                        <select name="bank[${i}][marketing]" class="form-select select2" data-placeholder="Pilih Marketing">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="p-3 border border-2 border-dashed border-secondary border-opacity-25 rounded-3 bg-light bg-opacity-25">
                                        <label class="form-label text-dark small fw-bold mb-1">DOKUMEN LAMPIRAN BANK</label>
                                        <p class="text-muted small mb-2" style="font-size: 0.75rem;">Unggah beberapa dokumen pendukung bank (bisa pilih lebih dari satu file).</p>
                                        <div class="file_bank_${i}">
                                            <!-- PERUBAHAN MULTIPLE UPLOAD DI SINI (JS TEMPLATE) -->
                                            <input type="file" class="form-control bg-white" name="bank[${i}][file][]" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;

                    let i_bank = {{ $lastIndex }};

                    $(".add_bank").on("click", function() {
                        i_bank++;
                        const html = bankTemplate(i_bank);
                        $(".card-body-bank").append(html);

                        $(`.card_bank_${i_bank}`).fadeIn(300);

                        $(`.card_bank_${i_bank} .select2`).select2({
                            theme: 'bootstrap-5',
                            width: '100%'
                        });
                    });

                    $('.card-body-bank .select2').select2({
                        theme: 'bootstrap-5',
                        width: '100%'
                    });
                });
            </script>
        @endpush
    @endif
@endcan
