<!-- Button trigger modal -->
<button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
    data-bs-target="#modalDispo{{ $key }}">
    Dispo
</button>

<!-- Modal -->
<div class="modal fade" id="modalDispo{{ $key }}" tabindex="-1"
    aria-labelledby="modalDispo{{ $key }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="">
                    <h1 class="modal-title fs-5" id="modalDispo{{ $key }}Label">
                        Form Dispo {{ $item->nama }}
                    </h1>
                    <div class="text-secondary">
                        Parent {{ $item->jobDivisi->kode }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('berkas-bermasalah.dispo.store') }}" method="post"
                    id="form__dispo{{ $key }}">
                    @csrf
                    <input type="text" name="form_order_id" hidden value="{{ $item->id }}">
                    <label for="" class="form-label requred">
                        Keterangan
                    </label>
                    <textarea name="keterangan" class="form-control" placeholder="Isi keterangan"></textarea>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary btn__dispo{{ $key }}">Simpan Data</button>
            </div>
        </div>
    </div>
</div>

@push('addScript')
    <script>
        $(".btn__dispo{{ $key }}").on("click", function() {
            $(".loading__global").show();
            $("#form__dispo{{ $key }}").submit();
        });
    </script>
@endpush
