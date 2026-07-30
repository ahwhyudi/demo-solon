<!-- Button trigger modal -->
<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalTolak">
    Tolak
</button>

<!-- Modal -->
<div class="modal fade" id="modalTolak" tabindex="-1" aria-labelledby="modalTolakLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalTolakLabel">Tolak Pembatalan </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('job.pembatalan-items.update', $pembatalanItem->id) }}" method="post"
                    id="formPenolakan">
                    @csrf
                    @method('PUT')
                    <label for="" class="form-label required">
                        Keterangan Penolakan
                    </label>
                    <textarea name="keterangan" class="form-control" placeholder="Keterangan Penolakan..."></textarea>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger btn__submit_tolak">Tolak Sekarang</button>
            </div>
        </div>
    </div>
</div>

@push('addScript')
    <script>
        $(".btn__submit_tolak").on("click", function() {
            $(".loading__global").show();
            $("#formPenolakan").submit();
        })
    </script>
@endpush
