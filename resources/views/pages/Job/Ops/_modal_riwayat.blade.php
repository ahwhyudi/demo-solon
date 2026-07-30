<!-- Button trigger modal -->
<button type="button" class="btn btn-secondary " data-bs-toggle="modal"
    data-bs-target="#statusRiwayatprsoes{{ $key }}">
    Riwayat
</button>

<!-- Modal -->
<div class="modal fade" id="statusRiwayatprsoes{{ $key }}" tabindex="-1"
    aria-labelledby="statusRiwayatprsoes{{ $key }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div class="">
                    <h1 class="modal-title fs-5" id="statusRiwayatprsoes{{ $key }}Label">Status Proses
                        {{ $formOrder->nama }}</h1>
                    <div class="text-secondary">
                        Parent {{ $formOrder->jobDivisi->kode }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @foreach ($statusJobOps as $number => $item)
                    <div class="d-flex gap-3 mb-3">
                        <div class="">

                            <span
                                class="badge {{ $item->status_penolakan ? 'bg-danger text-white' : 'bg-primary text-blue-fg' }}  badge-pill">
                                {{ $number + 1 }}
                            </span>
                        </div>
                        <div class="{{ $item->status_penolakan ? 'text-danger' : '' }}">
                            <div class="fw-bold">
                                {{ $item->status_penolakan ? "$item->status_penolakan Perlu Perbaikan" : "Selesai $item->status" }}
                            </div>
                            <div class="text-secondary mb-2">
                                {{ $item->keterangan }}
                            </div>
                            <div class="text-secondary mb-2">
                                <i class="bi bi-person-circle"></i> Diproses oleh :
                                {{ $item->createdBy->name ?? 'otomatis' }}
                            </div>
                            @if ($item->user)
                            
                                <div class="text-secondary mb-2">
                                    <i class="bi bi-person-check"></i> Penugasan : {{ $item->user->name }}
                                </div>
                            @endif
                            <div class="text-secondary mb-2">
                                <i class="bi bi-stopwatch"></i> Waktu : {{ $item->created_at->format('d M Y H:i') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>

            </div>
        </div>
    </div>
</div>
