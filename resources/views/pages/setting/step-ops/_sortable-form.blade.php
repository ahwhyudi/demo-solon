<div class="row g-4">
    <div class="{{ ($isModal ?? false) ? 'col-lg-6' : 'col-lg-5' }}">
        <div class="card">
            <div class="card-body">
                <input type="hidden" name="_form_type" value="{{ $formType ?? 'step-ops' }}">

                <div class="mb-3">
                    <label for="nama_step" class="form-label required">Nama Step</label>
                    <input type="text" name="nama_step" id="nama_step_{{ $formKey }}" class="form-control"
                        value="{{ old('nama_step', $item->nama_step ?? null) }}">
                </div>

                <div class="mb-3 d-flex flex-column gap-2">
                    <label class="form-check">
                        <input type="hidden" name="data_objek" value="0">
                        <input type="checkbox" class="form-check-input" name="data_objek"
                            id="data_objek_{{ $formKey }}" value="1"
                            {{ old('data_objek', $item->data_objek ?? false) ? 'checked' : '' }}>
                        <span class="form-check-label">Step ini membutuhkan data objek</span>
                    </label>

                    <label class="form-check">
                        <input type="hidden" name="penugasan_staff" value="0">
                        <input type="checkbox" class="form-check-input" name="penugasan_staff"
                            id="penugasan_staff_{{ $formKey }}" value="1"
                            {{ old('penugasan_staff', $item->penugasan_staff ?? false) ? 'checked' : '' }}>
                        <span class="form-check-label">Step ini membutuhkan penugasan staff</span>
                    </label>

                    <label class="form-check">
                        <input type="hidden" name="konfirmasi" value="0">
                        <input type="checkbox" class="form-check-input" name="konfirmasi"
                            id="konfirmasi_{{ $formKey }}" value="1"
                            {{ old('konfirmasi', $item->konfirmasi ?? false) ? 'checked' : '' }}>
                        <span class="form-check-label">Step ini butuh konfirmasi</span>
                    </label>
                </div>

                <div class="alert alert-info mb-0">
                    Geser kartu pada panel kanan untuk menentukan step paling awal sampai step paling akhir.
                </div>
            </div>
        </div>
    </div>

    <div class="{{ ($isModal ?? false) ? 'col-lg-6' : 'col-lg-7' }}">
        <div class="card">
            <div class="card-header">
                <strong>Urutan Step Operasional</strong>
            </div>
            <div class="card-body">
                <div id="sortable-step-list-{{ $formKey }}" class="d-flex flex-column gap-3">
                    @foreach ($items as $step)
                        @if (!empty($item) && (int) $item->id === (int) $step->id)
                            <div class="border rounded p-3 bg-warning-lt sortable-step-item" data-order-token="current">
                                <div class="d-flex justify-content-between align-items-center gap-3">
                                    <div>
                                        <div class="fw-bold" id="preview-nama-step-{{ $formKey }}">
                                            {{ old('nama_step', $item->nama_step) ?: 'Step ini sedang diedit' }}
                                        </div>
                                        <div class="text-muted small" id="preview-data-objek-step-{{ $formKey }}">
                                            {{ old('data_objek', $item->data_objek ?? false) ? 'Butuh data objek' : 'Tanpa data objek' }}
                                        </div>
                                        <div class="text-muted small mt-1" id="preview-penugasan-staff-step-{{ $formKey }}">
                                            {{ old('penugasan_staff', $item->penugasan_staff ?? false) ? 'Butuh penugasan staff' : 'Tanpa penugasan staff' }}
                                        </div>
                                        <div class="text-muted small mt-1" id="preview-konfirmasi-step-{{ $formKey }}">
                                            {{ old('konfirmasi', $item->konfirmasi ?? false) ? 'Butuh konfirmasi' : 'Tanpa konfirmasi' }}
                                        </div>
                                    </div>
                                    <span class="badge bg-warning text-dark">Step yang diedit</span>
                                </div>
                            </div>
                        @else
                            <div class="border rounded p-3 sortable-step-item" data-order-token="existing-{{ $step->id }}">
                                <div class="d-flex justify-content-between align-items-center gap-3">
                                    <div>
                                        <div class="fw-bold">{{ $step->nama_step }}</div>
                                        <div class="text-muted small">
                                            {{ $step->data_objek ? 'Butuh data objek' : 'Tanpa data objek' }}
                                        </div>
                                        <div class="text-muted small mt-1">
                                            {{ $step->penugasan_staff ? 'Butuh penugasan staff' : 'Tanpa penugasan staff' }}
                                        </div>
                                        <div class="text-muted small mt-1">
                                            {{ $step->konfirmasi ? 'Butuh konfirmasi' : 'Tanpa konfirmasi' }}
                                        </div>
                                    </div>
                                    <span class="badge bg-secondary">Urutan {{ $step->urutan }}</span>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    @if (empty($item))
                        <div class="border rounded p-3 bg-primary-lt sortable-step-item" data-order-token="new">
                            <div class="d-flex justify-content-between align-items-center gap-3">
                                <div>
                                    <div class="fw-bold" id="preview-nama-step-{{ $formKey }}">Step baru</div>
                                    <div class="text-muted small" id="preview-data-objek-step-{{ $formKey }}">Tanpa data objek</div>
                                    <div class="text-muted small mt-1" id="preview-penugasan-staff-step-{{ $formKey }}">
                                        Tanpa penugasan staff
                                    </div>
                                    <div class="text-muted small mt-1" id="preview-konfirmasi-step-{{ $formKey }}">Tanpa konfirmasi</div>
                                </div>
                                <span class="badge bg-primary">Step baru</span>
                            </div>
                        </div>
                    @endif
                </div>

                <div id="sortable-order-inputs-{{ $formKey }}"></div>

                <div class="mt-3">
                    <small class="text-muted">Urutan dibaca dari atas ke bawah.</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-4 d-flex gap-2">
    <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
    @if (($isModal ?? false) === false)
        <a href="{{ route('setting.step-ops.index') }}" class="btn btn-light">Kembali</a>
    @endif
