<!-- Button trigger modal -->
<button type="button" class="btn btn-outline-info d-inline-flex align-items-center gap-2 px-3 py-1.5 shadow-sm fw-medium" data-bs-toggle="modal" data-bs-target="#modalPPat{{ $key }}">
    <i class="bi bi-file-earmark-text"></i>
    <span>Nomor {{ strtoupper($tipe) }}</span>
</button>

<!-- Modal -->
<div class="modal fade" id="modalPPat{{ $key }}" tabindex="-1" aria-labelledby="modalPPat{{ $key }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            
            {{-- Header Modal: Bersih dengan badge status --}}
            <div class="modal-header bg-white border-bottom py-3 px-4">
                <div>
                    <h1 class="modal-title fs-5 fw-bold text-dark mb-1" id="modalPPat{{ $key }}Label">
                        Form Penginputan Nomor {{ strtoupper($tipe) }}
                    </h1>
                    <div class="d-flex align-items-center gap-2 mt-1">
                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1 fw-medium text-uppercase" style="font-size: 0.70rem;">
                            Proses: {{ $nama_proses }}
                        </span>
                    </div>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Body Modal: Padding lega dan form control shadow-sm --}}
            <div class="modal-body p-4">
                <form action="{{ route('job.notaris.simpanNomorPPAT') }}" id="form_ppat{{ $key }}" method="post">
                    @csrf
                    <input type="text" value="{{ $formOrder->id }}" name="form_id" hidden>
                    <input type="text" value="{{ $formOrder->kategori }}" name="kategori" hidden>
                    
                    @if ($formOrder->nomorPpat)
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary small mb-2 required">
                                Nomor {{ strtoupper($tipe) }}
                            </label>
                            <input type="text" autocomplete="off" class="form-control shadow-sm"
                                value="{{ $formOrder->nomorPpat->nomor ?? '' }}" name="nomor">
                            <div class="form-text mt-2 text-muted small">
                                <i class="bi bi-clock-history me-1"></i>
                                {{ \Carbon\Carbon::parse($formOrder->nomorPpat->tanggal)->format('d/M/Y') }}
                            </div>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-secondary small mb-2 required">
                            Tanggal {{ strtoupper($tipe) }}
                        </label>
                        <input type="date" autocomplete="off" value="{{ now()->format('Y-m-d') }}"
                            class="form-control shadow-sm" name="tanggal_nomor">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-secondary small mb-2">
                            Tanggal Perpanjangan
                        </label>
                        <input type="date" autocomplete="off"
                            value="{{ $formOrder->nomorPpat->tanggal_expired ?? '' }}" class="form-control shadow-sm"
                            name="tanggal_expired">
                    </div>

                    <div class="d-flex p-3 bg-light rounded-3 border mb-3">
                        <div class="form-check form-switch mb-0">
                            @php
                                $rekanan = $formOrder->nomorPpat->rekanan ?? 0;
                            @endphp
                            <input class="form-check-input" name="rekanan" {{ $rekanan ? 'checked' : '' }}
                                type="checkbox" role="switch" id="switchCheckNotarisRekanan{{ $key }}">
                            <label class="form-check-label fw-medium ms-2" for="switchCheckNotarisRekanan{{ $key }}">
                                Notaris Rekanan
                            </label>
                        </div>
                    </div>

                    @if (!$formOrder->nomorPpat)
                        <div class="mb-3 form_rekanan" style="display: none">
                            <label class="form-label fw-semibold text-secondary small mb-2 required">
                                Nomor {{ strtoupper($tipe) }} Rekanan
                            </label>
                            <input type="text" autocomplete="off" class="form-control shadow-sm" name="nomor_rekanan">
                        </div>
                    @endif
                </form>
            </div>

            {{-- Footer Modal: Background light dengan tombol terstruktur --}}
            <div class="modal-footer bg-light border-top-0 py-3 px-4 rounded-bottom-4">
                <button type="button" class="btn btn-outline-secondary px-4 fw-medium" data-bs-dismiss="modal">Tutup</button>
                @if (!$formOrder->nomorPpat)
                    <button type="button" class="btn btn-primary px-4 fw-medium shadow-sm d-flex align-items-center gap-2 btn__simpant_ppt{{ $key }}">
                        <i class="bi bi-save"></i>
                        <span>Simpan</span>
                    </button>
                @endif
            </div>

        </div>
    </div>
</div>

@push('addScript')
    <script>
        $(".btn__simpant_ppt{{ $key }}").on("click", function() {
            $(".loading__global").show();
            $("#form_ppat{{ $key }}").submit();
        });
    </script>

    @if (!$formOrder->nomorPpat)
        <script>
            // Sembunyikan default saat load
            $(".form_rekanan").hide();
            
            // Toggle form rekanan saat switch diubah
            $("#switchCheckNotarisRekanan{{ $key }}").on("change", function() {
                if ($(this).is(":checked")) {
                    $(".form_rekanan").slideDown('fast'); // Menggunakan slideDown agar transisi lebih smooth
                } else {
                    $(".form_rekanan").slideUp('fast'); // Menggunakan slideUp
                }
            });
        </script>
    @endif
@endpush