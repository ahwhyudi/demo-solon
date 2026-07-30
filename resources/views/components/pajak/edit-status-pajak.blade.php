<div>

    @if ($currentStatus !== 'Selesai')
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
            data-bs-target="#modalEditPajak{{ $key }}">
            Edit
        </button>

        <form action="{{ route('job.pajak.data.store') }}" method="post">
            @csrf
            <input type="text" value="{{ $formOrder->id }}" hidden name="form_id">
            <!-- Modal -->
            <div class="modal fade" id="modalEditPajak{{ $key }}" tabindex="-1"
                aria-labelledby="modalEditPajak{{ $key }}Label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="">
                                <h1 class="modal-title fs-5" id="modalEditPajak{{ $key }}Label">
                                    Status Proses {{ $formOrder->nama }}
                                </h1>
                                <div class="text-secondary">
                                    Parent {{ $jobDivisi->kode }}
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="" class="form-label required">
                                    Jenis Akad
                                </label>
                                <input type="text" class="form-control" readonly
                                    value="{{ $jobDivisi->jenisAkad->nama }}">
                            </div>

                            <div class="mb-3">
                                <label for="" class="form-label required">
                                    Nominal Pembayaran
                                </label>
                                <input type="text" class="form-control money" name="nominal_pembayaran">
                            </div>

                            <div class="">
                                <label for="" class="form-label required">
                                    Keterangan
                                </label>
                                <textarea name="keterangan" class="form-control">-</textarea>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    @endif


</div>
