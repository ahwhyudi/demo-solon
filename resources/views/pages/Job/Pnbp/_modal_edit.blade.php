<!-- Button trigger modal -->
{{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalInputVa{{ $key }}">
    Penugasan
</button>

<form action="{{ route('job.pnbp.store') }}" method="post" id="ajukan_{{ $key }}" class="form-pnbp">>
    @csrf
    <input type="hidden" name="item_id" value="{{ $item->id }}">
    <!-- Modal -->
    <div class="modal fade" id="modalInputVa{{ $key }}" tabindex="-1"
        aria-labelledby="modalInputVa{{ $key }}Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="">
                        <h1 class="modal-title fs-5" id="modalInputVa{{ $key }}Label">
                            Form Input VA
                        </h1>
                        <small>
                            @if ($item->jobDivisi?->objek?->isNotEmpty())
                                Nomor Objek
                                {{ $item->jobDivisi?->objek?->pluck('no_sertifikat')->implode(', ') ?? 'Belum di input no sertifikat' }}
                            @else
                                <span class="text-warning">
                                    Belum di input no sertifikat
                                </span>
                            @endif
                        </small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="">
                        <label for="" class="form-label required">
                            Nomor VA
                        </label>
                        <input type="text" class="form-control" required name="va">
                        <input type="text" class="form-control" required name="va" inputmode="numeric">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn_submit_va">Simpan VA</button>
                </div>
            </div>
        </div>
    </div>
</form> --}}


{{-- @push('addScript')
    <script>
        $("#ajukan_{{ $key }}").on("submit", function() {
            $(".loading__global").show();
        })
    </script>
@endpush --}}

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAssign{{ $key }}">
    Tugaskan
</button>

<form action="{{ route('job.pnbp.assign', $item->pnbp->id) }}" method="POST" class="form-pnbp">
    @csrf
    @method('PUT')

    <!-- Modal -->
    <div class="modal fade" id="modalAssign{{ $key }}" tabindex="-1"
        aria-labelledby="modalAssign{{ $key }}Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">

                    <div>

                        <h1 class="modal-title fs-5" id="modalAssign{{ $key }}Label">
                            Penugasan User
                        </h1>

                        <small>

                            @if ($item->jobDivisi?->objek?->isNotEmpty())
                                Nomor Objek

                                {{ $item->jobDivisi?->objek?->pluck('no_sertifikat')->implode(', ') }}
                            @else
                                <span class="text-warning">
                                    Belum di input no sertifikat
                                </span>
                            @endif

                        </small>

                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <div>

                        <label class="form-label required">
                            Pilih User
                        </label>

                        <select name="user_id" class="form-select" required>

                            <option value="">
                                Pilih User
                            </option>

                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }}
                                </option>
                            @endforeach

                        </select>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>

                    <button type="submit" class="btn btn-primary btn_submit_va">
                        Tugaskan
                    </button>

                </div>

            </div>
        </div>
    </div>
</form>

@push('addScript')
    <script>
        $(".form-pnbp").on("submit", function() {
            $(".btn_submit_va").prop("disabled", true);
            $(".loading__global").show();
        });
    </script>
@endpush
