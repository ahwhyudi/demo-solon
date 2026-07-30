<!-- Button trigger modal -->
<button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalPPat{{ $key }}">
    Nomor {{ strtoupper($tipe) }}
</button>

<!-- Modal -->
<div class="modal fade" id="modalPPat{{ $key }}" tabindex="-1" aria-labelledby="modalPPat{{ $key }}Label"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="">
                    <h1 class="modal-title fs-5" id="modalPPat{{ $key }}Label">Form Penginputan Nomor
                        {{ strtoupper($tipe) }}</h1>
                    <div class="text-secondary">
                        Proses : {{ $nama_proses }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('job.notaris.simpanNomorPPAT') }}" id="form_ppat{{ $key }}"
                    method="post">
                    @csrf
                    <input type="text" value="{{ $formOrder->id }}" name="form_id" hidden>
                    <input type="text" value="{{ $formOrder->kategori }}" name="kategori" hidden>
                    @if ($formOrder->nomorPpat)
                        <div class="mb-3">
                            <label for="" class="form-label required">
                                Nomor {{ strtoupper($tipe) }}
                            </label>
                            <input type="text" autocomplete="off" class="form-control"
                                value="{{ $formOrder->nomorPpat->nomor ?? '' }}" name="nomor">
                            <small>
                                {{ \Carbon\Carbon::parse($formOrder->nomorPpat->tanggal)->format('d/M/Y') }}
                            </small>
                        </div>
                    @endif
                    <div class="mb-3">
                        <label for="" class="form-label required">
                            Tanggal {{ strtoupper($tipe) }}
                        </label>
                        <input type="date" autocomplete="off" value="{{ now()->format('Y-m-d') }}"
                            class="form-control" name="tanggal_nomor">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">
                            Tanggal Perpanjangan
                        </label>
                        <input type="date" autocomplete="off"
                            value="{{ $formOrder->nomorPpat->tanggal_expired ?? '' }}" class="form-control"
                            name="tanggal_expired">
                    </div>

                    <div class=" d-flex">
                        <div class="form-check form-switch">
                            @php
                                $rekanan = $formOrder->nomorPpat->rekanan ?? 0;
                            @endphp
                            <input class="form-check-input" name="rekanan" {{ $rekanan ? 'checked' : '' }}
                                type="checkbox" role="switch" id="switchCheckNotarisRekanan{{ $key }}">
                            <label class="form-check-label" for="switchCheckNotarisRekanan{{ $key }}">Notaris
                                Rekanan</label>
                        </div>
                    </div>

                    @if (!$formOrder->nomorPpat)
                        <div class="mb-3 form_rekanan" style="display: none">
                            <label for="" class="form-label required">
                                Nomor {{ strtoupper($tipe) }} Rekanan
                            </label>
                            <input type="text" autocomplete="off" class="form-control" name="nomor_rekanan">

                        </div>
                    @endif
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                @if (!$formOrder->nomorPpat)
                    <button type="button" class="btn btn-primary btn__simpant_ppt{{ $key }}">Simpan</button>
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
            $(".form_rekanan").hide();
            $("#switchCheckNotarisRekanan{{ $key }}").on("change", function() {
                if ($(this).is(":checked")) {
                    $(".form_rekanan").show();
                } else {
                    $(".form_rekanan").hide();
                }
            });
        </script>
    @endif
@endpush
