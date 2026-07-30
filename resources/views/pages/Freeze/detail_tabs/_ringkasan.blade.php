<div class="row g-3">
    <div class="col-md-4">
        <label for="" class="form-label required">
            Grup Pekerjaan
        </label>
        <input type="text" class="form-control" name="ringkasan[group_proses]" value="{{ $jobDivisi->jenisAkad->nama }}"
            readonly>
    </div>
    <div class="col-md-4">
        <label for="" class="form-label required">
            Status
        </label>
        <input type="text" class="form-control" readonly value="{{ $jobDivisi->status }}">
    </div>

    <div class="col-md-4">
        <label for="" class="form-label required">
            Tipe Servis
        </label>
        <select name="ringkasan[tipe_servis]" class="form-select">
            <option value="regular" selected>
                Regular
            </option>
            <option value="prioritas" readonly {{ $jobDivisi->tipe_servis === 'prioritas' ? 'selected' : '' }}>
                Prioritas
            </option>
        </select>
    </div>

    <div class="col-md-4">
        <label for="" class="form-label required">
            Tanggal Akad
        </label>
        <input type="date" class="form-control" readonly name="ringkasan[tanggal_akad]"
            value="{{ $jobDivisi->tanggal_akad ? \Carbon\Carbon::parse($jobDivisi->tanggal_akad)->format('Y-m-d') : '' }}">
    </div>
    <div class="col-md-4">
        <label for="" class="form-label required">
            Tempat Akad
        </label>
        <input type="text" readonly class="form-control" value="{{ $jobDivisi->tempat_akad }}"
            name="ringkasan[tempat_akad]">
    </div>

    @if ($jobDivisi->tanggal_akad)
        <div class="col-md-4">
            <label for="" class="form-label required">
                Tanggal Akad
            </label>
            <input type="date" class="form-control" readonly value="{{ $jobDivisi->tanggal_akad }}">
        </div>
    @endif

    <div class="col-md-4">
        <label for="" class="form-label required">
            Tanggal Kirim Berkas
        </label>
        <input type="date" class="form-control" name="ringkasan[tanggal_kirim_berkas]" readonly
            value="{{ $jobDivisi->tanggal_akad ? \Carbon\Carbon::parse($jobDivisi->tanggal_kirim_berkas)->format('Y-m-d') : '' }}">
    </div>

    @if ($jobDivisi->tanggal_estimasi_selesai)
        <div class="col-md-4">
            <label for="" class="form-label required">
                Tanggal SLA Internal
            </label>
            <input type="date" readonly class="form-control" value="{{ $jobDivisi->tanggal_estimasi_selesai }}">
        </div>
    @endif
    @if ($jobDivisi->tanggal_estimasi_selesai)
        <div class="col-md-4">
            <label for="" class="form-label required">
                Tanggal SLA Eksternal
            </label>
            <input type="date" readonly class="form-control"
                value="{{ $jobDivisi->tanggal_estimasi_selesai_eksternal }}">
        </div>
    @endif

    <div class="col-md-4">
        <label for="" class="form-label required">
            Foto Akad
        </label>
        @if ($fileAkad)
            <a href="{{ asset('storage/' . $fileAkad->path) }}" target="_blank" class="btn btn-sm btn-success ">
                Lihat File Saat Ini
            </a>
        @endif

    </div>
    <div class="col-md-4">
        <label for="" class="form-label required">
            Upload Receipt Certificate
        </label>
        @if (isset($fileSertifikat[0]))
            <a href="{{ asset('storage/' . $fileSertifikat[0]->path) }}" target="_blank"
                class="btn btn-sm btn-success ">
                Lihat File Saat Ini
            </a>
        @endif
    </div>
    <div class="col-md-4">
        <label for="" class="form-label required">
            Upload Receipt Certificate
        </label>
        @if (isset($fileSertifikat[1]))
            <a href="{{ asset('storage/' . $fileSertifikat[1]->path) }}" target="_blank"
                class="btn btn-sm btn-success ">
                Lihat File Saat Ini
            </a>
        @endif

    </div>
    <div class="col-md-12">
        <label for="" class="form-label required">
            Catatan Tambahan (Ringkasan)
        </label>
        <textarea name="ringkasan[catatan]" readonly class="form-control">{{ $jobDivisi->keterangan }}</textarea>
    </div>
</div>
