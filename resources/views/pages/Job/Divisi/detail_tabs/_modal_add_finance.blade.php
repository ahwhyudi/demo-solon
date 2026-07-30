<form action="{{ route('job.detail-divisi-finance.store') }}" method="post" id="form_add_finance">
    @csrf

    <input type="text" name="job_divisi_id" value="{{ $jobDivisi->id }}" hidden>
    <div class="modal fade" id="addFinance" tabindex="-1" aria-labelledby="addFinance" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addFinance">Tambah Finance Control</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="list_finance">
                        <div class="card_finance_0">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="" class="form-label required">
                                        Metode Pembayaran
                                    </label>
                                    <input type="text" class="form-control" name="metode_pembayaran[]" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="" class="form-label required">
                                        Keterangan
                                    </label>
                                    <input type="text" class="form-control" name="keterangan[]" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="" class="form-label required">
                                        Total
                                    </label>
                                    <input type="text" class="form-control money" name="total[]" required>
                                </div>

                                <div class="col-md-4">
                                    <label for="tipe-select" class="form-label required">
                                        Tipe
                                    </label>
                                    <select name="tipe[]" required id="tipe-select" class="form-select">
                                        <option value="in" selected>
                                            IN
                                        </option>
                                        <option value="out">
                                            OUT
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="peruntukan-in" class="form-label required">
                                        Peruntukan
                                    </label>
                                    <select name="peruntukan[]" required id="peruntukan-in"
                                        class="form-select peruntukan-select">
                                        <option value="pajak" selected>
                                            Pajak
                                        </option>
                                        <option value="proses">
                                            Proses
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="" class="form-label required">
                                        Date
                                    </label>

                                    <input type="date" class="form-control" value="{{ now()->format('Y-m-d') }}"
                                        name="date[]" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="" class="form-label required">
                                        Invoice
                                    </label>
                                    <select name="invoice[]" required class="form-select" required>
                                        <option value="" disabled>
                                            Pilih Invoice
                                        </option>
                                        @foreach ($jobDivisi->invoice as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->kode }} ({{ $item->kategori }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <div class="btn btn-danger  " onclick="removeFinance(0)">
                                        Hapus
                                    </div>
                                </div>
                            </div>

                            <hr>
                        </div>
                    </div>
                    <div class="add_more add_more_finance text-center py-2">
                        Tambah Pembayaran
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary submit_form_add_finance">Simpan Data</button>
                </div>
            </div>
        </div>
    </div>
</form>


@push('addScript')
    <script>
        const removeFinance = (idx) => {
            $(`.card_finance_${idx}`).remove();
        }

        $("#form_add_finance").on("submit", function(e) {
            $(".loading__global").show();
        });

        let i = 0;
        $(".add_more_finance").on("click", function() {
            i++;
            $("#form_add_finance .list_finance").append(`
                    <div class="card_finance_${i}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="" class="form-label required">
                                    Metode Pembayaran
                                </label>
                                <input type="text" class="form-control" name="metode_pembayaran[]" required>
                            </div>
                            <div class="col-md-4">
                                <label for="" class="form-label required">
                                    Keterangan
                                </label>
                                <input type="text" class="form-control" name="keterangan[]" required>
                            </div>
                            <div class="col-md-4">
                                <label for="" class="form-label required">
                                    Total
                                </label>
                                <input type="text" class="form-control money" name="total[]" required>
                            </div>

                            <div class="col-md-4">
                                <label for="tipe-select" class="form-label required">
                                    Tipe
                                </label>
                                <select name="tipe[]" required id="tipe-select" class="form-select">
                                    <option value="in" selected>
                                        IN
                                    </option>
                                    <option value="out">
                                        OUT
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="peruntukan-in" class="form-label required">
                                    Peruntukan
                                </label>
                                <select name="peruntukan[]" required id="peruntukan-in"
                                    class="form-select peruntukan-select">
                                    <option value="pajak" selected>
                                        Pajak
                                    </option>
                                    <option value="proses">
                                        Proses
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="" class="form-label required">
                                    Date
                                </label>

                                <input type="date" class="form-control" value="{{ now()->format('Y-m-d') }}"
                                    name="date[]" required>
                            </div>
                            <div class="col-md-4">
                                <label for="" class="form-label required">
                                    Invoice
                                </label>
                                <select name="invoice[]" required class="form-select" required>
                                    <option value="" disabled>
                                        Pilih Invoice
                                    </option>
                                    @foreach ($jobDivisi->invoice as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->kode }} ({{ $item->kategori }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
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
