@if ($jobDivisi->status !== 'Batal Akad')
    <div class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalAddItem">
        Tambah Item
    </div>
@endif
<form action="{{ route('job.addItemJobDivisi') }}" method="post" id="formAddItem">
    @csrf
    <input type="text" value="{{ $jobDivisi->id }}" name="job_divisi_id" hidden>
    <div class="modal fade" id="ModalAddItem" tabindex="-1" aria-labelledby="ModalAddItemLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalAddItemLabel">Tambah Item</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="" class="form-label required">
                            Proses
                        </label>

                        <select name="proses" data-placeholder="Pilih Proses" class="form-select select_proses">
                            <option value=""></option>
                            @foreach ($masterPekerjaan as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="masuk_invoice" value="1"
                            role="switch" id="masuk_inv_add_item" checked>
                        <label class="form-check-label" for="masuk_inv_add_item">Masuk Invoice</label>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary save_form_add_item">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>


@push('addScript')
    <script>
        $(".save_form_add_item").on("click", function() {
            $(".loading__global").show();
            $("#formAddItem").submit();
        });
    </script>
@endpush
