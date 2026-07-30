@can('data-pendukung/broker/view')
    <x-job.detail.list-broker-component :list-broker="$jobDivisi->listBroker" />
@endcan

@can('data-pendukung/broker/create')
    @if (!count($jobDivisi->listBroker))
        <form action="{{ route('job.divisi-data-pendukung.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="text" hidden name="job_divisi_id" value="{{ $jobDivisi->id }}">
            
            <div class="body__broker">
                @php
                    $oldBrokers = old('broker') ?? (session('form_data.broker') ?? [[
                        'broker' => '', 
                        'nama_pt' => '', 
                        'nama_pimpinan' => '',
                        'nama_agent' => ''
                    ]]);

                    $lastIndex = is_array($oldBrokers) ? array_key_last($oldBrokers) : -1;
                    if ($lastIndex === null) {
                        $lastIndex = -1;
                    }
                @endphp

                @foreach ($oldBrokers as $idx => $o)
                    <div class="card shadow-sm border border-light-subtle rounded-3 card_broker_{{ $idx }} @if ($idx > 0) mt-4 @endif" data-index="{{ $idx }}">
                        <div class="card-header bg-light bg-opacity-50 py-3 border-bottom border-light-subtle d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-dark">
                                <i class="bi bi-briefcase me-2 text-primary"></i>Form Data Broker
                            </h6>
                            @if ($idx > 0)
                                <button type="button" class="btn btn-sm btn-outline-danger border-0 px-2 py-1" onclick="removeBroker({{ $idx }})" title="Hapus Form Ini">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            @endif
                        </div>
                        <div class="card-body pt-3">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold required">NAMA BROKER</label>
                                    <select name="broker[{{ $idx }}][broker]"
                                        class="form-select select2 select_broker" data-idx="{{ $idx }}"
                                        data-placeholder="Pilih Broker">
                                        <option value=""></option>
                                        @foreach ($broker as $item)
                                            <option value="{{ $item->id }}"
                                                {{ old("broker.$idx.broker", $o['broker'] ?? '') == $item->id ? 'selected' : '' }}>
                                                {{ $item->nama_perumahan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error("broker.$idx.broker")
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold required">NAMA PT</label>
                                    <input type="text"
                                        class="form-control nama_pt_{{ $idx }} @error("broker.$idx.nama_pt") is-invalid @enderror"
                                        name="broker[{{ $idx }}][nama_pt]" readonly
                                        value="{{ old("broker.$idx.nama_pt", $o['nama_pt'] ?? '') }}"
                                        placeholder="Otomatis terisi">
                                    @error("broker.$idx.nama_pt")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold required">NAMA PIMPINAN</label>
                                    <input type="text"
                                        class="form-control nama_pimpinan_{{ $idx }} @error("broker.$idx.nama_pimpinan") is-invalid @enderror"
                                        name="broker[{{ $idx }}][nama_pimpinan]" readonly
                                        value="{{ old("broker.$idx.nama_pimpinan", $o['nama_pimpinan'] ?? '') }}"
                                        placeholder="Otomatis terisi">
                                    @error("broker.$idx.nama_pimpinan")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold required">NAMA AGENT</label>
                                    <div class="nama_agent_{{ $idx }}">
                                        <select name="broker[{{ $idx }}][nama_agent]"
                                            class="form-select select2" data-placeholder="Pilih Agent">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    @error("broker.$idx.nama_agent")
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
                <button type="button" class="btn btn-outline-primary w-100 py-3 border-2 border-dashed fw-bold rounded-3 add_broker shadow-sm transition-hover" style="border-style: dashed !important;">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Form Broker Lainnya
                </button>
            </div>

            @if ($jobDivisi->status !== 'Batal Akad')
                <div class="d-flex justify-content-end mt-4 mb-5">
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm btn__simpan_broker">
                        Simpan Data Broker
                    </button>
                </div>
            @endif
        </form>

        <!-- Modal Konfirmasi Hapus Form Broker -->
        <div class="modal fade" id="modalDeleteBroker" tabindex="-1" aria-hidden="true">
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
                            <button type="button" class="btn btn-danger btn-sm px-3 fw-bold" id="btn-confirm-remove-broker">Ya, Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @push('addScript')
            <script>
                $(document).ready(function() {
                    $(".btn__simpan_broker").on("click", function() {
                        $(this).prop("disabled", true);
                        $(this).text("Menyimpan...");
                        $(".loading__global").show();
                        $(this).closest("form").submit();
                    });

                    const list_broker = @json($broker);

                    $(document).on("change", ".select_broker", function() {
                        const idx = $(this).attr("data-idx");
                        const broker = $(this).val();
                        generateChangeEventBroker(idx, broker);
                    });

                    const generateChangeEventBroker = (idx, broker) => {
                        const item = list_broker.find((item) => Number(item.id) == Number(broker));
                        $(`.nama_pt_${idx}`).val(item ? item.nama_pt : '');
                        $(`.nama_pimpinan_${idx}`).val(item ? item.nama_pimpinan : '');

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
                    $('.body__broker .select2').select2({
                        theme: 'bootstrap-5',
                        width: '100%'
                    });

                    let i_broker = {{ $lastIndex }};
                    let brokerIndexToRemove = null;

                    // Template untuk append dinamis selaras dengan layout Debitur
                    const brokerTemplate = (i) => `
                    <div class="card shadow-sm border border-light-subtle rounded-3 card_broker_${i} mt-4" data-index="${i}" style="display:none;">
                        <div class="card-header bg-light bg-opacity-50 py-3 border-bottom border-light-subtle d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-dark">
                                <i class="bi bi-briefcase me-2 text-primary"></i>Form Data Broker
                            </h6>
                            <button type="button" class="btn btn-sm btn-outline-danger border-0 px-2 py-1" onclick="removeBroker(${i})" title="Hapus Form Ini">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </div>
                        <div class="card-body pt-3">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold required">NAMA BROKER</label>
                                    <select name="broker[${i}][broker]"
                                        class="form-select select2 select_broker" data-idx="${i}"
                                        data-placeholder="Pilih Broker">
                                        <option value=""></option>
                                        @foreach ($broker as $item)
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
                                        name="broker[${i}][nama_pt]" readonly
                                        value="" placeholder="Otomatis terisi">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold required">NAMA PIMPINAN</label>
                                    <input type="text"
                                        class="form-control nama_pimpinan_${i}"
                                        name="broker[${i}][nama_pimpinan]" readonly
                                        value="" placeholder="Otomatis terisi">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label text-secondary small fw-bold required">NAMA AGENT</label>
                                    <div class="nama_agent_${i}">
                                        <select name="broker[${i}][nama_agent]"
                                            class="form-select select2" data-placeholder="Pilih Agent">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;

                    $(".add_broker").on("click", function() {
                        i_broker++;
                        let i = i_broker;

                        $(".body__broker").append(brokerTemplate(i));
                        
                        // Inisialisasi select2 untuk baris baru
                        $(`.card_broker_${i} .select2`).select2({
                            theme: 'bootstrap-5',
                            width: '100%'
                        });

                        $(`.card_broker_${i}`).fadeIn(300);
                    });

                    window.removeBroker = function(idx) {
                        brokerIndexToRemove = idx;
                        let myModal = new bootstrap.Modal(document.getElementById('modalDeleteBroker'));
                        myModal.show();
                    };

                    $("#btn-confirm-remove-broker").on("click", function() {
                        if (brokerIndexToRemove !== null) {
                            $(`.body__broker .card_broker_${brokerIndexToRemove}`).fadeOut(300, function() {
                                $(this).remove();
                            });
                            $('#modalDeleteBroker').modal('hide');
                            brokerIndexToRemove = null;
                        }
                    });
                });
            </script>
        @endpush
    @endif
@endcan