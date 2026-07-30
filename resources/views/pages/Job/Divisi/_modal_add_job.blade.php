<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddJob">
    + Tambah Data
</button>

<!-- Modal -->
<form action="{{ route('job.divisi.store') }}" method="post" id="form_add_job">
    @csrf
    <div class="modal fade" id="modalAddJob" tabindex="-1" aria-labelledby="modalAddJobLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalAddJobLabel">Form Tambah Job</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="label form-label required">
                        Group Proses
                    </div>
                    <select name="group_proses" class="form-select select2_group_proses"
                        data-placeholder="Pilih group proses">
                        <option value=""></option>
                        @foreach ($masterPekerjaan as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn__save_job">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>


@push('addScript')
    <script>
        $(".select2_group_proses").select2({
            width: "100%",
            dropdownParent: $("#modalAddJob"),
            theme: "bootstrap-5"
        });

        $(".btn__save_job").on("click", function() {
            $(".loading__global").show();
            $("#form_add_job").submit();
        })
    </script>
@endpush
