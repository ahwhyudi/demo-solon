<!-- Button trigger modal -->
<button
    type="button"
    class="btn btn-warning"
    data-bs-toggle="modal"
    data-bs-target="#modalInputVa{{ $key }}"
>
    Nomor VA
</button>

<form
    action="{{ route('job.pnbp.input-va', $item->pnbp->id) }}"
    method="POST"
    class="form-pnbp"
    id="formInputVa{{ $key }}"
>
    @csrf
    @method('PUT')

    <!-- Modal -->
    <div
        class="modal fade"
        id="modalInputVa{{ $key }}"
        tabindex="-1"
        aria-labelledby="modalInputVa{{ $key }}Label"
        aria-hidden="true"
    >

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">

                    <div>

                        <h1
                            class="modal-title fs-5"
                            id="modalInputVa{{ $key }}Label"
                        >
                            Input Nomor VA
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

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Petugas
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $item->pnbp?->user?->name ?? '-' }}"
                            disabled
                        >

                    </div>

                    <div class="mb-3">

                        <label class="form-label required">
                            Nomor VA
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            name="va"
                            required
                            inputmode="numeric"
                            autocomplete="off"
                            placeholder="Masukkan nomor VA"
                            value="{{ old('va', $item->pnbp->va) }}"
                        >

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Close
                    </button>

                    <button
                        type="submit"
                        class="btn btn-warning btn_submit_va"
                    >
                        Simpan VA
                    </button>

                </div>

            </div>

        </div>

    </div>
</form>

@push('addScript')
<script>

    $("#formInputVa{{ $key }}").on("submit", function() {

        $(this)
            .find("button[type='submit']")
            .prop("disabled", true);

        $(".loading__global").show();

    });

</script>
@endpush