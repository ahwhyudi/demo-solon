@can('data-pendukung/developer/view')
    <x-job.detail.list-developer-component :list-developer="$jobDivisi->developer" />
@endcan

@can('data-pendukung/developer/create')
    @if (!count($jobDivisi->developer))
        <form action="{{ route('job.divisi-data-pendukung.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="text" hidden name="job_divisi_id" value="{{ $jobDivisi->id }}">
            
            <div class="body__developer">
                @php
                    $oldDevelopers = old('developer') ?? (session('form_data.developer') ?? [[
                        'developer' => '', 
                        'nama_pt' => '', 
                        'nama_agent' => ''
                    ]]);

                    $lastIndex = is_array($oldDevelopers) ? array_key_last($oldDevelopers) : -1;
                    if ($lastIndex === null) {
                        $lastIndex = -1;
                    }
                @endphp

                @foreach ($oldDevelopers as $idx => $o)
                    <div class="card shadow-sm border border-light-subtle rounded-3 card_developer_{{ $idx }} @if ($idx > 0) mt-4 @endif" data-index="{{ $idx }}">
                        <div class="card-header bg-light bg-opacity-50 py-3 border-bottom border-light-subtle d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-dark">
                                <i class="bi bi-building me-2 text-primary"></i>Form Data Developer
                            </h6>
                            @if ($idx > 0)
                                <button type="button" class="btn btn-sm btn-outline-danger border-0 px-2 py-1" onclick="removeDeveloper({{ $idx }})" title="Hapus Form Ini">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            @endif
                        </div>
                        <div class="card-body pt-3">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold required">NAMA DEVELOPER</label>
                                    <select name="developer[{{ $idx }}][developer]"
                                        class="form-select select2 select_developer" data-idx="{{ $idx }}"
                                        data-placeholder="Pilih Developer">
                                        <option value=""></option>
                                        @foreach ($developer as $item)
                                            <option value="{{ $item->id }}"
                                                {{ old("developer.$idx.developer", $o['developer'] ?? '') == $item->id ? 'selected' : '' }}>
                                                {{ $item->nama_perumahan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error("developer.$idx.developer")
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold required">NAMA PT</label>
                                    <input type="text"
                                        class="form-control nama_pt_{{ $idx }} @error("developer.$idx.nama_pt") is-invalid @enderror"
                                        name="developer[{{ $idx }}][nama_pt]" readonly
                                        value="{{ old("developer.$idx.nama_pt", $o['nama_pt'] ?? '') }}"
                                        placeholder="Otomatis terisi">
                                    @error("developer.$idx.nama_pt")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold required">NAMA AGENT</label>
                                    <div class="nama_agent_{{ $idx }}">
                                        <select name="developer[{{ $idx }}][nama_agent]"
                                            class="form-select select2" data-placeholder="Pilih Agent">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    @error("developer.$idx.nama_agent")
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Tombol Tambah Lebih Elegan -->
            <div class="mt-4">
                <button type="button" class="btn btn-outline-primary w-100 py-3 border-2 border-dashed fw-bold rounded-3 add_developer shadow-sm transition-hover" style="border-style: dashed !important;">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Form Developer Lainnya
                </button>
            </div>

            @if ($jobDivisi->status !== 'Batal Akad')
                <div class="d-flex justify-content-end mt-4 mb-5">
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm btn__simpan_data_developer">
                        Simpan Data Developer
                    </button>
                </div>
            @endif
        </form>

        <!-- Modal Konfirmasi Hapus Form Developer -->
        <div class="modal fade" id="modalDeleteDeveloper" tabindex="-1" aria-hidden="true">
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
                            <button type="button" class="btn btn-danger btn-sm px-3 fw-bold" id="btn-confirm-remove-developer">Ya, Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @push('addScript')
            <script>
                $(document).ready(function() {
                    $(".btn__simpan_data_developer").on("click", function() {
                        $(this).prop("disabled", true);
                        $(this).text("Menyimpan...");
                        $(".loading__global").show();
                        $(this).closest("form").submit();
                    });

                    const list_developer = @json($developer);

                    $(document).on("change", ".select_developer", function() {
                        const idx = $(this).attr("data-idx");
                        const developer = $(this).val();
                        generateChangeEventDeveloper(idx, developer);
                    });

                    const generateChangeEventDeveloper = (idx, developer) => {
                        const item = list_developer.find((item) => Number(item.id) == Number(developer));
                        $(`.nama_pt_${idx}`).val(item ? item.nama_pt : '');

                        const $agentSelect = $(`.nama_agent_${idx} select`);
                        $agentSelect.empty();
                        $agentSelect.append(`<option value="">Pilih Agent</option>`);
                        
                        if (item && item.marketing) {
                            item.marketing.forEach((mkt) => {
                                $agentSelect.append(`<option value="${mkt.id}">${mkt.nama}</option>`);
                            });
                        }
                        $agentSelect.trigger('change');
                    };

                    // Inisialisasi Select2 awal
                    $('.body__developer .select2').select2({
                        theme: 'bootstrap-5',
                        width: '100%'
                    });

                    let i_developer = {{ $lastIndex }};
                    let developerIndexToRemove = null;

                    // Template untuk append dinamis selaras dengan layout Debitur
                    const developerTemplate = (i) => `
                    <div class="card shadow-sm border border-light-subtle rounded-3 card_developer_${i} mt-4" data-index="${i}" style="display:none;">
                        <div class="card-header bg-light bg-opacity-50 py-3 border-bottom border-light-subtle d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-dark">
                                <i class="bi bi-building me-2 text-primary"></i>Form Data Developer
                            </h6>
                            <button type="button" class="btn btn-sm btn-outline-danger border-0 px-2 py-1" onclick="removeDeveloper(${i})" title="Hapus Form Ini">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </div>
                        <div class="card-body pt-3">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold required">NAMA DEVELOPER</label>
                                    <select name="developer[${i}][developer]"
                                        class="form-select select2 select_developer" data-idx="${i}"
                                        data-placeholder="Pilih Developer">
                                        <option value=""></option>
                                        @foreach ($developer as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->nama_perumahan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold required">NAMA PT</label>
                                    <input type="text"
                                        class="form-control nama_pt_${i}"
                                        name="developer[${i}][nama_pt]" readonly
                                        value="" placeholder="Otomatis terisi">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold required">NAMA AGENT</label>
                                    <div class="nama_agent_${i}">
                                        <select name="developer[${i}][nama_agent]"
                                            class="form-select select2" data-placeholder="Pilih Agent">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;

                    $(".add_developer").on("click", function() {
                        i_developer++;
                        let i = i_developer;

                        $(".body__developer").append(developerTemplate(i));
                        
                        // Inisialisasi select2 untuk baris baru
                        $(`.card_developer_${i} .select2`).select2({
                            theme: 'bootstrap-5',
                            width: '100%'
                        });

                        $(`.card_developer_${i}`).fadeIn(300);
                    });

                    window.removeDeveloper = function(idx) {
                        developerIndexToRemove = idx;
                        let myModal = new bootstrap.Modal(document.getElementById('modalDeleteDeveloper'));
                        myModal.show();
                    };

                    $("#btn-confirm-remove-developer").on("click", function() {
                        if (developerIndexToRemove !== null) {
                            $(`.body__developer .card_developer_${developerIndexToRemove}`).fadeOut(300, function() {
                                $(this).remove();
                            });
                            $('#modalDeleteDeveloper').modal('hide');
                            developerIndexToRemove = null;
                        }
                    });
                });
            </script>
        @endpush
    @endif
@endcan