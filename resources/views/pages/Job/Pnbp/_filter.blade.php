<button type="button" class="btn btn-warning mb-4 position-relative" data-bs-toggle="modal"
    data-bs-target="#modalFilterPnbp">
     <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search"
        viewBox="0 0 16 16">
        <path
            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
    </svg>
    @if ($countFilter)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger text-white">
            {{ $countFilter }}
        </span>
    @endif
</button>

<div class="modal fade" id="modalFilterPnbp" tabindex="-1" aria-labelledby="modalFilterPnbpLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('job.pnbp.index') }}" method="GET">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalFilterPnbpLabel">Filter PNBP/Voucher</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="filter_parent" class="form-label">Parent</label>
                        <input type="text" id="filter_parent" class="form-control" name="parent"
                            value="{{ request('parent') }}" placeholder="Kode/ID parent">
                    </div>

                    <div class="mb-3">
                        <label for="filter_proses" class="form-label">Proses</label>
                        <select id="filter_proses" name="proses" class="form-select">
                            <option value="">Semua proses</option>
                            @foreach ($prosesOptions as $proses)
                                <option value="{{ $proses }}" {{ request('proses') === $proses ? 'selected' : '' }}>
                                    {{ $proses }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="filter_nomor_objek" class="form-label">Nomor Objek</label>
                        <input type="text" id="filter_nomor_objek" class="form-control" name="nomor_objek"
                            value="{{ request('nomor_objek') }}" placeholder="Nomor objek">
                    </div>

                    <div class="mb-3">
                        <label for="filter_nama_debitur" class="form-label">Nama Debitur</label>
                        <input type="text" id="filter_nama_debitur" class="form-control" name="nama_debitur"
                            value="{{ request('nama_debitur') }}" placeholder="Nama debitur">
                    </div>

                    <div class="mb-3">
                        <label for="filter_nama_bank" class="form-label">Nama Bank</label>
                        <input type="text" id="filter_nama_bank" class="form-control" name="nama_bank"
                            value="{{ request('nama_bank') }}" placeholder="Nama bank">
                    </div>

                    <div class="mb-3">
                        <label for="filter_status" class="form-label">Status</label>
                        <select id="filter_status" name="status" class="form-select">
                            <option value="">Semua status</option>
                            @foreach ($statusOptions as $status)
                                <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('job.pnbp.index') }}" class="btn btn-secondary">Reset Filter</a>
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>
