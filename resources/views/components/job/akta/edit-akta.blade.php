@php
    $lastStatus = $statusJobOps->last();
    $isSuperAdmin = auth()->user()->hasRole('super admin');
    $currentUserId = auth()->id();

    $assignedStaffId = $lastStatus->user_id ?? null;
    $activeStep = $nextStatus;

    // Menunggu TTD Notaris sekarang langsung berurutan setelah Selesai Minuta
    $isPenugasanStep =
        str_contains($activeStep, 'Penugasan') ||
        $activeStep === 'Menunggu TTD Notaris' ||
        $activeStep === 'Selesai Minuta';

    $isStaffStep = !$isPenugasanStep;

    $canAccess =
        $isSuperAdmin ||
        (
            $isPenugasanStep &&
            auth()->user()->can('job/akta/penugasan')
        ) ||
        (
            $isStaffStep &&
            $assignedStaffId == $currentUserId
        );
@endphp

<div>
    @if ($canAccess)
        @if ($currentStatus !== 'Selesai')
            @if ($currentStatus !== '')
                <button type="button" class="btn btn-primary btn-md"
                    data-bs-toggle="modal" data-bs-target="#modalEditAkta{{ $key }}">
                    Edit
                </button>
            @endif

            <form action="{{ route('job.akta.data.store') }}" method="post" class="form_step_akad"
                id="formData{{ $key }}">
                @csrf

                <input type="text" value="{{ $formOrder->id }}" name="form_id" hidden>
                <input type="hidden" name="current_status" value="{{ $currentStatus }}">
                <input type="hidden" name="next_status" value="{{ $nextStatus }}">
                <input type="hidden" name="reject_status" value="{{ $rejectStatus }}">

                <div class="modal fade" id="modalEditAkta{{ $key }}" tabindex="-1"
                    aria-labelledby="modalEditAkta{{ $key }}Label" aria-hidden="true">

                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                        <div class="modal-content">

                            <div class="modal-header">
                                <div>
                                    <h1 class="modal-title fs-5" id="modalEditAkta{{ $key }}Label">
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
                                <div class="row justify-content-center g-3">
                                    <div class="col-lg-4 d-lg-block d-none">
                                        <ul class="steps steps-counter steps-vertical">
                                            @foreach ($steps as $step)
                                                <li
                                                    class="step-item {{ $nextStatus === $step ? 'active' : '' }} {{ $currentStatus === 'Belum diproses' ? 'active' : '' }}">
                                                    {{ $step }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="mb-3">
                                            <label class="form-label required">Jenis Akad</label>
                                            <input type="text" class="form-control" disabled
                                                value="{{ $jobDivisi->jenisAkad->nama }}">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Status Saat Ini</label>
                                            <input type="text" class="form-control" disabled
                                                value="{{ $currentStatus }}">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Status Berikutnya</label>
                                            <input type="text" class="form-control" disabled
                                                value="{{ $nextStatus }}">
                                        </div>

                                        @if ($isPenugasan)
                                            <div class="mb-3">
                                                <label for="" class="form-label required">Staff</label>
                                                <select name="staff" class="form-select select2_ops"
                                                    data-placeholder="Pilih Staff">
                                                    <option value=""></option>
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user['value'] }}">
                                                            {{ $user['label'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif

                                        <div>
                                            <label class="form-label required">Keterangan</label>
                                            <textarea name="keterangan" class="form-control">-</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Tutup
                                </button>

                                <button type="submit" name="tipe" value="next_step"
                                    class="btn btn-primary btn__submit_data{{ $key }}">
                                    Selesai {{ $nextStatus }}
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

            </form>
        @endif
    @endif
</div>