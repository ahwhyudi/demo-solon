@php
    $filterAction = $tipe === 'notaris' ? route('job.akta.data.index') : route('job.akta.data.filter', $tipe);
@endphp

<button type="button" class="btn btn-warning mb-4 position-relative" data-bs-toggle="modal"
    data-bs-target="#modalFilterAkta">
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

<div class="modal fade" id="modalFilterAkta" tabindex="-1" aria-labelledby="modalFilterAktaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ $filterAction }}" method="GET">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalFilterAktaLabel">Filter Data Akta</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="filter_parent" class="form-label">Parent</label>
                        <input type="text" id="filter_parent" class="form-control" name="parent"
                            value="{{ request('parent') }}" placeholder="Kode/ID parent">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Penugasan</label>

                        <select name="penugasan" class="form-select">
                            <option value="">Semua</option>

                            @foreach ($userOptions as $user)
                                <option value="{{ $user->id }}"
                                    {{ request('penugasan') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Penugasan QC</label>

                        <select name="penugasan_qc" class="form-select">
                            <option value="">Semua</option>

                            @foreach ($userOptions as $user)
                                <option value="{{ $user->id }}"
                                    {{ request('penugasan_qc') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor Objek</label>

                        <input type="text" name="nomor_objek" class="form-control"
                            value="{{ request('nomor_objek') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Debitur</label>

                        <input type="text" name="nama_debitur" class="form-control"
                            value="{{ request('nama_debitur') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Bank</label>

                        <select name="bank" class="form-select">
                            <option value="">Semua</option>

                            @foreach ($bankOptions as $bank)
                                <option value="{{ $bank->id }}"
                                    {{ request('bank') == $bank->id ? 'selected' : '' }}>
                                    {{ $bank->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status Akad</label>

                        <select name="status_akad" class="form-select">
                            <option value="">Semua</option>
                            <option value="Pra Akad" {{ request('status_akad') == 'Pra Akad' ? 'selected' : '' }}>
                                Pra Akad
                            </option>

                            <option value="Akad" {{ request('status_akad') == 'Akad' ? 'selected' : '' }}>
                                Akad
                            </option>

                            <option value="Pending" {{ request('status_akad') == 'Pending' ? 'selected' : '' }}>
                                Pending
                            </option>

                            <option value="Selesai" {{ request('status_akad') == 'Selesai' ? 'selected' : '' }}>
                                Selesai
                            </option>

                            <option value="Batal Akad" {{ request('status_akad') == 'Batal Akad' ? 'selected' : '' }}>
                                Batal Akad
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="filter_nomor_akta" class="form-label">Nomor Akta</label>
                        <input type="text" id="filter_nomor_akta" class="form-control" name="nomor_akta"
                            value="{{ request('nomor_akta') }}" placeholder="Nomor akta">
                    </div>

                    <div class="mb-3">
                        <label for="filter_proses" class="form-label">Proses</label>
                        <select id="filter_proses" name="proses" class="form-select">
                            <option value="">Semua proses</option>
                            @foreach ($prosesOptions as $proses)
                                <option value="{{ $proses }}"
                                    {{ request('proses') === $proses ? 'selected' : '' }}>
                                    {{ $proses }}
                                </option>
                            @endforeach
                        </select>
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
                </div>
                <div class="modal-footer">
                    <a href="{{ $filterAction }}" class="btn btn-secondary">Reset Filter</a>
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>