</div>

@push('addScript')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
    <script>
        const sortableStepList{{ $formKey }} = document.getElementById('sortable-step-list-{{ $formKey }}');
        const sortableOrderInputs{{ $formKey }} = document.getElementById('sortable-order-inputs-{{ $formKey }}');
        const namaStepInput{{ $formKey }} = document.getElementById('nama_step_{{ $formKey }}');
        const dataObjekInput{{ $formKey }} = document.getElementById('data_objek_{{ $formKey }}');
        const penugasanStaffInput{{ $formKey }} = document.getElementById('penugasan_staff_{{ $formKey }}');
        const konfirmasiInput{{ $formKey }} = document.getElementById('konfirmasi_{{ $formKey }}');
        const previewNamaStep{{ $formKey }} = document.getElementById('preview-nama-step-{{ $formKey }}');
        const previewDataObjekStep{{ $formKey }} = document.getElementById('preview-data-objek-step-{{ $formKey }}');
        const previewPenugasanStaffStep{{ $formKey }} = document.getElementById('preview-penugasan-staff-step-{{ $formKey }}');
        const previewKonfirmasiStep{{ $formKey }} = document.getElementById('preview-konfirmasi-step-{{ $formKey }}');

        function syncOrderInputs{{ $formKey }}() {
            sortableOrderInputs{{ $formKey }}.innerHTML = '';

            sortableStepList{{ $formKey }}.querySelectorAll('.sortable-step-item').forEach((item) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'order[]';
                input.value = item.dataset.orderToken;
                sortableOrderInputs{{ $formKey }}.appendChild(input);
            });
        }

        function syncPreview{{ $formKey }}() {
            if (previewNamaStep{{ $formKey }}) {
                previewNamaStep{{ $formKey }}.textContent = namaStepInput{{ $formKey }}.value.trim() || 'Step baru';
            }

            if (previewDataObjekStep{{ $formKey }}) {
                previewDataObjekStep{{ $formKey }}.textContent = dataObjekInput{{ $formKey }} && dataObjekInput{{ $formKey }}.checked ?
                    'Butuh data objek' : 'Tanpa data objek';
            }

            if (previewPenugasanStaffStep{{ $formKey }}) {
                previewPenugasanStaffStep{{ $formKey }}.textContent = penugasanStaffInput{{ $formKey }} && penugasanStaffInput{{ $formKey }}.checked ?
                    'Butuh penugasan staff' : 'Tanpa penugasan staff';
            }

            if (previewKonfirmasiStep{{ $formKey }}) {
                previewKonfirmasiStep{{ $formKey }}.textContent = konfirmasiInput{{ $formKey }} && konfirmasiInput{{ $formKey }}.checked ?
                    'Butuh konfirmasi' : 'Tanpa konfirmasi';
            }
        }

        new Sortable(sortableStepList{{ $formKey }}, {
            animation: 150,
            ghostClass: 'bg-yellow-lt',
            onSort: syncOrderInputs{{ $formKey }},
        });

        syncOrderInputs{{ $formKey }}();
        syncPreview{{ $formKey }}();

        if (namaStepInput{{ $formKey }}) {
            namaStepInput{{ $formKey }}.addEventListener('input', syncPreview{{ $formKey }});
        }

        if (dataObjekInput{{ $formKey }}) {
            dataObjekInput{{ $formKey }}.addEventListener('change', syncPreview{{ $formKey }});
        }

        if (penugasanStaffInput{{ $formKey }}) {
            penugasanStaffInput{{ $formKey }}.addEventListener('change', syncPreview{{ $formKey }});
        }

        if (konfirmasiInput{{ $formKey }}) {
            konfirmasiInput{{ $formKey }}.addEventListener('change', syncPreview{{ $formKey }});
        }
    </script>
@endpush
