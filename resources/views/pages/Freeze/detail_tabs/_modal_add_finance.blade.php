<div class="modal fade" id="addFinance" tabindex="-1" aria-labelledby="addFinance" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addFinance">Tambah Finance Control</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('job.detail-divisi-finance.store') }}" method="post" id="form_add_finance">
                    @csrf

                    <input type="text" name="job_divisi_id" value="{{ $jobDivisi->id }}" hidden>
                    <div class="card_finance_0">
                        <div class="d-flex gap-3 align-items-end">
                            <div class="">
                                <label for="" class="form-label required">
                                    Metode Pembayaran
                                </label>
                                <input type="text" class="form-control" name="metode_pembayaran[]">
                            </div>
                            <div class="">
                                <label for="" class="form-label required">
                                    Keterangan
                                </label>
                                <input type="text" class="form-control" name="keterangan[]">
                            </div>
                            <div class="">
                                <label for="" class="form-label required">
                                    Total
                                </label>
                                <input type="text" class="form-control money" name="total[]">
                            </div>
                            <div class="">
                                <label for="" class="form-label required">
                                    Tipe
                                </label>
                                <select name="tipe[]" class="form-select">
                                    <option value="in" selected>
                                        IN
                                    </option>
                                    <option value="out">
                                        OUT
                                    </option>
                                </select>
                            </div>
                            <div class="">
                                <label for="" class="form-label required">
                                    Date
                                </label>

                                <input type="date" class="form-control" name="date[]">
                            </div>
                            <div class="">
                                <div class="btn btn-danger  " onclick="removeFinance(0)">
                                    Hapus
                                </div>
                            </div>
                        </div>

                        <hr>
                    </div>
                </form>
                <div class="add_more add_more_finance text-center py-2">
                    Tambah Pembayaran
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary submit_form_add_finance">Save changes</button>
            </div>
        </div>
    </div>
</div>

@push('addScript')
    <script>
        const removeFinance = (idx) => {
            $(`.card_finance_${idx}`).remove();
        }

        $(".submit_form_add_finance").on("click", function() {
            $(".loading__global").show();
            $("#form_add_finance").submit();
        })

        let i = 0;
        $(".add_more_finance").on("click", function() {
            i++;
            $("#form_add_finance").append(`
                    <div class="card_finance_${i}">
                        <div class="d-flex gap-3 align-items-end">
                            <div class="">
                                <label for="" class="form-label required">
                                    Metode Pembayaran
                                </label>
                                <input type="text" class="form-control" name="metode_pembayaran[]">
                            </div>
                            <div class="">
                                <label for="" class="form-label required">
                                    Keterangan
                                </label>
                                <input type="text" class="form-control" name="keterangan[]">
                            </div>
                            <div class="">
                                <label for="" class="form-label required">
                                    Total
                                </label>
                                <input type="text" class="form-control money" name="total[]">
                            </div>
                            <div class="">
                                <label for="" class="form-label required">
                                    Tipe
                                </label>
                                <select name="tipe[]" class="form-select">
                                    <option value="in" selected>
                                        IN
                                    </option>
                                    <option value="out">
                                        OUT
                                    </option>
                                </select>
                            </div>
                            <div class="">
                                <label for="" class="form-label required">
                                    Date
                                </label>

                                <input type="date" class="form-control" name="date[]">
                            </div>
                            <div class="">

                                <div class="btn btn-danger  " onclick="removeFinance(${i})">
                                    Hapus
                                </div>
                            </div>
                        </div>

                        <hr>
                    </div>
            `);

            $('.money').mask('#.##0', {
                reverse: true
            });
        })
    </script>
@endpush
