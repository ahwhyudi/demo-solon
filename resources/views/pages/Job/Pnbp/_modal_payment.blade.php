<!-- Button trigger modal -->
<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalPayment{{ $key }}">
    Bayar
</button>

<form action="{{ route('job.pnbp.payment', $item->pnbp->id) }}" method="POST" class="form-pnbp"
    id="formPayment{{ $key }}">
    @csrf
    @method('PUT')

    <!-- Modal -->
    <div class="modal fade" id="modalPayment{{ $key }}" tabindex="-1"
        aria-labelledby="modalPayment{{ $key }}Label" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">

                    <div>

                        <h1 class="modal-title fs-5" id="modalPayment{{ $key }}Label">
                            Pembayaran PNBP
                        </h1>

                        <small>

                            @if ($item->jobDivisi?->objek?->isNotEmpty())
                                Nomor Objek :

                                {{ $item->jobDivisi?->objek?->pluck('no_sertifikat')->implode(', ') }}
                            @else
                                <span class="text-warning">
                                    Belum di input no sertifikat
                                </span>
                            @endif

                        </small>

                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Nomor VA
                        </label>

                        <input type="text" class="form-control" value="{{ $item->pnbp->va }}" disabled>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Petugas
                        </label>

                        <input type="text" class="form-control" value="{{ $item->pnbp?->user?->name ?? '-' }}"
                            disabled>

                    </div>

                    <div class="mb-3">

                        <label class="form-label required">
                            Nominal Pembayaran
                        </label>
{{-- {{ dd($item) }} --}}
                        <input type="text" name="nominal" class="form-control"
                            value="{{ number_format($item->harga_jual ?? 0, 0, ',', '.') }}">

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>

                    <button type="submit" class="btn btn-success btn_submit_payment">
                        Bayar
                    </button>

                </div>

            </div>

        </div>

    </div>
</form>

@push('addScript')
    <script>
        $("#formPayment{{ $key }}").on("submit", function() {

            $(this)
                .find("button[type='submit']")
                .prop("disabled", true);

            $(".loading__global").show();

        });
    </script>
@endpush
