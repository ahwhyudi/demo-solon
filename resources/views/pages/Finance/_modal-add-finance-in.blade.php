<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
    Tambah Data
</button>

<!-- Modal -->

<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Form Control Invoice</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('job.detail-divisi-finance.store') }}" method="post"
                    id="form_add_finance__data">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="" class="form-label required">
                                Pilih Parent
                            </label>

                            <select name="job_divisi_id" class="form-select pilih__parent"
                                data-placeholder="Pilih Parent">
                                <option value="">Pilih Parent</option>
                                @foreach ($jobDivisi as $item)
                                    <option value="{{ $item->id }}">{{ $item->kode }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

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

                            <input type="text" hidden value="in" name="tipe[]">

                            <div class="">
                                <label for="" class="form-label required">
                                    Peruntukan
                                </label>
                                <select name="peruntukan[]" class="form-select">
                                    <option value="pajak" selected>
                                        Pajak
                                    </option>
                                    <option value="proses">
                                        Proses
                                    </option>
                                </select>
                            </div>
                            <div class="">
                                <label for="" class="form-label required">
                                    Date
                                </label>

                                <input type="date" class="form-control" name="date[]">
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary submit_form_add_finance">Simpan Data</button>
            </div>
        </div>
    </div>
</div>

@push('addScript')
    <script>
        $('.pilih__parent').select2({
            theme: 'bootstrap-5',
            dropdownParent: $("#staticBackdrop")
        });


        const removeFinance = (idx) => {
            $(`.card_finance_${idx}`).remove();
        }

        $(".submit_form_add_finance").on("click", function() {
            $(".loading__global").show();
            $("#form_add_finance__data").submit();
        });

        let i = 2;
        $(".add_more_finance").on("click", function() {
            i++;

            $("#form_add_finance__data").append(`
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

                            <input type="text" hidden value="in" name="tipe[]">

                            <div class="">
                                <label for="" class="form-label required">
                                    Peruntukan
                                </label>
                                <select name="peruntukan[]" class="form-select">
                                    <option value="pajak" selected>
                                        Pajak
                                    </option>
                                    <option value="proses">
                                        Proses
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
            `)

            $('.money').mask('#.##0', {
                reverse: true
            });
        });
    </script>
@endpush
