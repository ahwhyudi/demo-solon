<!-- Button trigger modal -->


<!-- Modal -->
<form action="{{ route('job.pnbp.store') }}" method="POST" class="form-pnbp" id="formAssign{{ $key }}">
    @csrf

    <input type="hidden" name="item_id" value="{{ $item->id }}">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalAssign{{ $key }}">
        Tugaskan
    </button>
    <div class="modal fade" id="ModalAssign{{ $key }}" tabindex="-1"
        aria-labelledby="ModalAssign{{ $key }}Label" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">

                    <div>

                        <h1 class="modal-title fs-5" id="ModalAssign{{ $key }}Label">
                            Penugasan PNBP
                        </h1>

                        <small>

                            @if ($item->jobDivisi?->objek?->isNotEmpty())
                                Nomor Objek :

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

                    <div class="mb-3">

                        <label class="form-label required">
                            Pilih User
                        </label>

                        <select name="user_id" class="form-select select2" required>

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

                    <button type="submit" class="btn btn-primary btn_submit_assign">
                        Tugaskan
                    </button>

                </div>

            </div>

        </div>

    </div>
</form>

@push('addScript')
    <script>
         $('#ModalAssign{{ $key }} .select2').select2({
        width: '100%',
        theme: 'bootstrap-5',
        dropdownParent: $('#ModalAssign{{ $key }}')
    });
        $(".form-pnbp").on("submit", function() {

            $(this)
                .find("button[type='submit']")
                .prop("disabled", true);
            $(".loading__global").show();

        });
    </script>
@endpush
