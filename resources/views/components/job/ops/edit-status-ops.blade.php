@php
    $currentUserId = auth()->id();

    $lastStatusData = $statusOps->last();

    /*
    |--------------------------------------------------------------------------
    | SUPER ADMIN
    |--------------------------------------------------------------------------
    */

    $isSuperAdmin = auth()->user()->hasRole('super admin');

    /*
    |--------------------------------------------------------------------------
    | USER YANG DITUGASKAN
    |--------------------------------------------------------------------------
    */

    $isAssignedUser =
        $formOrder->penugasan_user == $currentUserId;

    /*
    |--------------------------------------------------------------------------
    | HAK PENUGASAN
    |--------------------------------------------------------------------------
    */

    $canAssign =
        $isSuperAdmin ||
        auth()->user()->can('job/ops/penugasan');

    /*
    |--------------------------------------------------------------------------
    | HAK PROGRESS / MENYELESAIKAN
    |--------------------------------------------------------------------------
    */

    $canProgress =
        $isSuperAdmin ||
        $isAssignedUser;

    /*
    |--------------------------------------------------------------------------
    | TAMPILKAN BUTTON
    |--------------------------------------------------------------------------
    */

    $canShowButton = false;

    if ($currentStatus === 'Penugasan') {

        $canShowButton = $canAssign;

    } else {

        $canShowButton = $canProgress;
    }
@endphp
<div>
    @if ($canShowButton)
        @if ($lastStatus !== 'Menunggu persetujuan finance' && $lastStatus !== 'Dikembalikan')
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary " data-bs-toggle="modal"
                data-bs-target="#editStatusOPS{{ $key }}">
                Edit
            </button>

            <form action="{{ route('job.ops.data.store') }}" method="post" id="formData{{ $key }}">
                @csrf
                <input type="text" value="{{ $currentStatus }}" hidden name="status"
                    class="field__status{{ $key }}">
                <input type="text" value="{{ $formOrder->id }}" hidden name="form_id">
                <div class="modal fade editStatus" id="editStatusOPS{{ $key }}" tabindex="-1"
                    aria-labelledby="editStatusOPS{{ $key }}Label" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="">
                                    <h1 class="modal-title fs-5" id="editStatusOPS{{ $key }}Label">
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
                                @if ($currentStatus === 'Penugasan')
                                    <div class="mb-3">
                                        <label for="" class="form-label required">
                                            Objek
                                        </label>
                                        <select name="objek" class="form-select select2_ops"
                                            data-placeholder="Pilih Objek">
                                            <option value=""></option>
                                            @foreach ($jobDivisi->objek as $objek)
                                                <option value="{{ $objek->id }}">
                                                    {{ $objek->desa->kecamatan->kota->name }},
                                                    {{ $objek->desa->kecamatan->name }},
                                                    {{ $objek->desa->name }},
                                                    {{ $objek->jenis_sertifikat }},
                                                    ({{ $objek->no_sertifikat }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="" class="form-label required">
                                            Penugasan OPS
                                        </label>

                                        <select name="ops" class="form-select select2_ops"
                                            data-placeholder="Pilih OPS">
                                            <option value=""></option>
                                            @foreach ($userOps as $itemOps)
                                                <option value="{{ $itemOps->id }}">
                                                    {{ $itemOps->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                @if ($currentStatus === 'Pengajuan Biaya')
                                    <div class="mb-3">
                                        <label for="" class="form-label required">
                                            Biaya Operasional
                                        </label>
                                        <input type="text" class="form-control money" name="biaya_operasional">
                                    </div>
                                @endif

                                <label for="" class="form-label required">
                                    Keterangan
                                </label>
                                <textarea name="keterangan" class="form-control">-</textarea>

                            </div>
                            <div class="modal-footer">
                                {{-- @if ($currentStatus === 'Penugasan')
                                    <button class="btn btn__submit_data{{ $key }} btn-danger" type="button"
                                        data-tipe="tolak">
                                        Tolak
                                    </button>
                                @endif --}}

                                @if ($canShowButton)
                                    <button type="button" class="btn btn__submit_data{{ $key }} btn-primary"
                                        data-tipe="ok">

                                        Simpan {{ $currentStatus }}

                                    </button>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!-- Modal -->

        @endif
    @endif


</div>

@push('addScript')
    <script>
        $("#editStatusOPS{{ $key }} .select2_ops").select2({
            theme: 'bootstrap-5',
            dropdownParent: $("#editStatusOPS{{ $key }}"),
        });

        $(".btn__submit_data{{ $key }}").on("click", function() {
            $(".loading__global").show();
            let tipe = $(this).attr("data-tipe");
            console.log('tipe', tipe)

            if (tipe === "tolak") {
                $(".field__status{{ $key }}").val("Ditolak");
            }
            $("#formData{{ $key }}").submit();
        })
    </script>
@endpush
