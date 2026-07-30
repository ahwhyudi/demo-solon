<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddNomor">
    Tambah Nomor
</button>

<form action="{{ route('laporan.inputNomorRekanan') }}" method="post" id="formInputNomor">
    @csrf
    <input type="hidden" name="kategori" value="{{ $kategori }}">
    <div class="modal fade" id="modalAddNomor" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Nomor </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label required">Nama Proses</label>
                        <select name="group_proses" class="form-select select2_group_proses_add "
                            data-placeholder="Pilih group proses" required>
                            <option value=""></option>
                            @foreach ($masterPekerjaan as $item)
                                @if (in_array($item->kategori, ['surat-keluar', 'waarmerking', 'legalisasi', 'notaris', 'ppat']))
                                    <option value="{{ $item->id }}">
                                        {{ $item->nama }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Notaris Pengambil Nomor</label>
                        <input type="text" class="form-control" name="notaris_pengambil"
                            placeholder="Masukkan nama notaris" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Nama Debitur</label>
                        <input type="text" class="form-control" name="nama_debitur_notaris_pengambil"
                            placeholder="Masukkan nama debitur" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label ">Objek</label>
                        <input type="text" class="form-control" name="objek_notaris_pengambil"
                            placeholder="Masukkan objek" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label ">Tanggal</label>
                        <input type="date" class="form-control" name="tanggal_nomor"
                            value="{{ old('tanggal_nomor', date('Y-m-d')) }}" required>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary ">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>

@push('addScript')
    <script>
        $(".select2_group_proses_add").select2({
            width: "100%",
            dropdownParent: $("#modalAddNomor"),
            theme: "bootstrap-5"
        });

        $(".btn__save_nomor").on("click", function() {
            $(".loading__global").show();
            $("#form_add_job").submit();
        });

        $("#formInputNomor").on("submit", function(e) {
            $(".loading__global").show();
        });
    </script>
@endpush
