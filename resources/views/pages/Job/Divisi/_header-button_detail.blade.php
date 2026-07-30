<div class="my-3 d-flex justify-content-end gap-3">
    @if (!in_array($jobDivisi->status, ['Pra Akad', 'Batal Akad', 'Selesai']))
        <span class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalSelesaiAkad">
            Selesai
        </span>
    @endif
    @if ($jobDivisi->status === 'Pra Akad')
        <div class="btn btn-outline-primary " data-bs-toggle="modal" data-bs-target="#modalAkad">
            Akad
        </div>
    @endif
    @if (!$jobDivisi->freeze && $jobDivisi->status !== 'Selesai')
        <div class="btn btn-outline-warning " data-bs-toggle="modal" data-bs-target="#modalFreeze">
            Freeze (Lock Data)
        </div>
    @endif
    @if ($jobDivisi->status !== 'Selesai' && $jobDivisi->status !== 'Batal Akad')
        <form action="" method="POST" class="form-batal-akad">
            @csrf
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalBatalAkad">
                Batal Akad
            </button>
        </form>
    @endif
    @if ($jobDivisi->status === 'Batal Akad')
        @can('job/divisi/buka-batal')
            <form action="{{ route('job.divisi.buka-batal', $jobDivisi->id) }}" method="POST" class="form-batal-akad">
                @csrf
                <button type="submit" class="btn btn-outline-success">
                    Buka Batal
                </button>
            </form>
        @endcan
    @endif

</div>

{{-- modal freeze --}}
<div class="modal fade" id="modalFreeze" tabindex="-1" aria-labelledby="modalFreezeLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalFreezeLabel">Form Modal Freeze</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('berkas-bermasalah.freeze.store') }}" method="post" id="form__freeze">
                    @csrf
                    <input type="number" name="job_id" hidden value="{{ $jobDivisi->id }}">
                    <div class="mb-3">
                        <label for="" class="form-label required">
                            Keterangan Freeze
                        </label>
                        <textarea name="keterangan" class="form-control" placeholder="Isi Alasan Keterangan"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal & Tutup</button>
                <button type="button" class="btn btn-primary btn__freeze">Ajukan Freeze Data</button>
            </div>
        </div>
    </div>
</div>
{{-- modal freeze end --}}

{{-- modal AKAD --}}
<div class="modal fade" id="modalAkad" tabindex="-1" aria-labelledby="modalAkadLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalAkadLabel">Form Modal Akad</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('job.changeStatusAkad') }}" method="post" id="form__akad">
                    @csrf
                    <input type="number" name="job_divisi_id" hidden value="{{ $jobDivisi->id }}">
                    <input type="text" name="status" hidden value="Akad">
                    <div class="mb-3">
                        <label for="" class="form-label required">
                            Keterangan Akad
                        </label>
                        <textarea name="keterangan" class="form-control" placeholder="Keterangan akad"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal & Tutup</button>
                <button type="button" class="btn btn-primary btn__akad">Yakin Akad</button>
            </div>
        </div>
    </div>
</div>
{{-- modal AKAD end --}}

{{-- modal Selesai AKAD --}}
<div class="modal fade" id="modalSelesaiAkad" tabindex="-1" aria-labelledby="modalSelesaiAkadLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalSelesaiAkadLabel">Konfirmasi Selesai Akad</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <!-- Form tetap ada di background untuk submit data hidden -->
                <form action="{{ route('job.changeStatusAkad') }}" method="post" id="form__Selesai__akad">
                    @csrf
                    <input type="number" name="job_divisi_id" hidden value="{{ $jobDivisi->id }}">
                    <input type="text" name="status" hidden value="Selesai">
                </form>

                <!-- Kalimat Konfirmasi -->
                <p class="mb-0 fs-5 fw-medium text-dark">
                    Apakah Anda yakin proses akad ini sudah selesai?
                </p>
                <small class="text-muted d-block mt-2">
                    Setelah dikonfirmasi, status akan otomatis berubah menjadi Selesai.
                </small>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Belum, Kembali</button>
                <button type="button" class="btn btn-success px-4 btn_selesai__akad">Ya, Sudah Yakin</button>
            </div>
        </div>
    </div>
</div>
{{-- modal Selesai AKAD end --}}

{{-- modal batal akad --}}
<div class="modal fade" id="modalBatalAkad" tabindex="-1" aria-labelledby="modalBatalAkadLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalBatalAkadLabel">Form Modal Akad</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('job.divisi.batal-akad', $jobDivisi->id) }}" method="post"
                    id="form__batal__akad">
                    @csrf
                    <input type="number" name="job_divisi_id" hidden value="{{ $jobDivisi->id }}">
                    <input type="text" name="status" hidden value="Akad">
                    <div class="mb-3">
                        <label for="" class="form-label required">
                            Keterangan batal Akad
                        </label>
                        <textarea name="keterangan" class="form-control" placeholder="Keterangan akad"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal & Tutup</button>
                <button type="button" class="btn btn-danger btn__batal__akad">Yakin Batal Akad</button>
            </div>
        </div>
    </div>
</div>


@push('addScript')
    <script>
        $(".btn__freeze").on("click", function() {
            $(".loading__global").show();
            $("#form__freeze").submit();
        });
        $(".btn__akad").on("click", function() {
            $(".loading__global").show();
            $("#form__akad").submit();
        });
        $(".btn__batal__akad").on("click", function() {
            $(".loading__global").show();
            $("#form__batal__akad").submit();
        });
        $(".btn_selesai__akad").on("click", function() {
            $(".loading__global").show();
            $("#form__Selesai__akad").submit();
        });

        $(".btn__selesai").on("click", function() {
            Swal.fire({
                title: 'Yakin Selesai?',
                icon: 'question',
                denyButtonText: `Batal`,
                confirmButtonText: 'Yakin',
                showDenyButton: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    $(".loading__global").show();
                    $("#form__selesai").submit();
                }
            })
        });
    </script>
@endpush
