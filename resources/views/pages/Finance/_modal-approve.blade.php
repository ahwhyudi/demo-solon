<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
    <i class="bi bi-info-circle"></i>
</button>

<!-- Modal -->
<form action="{{ route('finance.job-divisi.update', $item->id) }}" method="post" class="form_approve">

    @csrf
    @method('PUT')
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Butuh Persetujuan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="" class="form-label">
                            Diajukan Oleh
                        </label>
                        <input class="form-control" type="text" value="{{ $item->pembuat->name ?? '-' }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">
                            Tanggal Diajukan
                        </label>
                        <input class="form-control" type="text" value="{{ $item->tanggal->format('d M Y') ?? '-' }}"
                            disabled>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">
                            Item Proses
                        </label>
                        <input class="form-control" type="text" value="{{ $item->formOrder->nama ?? '' }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">
                            Biaya Operasional
                        </label>
                        <input class="form-control money" name="harga_proses" type="text"
                            value="{{ (int) $item->formOrder->harga_proses ?? 0 }}" required>
                    </div>
                </div>
                <div class="modal-footer">

                    <button type="submit" name="status" value="Tolak" class="btn btn-danger">Tolak</button>
                    <button type="submit" name="status" value="Disetujui" class="btn btn-primary">Simpan &
                        Setujui</button>
                </div>
            </div>
        </div>
    </div>
</form>
