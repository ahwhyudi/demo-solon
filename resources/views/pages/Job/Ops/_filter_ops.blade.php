<!-- Button trigger modal -->
<button type="button" class="btn btn-warning position-relative" data-bs-toggle="modal" data-bs-target="#modalFilterOps">
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

<!-- Modal -->
<div class="modal fade" id="modalFilterOps" tabindex="-1" aria-labelledby="modalFilterOpsLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalFilterOpsLabel">Filter OPS</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="">
                    <div class="mb-3">
                        <label for="" class="form-label">
                            Kode/ID Parent
                        </label>
                        <input type="text" class="form-control" name="parent"
                            value="{{ request()->get('parent') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            Nomor Objek
                        </label>

                        <input type="text" class="form-control" name="nomor_objek"
                            value="{{ request()->get('nomor_objek') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            Nama Penghadap
                        </label>

                        <input type="text" class="form-control" name="nama_penghadap"
                            value="{{ request()->get('nama_penghadap') }}">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">
                            Status Pengerjaan
                        </label>
                        <select name="status_pengerjaan" class="form-select select_2_ops"
                            data-placeholder="Pilih Status Pengerjaan">
                            <option value=""></option>
                            <option value="belum dikerjakan"
                                {{ request()->get('status_pengerjaan') == 'belum dikerjakan' ? 'selected' : '' }}>Belum
                                Dikerjakan</option>
                            <option value="Penugasan"
                                {{ request()->get('status_pengerjaan') == 'Penugasan' ? 'selected' : '' }}>Penugasan
                            </option>
                            <option value="Selesai"
                                {{ request()->get('status_pengerjaan') == 'Selesai' ? 'selected' : '' }}>Selesai
                            </option>
                            <option value="dispo"
                                {{ request()->get('status_pengerjaan') == 'dispo' ? 'selected' : '' }}>Dispo</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">
                            Status Akad
                        </label>
                        <select name="status_akad" class="form-select select_2_ops"
                            data-placeholder="Pilih Status Akad">
                            <option value=""></option>
                            <option value="Pra Akad"
                                {{ request()->get('status_akad') == 'Pra Akad' ? 'selected' : '' }}>Pra Akad</option>
                            <option value="Akad" {{ request()->get('status_akad') == 'Akad' ? 'selected' : '' }}>Akad
                            </option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            Proses
                        </label>

                        <select name="proses" class="form-select select_2_ops" data-placeholder="Pilih Proses">

                            <option value=""></option>

                            @foreach ($prosesList as $item)
                                <option value="{{ $item }}"
                                    {{ request()->get('proses') == $item ? 'selected' : '' }}>
                                    {{ $item }}
                                </option>
                            @endforeach

                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            Nama Bank
                        </label>

                        <select name="bank" class="form-select select_2_ops" data-placeholder="Pilih Bank">

                            <option value=""></option>

                            @foreach ($bankList as $item)
                                <option value="{{ $item }}"
                                    {{ request()->get('bank') == $item ? 'selected' : '' }}>
                                    {{ $item }}
                                </option>
                            @endforeach

                        </select>
                    </div>
                    <div class="d-flex gap-4 justify-content-end mt-4">
                        <a href="{{ route('job.ops.data.index') }}#tabs-finance" class="btn btn-secondary">Reset
                            Filter</a>
                        <button class="btn btn-primary">Terapkan Filter</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
