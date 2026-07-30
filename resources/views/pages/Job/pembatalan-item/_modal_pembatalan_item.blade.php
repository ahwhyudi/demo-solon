<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPembatalanItem">
    Tambah Item
</button>

<!-- Modal -->
<div class="modal fade" id="modalPembatalanItem" tabindex="-1" aria-labelledby="modalPembatalanItemLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalPembatalanItemLabel">Pembatalan Item</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('job.pembatalan-items.create') }}" method="get" id="form_pembatalan_item">
                    <label for="" class="form-label required">
                        Parent Job
                    </label>
                    <select name="job_divisi_id" class="form-select select2_pembatalan" data-placeholder="Pilih Parent">
                        <option value=""></option>
                        @foreach ($jobDivisi as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->kode }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary btn__lanjut_proses">Lanjut Proses</button>
            </div>
        </div>
    </div>
</div>


@push('addScript')
    <script>
        $(".btn__lanjut_proses").on("click", function() {
            $(".loading__global").show();
            $("#form_pembatalan_item").submit();
        });

        $(document).ready(function() {
            $(".select2_pembatalan").select2({
                placeholder: "Pilih Parent",
                theme: 'bootstrap-5',
                width: '100%',
                dropdownParent: $("#modalPembatalanItem")
            });
        })
    </script>
@endpush
