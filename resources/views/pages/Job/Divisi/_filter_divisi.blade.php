<button type="button" class="btn btn-warning position-relative" data-bs-toggle="modal" data-bs-target="#modalFilterDivisi">
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

<div class="modal fade" id="modalFilterDivisi" tabindex="-1" aria-labelledby="modalFilterDivisiLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ $filterRoute ?? route('job.divisi.index') }}" method="GET">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalFilterDivisiLabel">Filter Job Divisi</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="filter_kode" class="form-label">Kode</label>
                        <input type="text" id="filter_kode" class="form-control" name="kode"
                            value="{{ request('kode', request('q')) }}" placeholder="Kode job">
                    </div>

                    <div class="mb-3">
                        <label for="filter_status" class="form-label">Status</label>
                        <select id="filter_status" name="status" class="form-select">
                            <option value="">Semua status</option>
                            @foreach ($statusOptions as $status)
                                <option value="{{ $status }}"
                                    {{ request('status') === $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="filter_tanggal_akad" class="form-label">Tanggal Akad</label>
                        <input type="date" id="filter_tanggal_akad" class="form-control" name="tanggal_akad"
                            value="{{ request('tanggal_akad') }}">
                    </div>

                    <div class="mb-3">
                        <label for="filter_nama_penghadap" class="form-label">Nama Penghadap</label>
                        <input type="text" id="filter_nama_penghadap" class="form-control" name="nama_penghadap"
                            value="{{ request('nama_penghadap') }}" placeholder="Nama penghadap">
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ $filterRoute ?? route('job.divisi.index') }}" class="btn btn-secondary">Reset Filter</a>
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>
