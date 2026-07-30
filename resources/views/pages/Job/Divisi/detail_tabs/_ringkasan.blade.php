<form action="{{ route('job.divisi.update', $jobDivisi->id) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row g-3">
        <div class="col-md-4">
            <label for="" class="form-label required">
                Grup Pekerjaan
            </label>
            <input type="text" class="form-control" name="ringkasan[group_proses]"
                value="{{ $jobDivisi->jenisAkad->nama }}" disabled>
        </div>

        <div class="col-md-4">
            <label for="" class="form-label ">
                Penanggung Jawab
            </label>
            <select name="ringkasan[penanggung_jawab]" class="form-select select2"
                data-placeholder="Pilih Penanggung Jawab">
                <option value=""></option>
                @foreach ($user as $item)
                    <option value="{{ $item->id }}"
                        {{ (int) $jobDivisi->user_ops === (int) $item->id ? 'selected' : '' }}>
                        {{ $item->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label for="" class="form-label ">
                Perwakilan Akad
            </label>
            <select name="ringkasan[perwakilan_akad][]" multiple class="form-select select2 select_perwakilan"
                data-placeholder="Pilih Perwakilan Akad">
                <option value=""></option>
                @foreach ($user as $item)
                    <option value="{{ $item->id }}"
                        {{ (int) $jobDivisi->user_perwakilan_akad === (int) $item->id ? 'selected' : '' }}
                        {{ (int) $jobDivisi->user_perwakilan_akad_2 === (int) $item->id ? 'selected' : '' }}>
                        {{ $item->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="" class="form-label required">
                Tanggal Rencana Akad
            </label>
            <input type="datetime-local" class="form-control" name="ringkasan[tanggal_rencana_akad]"
                value="{{ $jobDivisi->tanggal_rencana_akad ? \Carbon\Carbon::parse($jobDivisi->tanggal_rencana_akad)->format('Y-m-d H:i') : '' }}">
        </div>

        <div class="col-md-3">
            <label for="" class="form-label required">
                Tipe Servis
            </label>
            <select name="ringkasan[tipe_servis]" class="form-select">
                <option value="regular" selected>
                    Regular
                </option>
                <option value="prioritas" {{ $jobDivisi->tipe_servis === 'prioritas' ? 'selected' : '' }}>
                    Prioritas
                </option>
            </select>
        </div>

        <div class="col-md-3">
            <label for="" class="form-label ">
                Tempat Akad
            </label>
            <input type="text" class="form-control" value="{{ $jobDivisi->tempat_akad }}"
                name="ringkasan[tempat_akad]">
        </div>
        @if ($jobDivisi->tanggal_akad)
            <div class="col-md-4">
                <label for="" class="form-label ">
                    Tanggal Akad
                </label>
                <input type="date" class="form-control" value="{{ $jobDivisi->tanggal_akad }}">
            </div>
        @endif
        <div class="col-md-3">
            <label for="" class="form-label ">
                Tanggal Kirim Berkas
            </label>
            <input type="date" class="form-control" name="ringkasan[tanggal_kirim_berkas]"
                value="{{ $jobDivisi->tanggal_kirim_berkas ? \Carbon\Carbon::parse($jobDivisi->tanggal_kirim_berkas)->format('Y-m-d') : '' }}">
        </div>
        @if ($jobDivisi->tanggal_estimasi_selesai)
            <div class="col-md-3">
                <label for="" class="form-label ">
                    Tanggal SLA Internal
                </label>
                <input type="date" class="form-control" value="{{ $jobDivisi->tanggal_estimasi_selesai }}">
            </div>
        @endif
        @if ($jobDivisi->tanggal_estimasi_selesai)
            <div class="col-md-3">
                <label for="" class="form-label ">
                    Tanggal SLA Eksternal
                </label>
                <input type="date" class="form-control"
                    value="{{ $jobDivisi->tanggal_estimasi_selesai_eksternal }}">
            </div>
        @endif

        <div class="col-md-12">
            <label for="" class="form-label ">
                Catatan Tambahan (Ringkasan)
            </label>
            <textarea name="ringkasan[catatan]" class="form-control">{{ $jobDivisi->keterangan }}</textarea>
        </div>
    </div>

    @if ($jobDivisi->status !== 'Batal Akad' && $jobDivisi->status !== 'Selesai' && auth()->user()->can('job/divisi/edit'))
        <button class="btn btn-primary mt-4">
            Simpan Data
        </button>
    @endif
</form>

@push('addScript')
    <script>
        $(".select_perwakilan").select2({
            theme: 'bootstrap-5',
            width: '100%',
            maximumSelectionLength: 2,
            placeholder: "Pilih Perwakilan Akad",

        });
    </script>
@endpush
