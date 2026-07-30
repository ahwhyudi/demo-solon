<div class="card">
    <div class="card-body">
        <form action="{{ route('job.storeFormAkad') }}" method="post">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label required">Group Proses</label>
                    <select name="group_proses" class="form-select select2" data-placeholder="Pilih item">
                        <option value=""></option>
                        @foreach ($status_akad_developer as $item)
                            @if (Session::get('form_akad'))
                                <option value="{{ $item['value'] }}"
                                    {{ $item['value'] == Session::get('form_akad')['group_proses'] ? 'selected' : '' }}>
                                    {{ $item['label'] }}</option>
                            @else
                                <option value="{{ $item['value'] }}">{{ $item['label'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label required">Divisi</label>
                    <select name="divisi" class="form-select select2" data-placeholder="Pilih item">
                        <option value=""></option>
                        @foreach ($divisi as $item)
                            @if (Session::get('form_akad'))
                                <option value="{{ $item['value'] }}"
                                    {{ $item['value'] == Session::get('form_akad')['divisi'] ? 'selected' : '' }}>
                                    {{ $item['label'] }}</option>
                            @else
                                <option value="{{ $item['value'] }}">{{ $item['label'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label required">Penanggung Jawab Berkas</label>
                    <select name="user_ops" class="form-select select2" data-placeholder="Pilih item">
                        <option value=""></option>
                        @foreach ($userOps as $item)
                            @if (Session::get('form_akad'))
                                <option value="{{ $item['value'] }}"
                                    {{ $item['value'] == Session::get('form_akad')['user_ops'] ? 'selected' : '' }}>
                                    {{ $item['label'] }}
                                </option>
                            @else
                                <option value="{{ $item['value'] }}">{{ $item['label'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="" class="form-label ">
                        Nama Agent
                    </label>
                    <input type="text" class="form-control" name="nama_agent"
                        value="{{ Session::get('form_akad')['nama_agent'] ?? '' }}">
                </div>
                <div class="col-md-4">
                    <label for="" class="form-label required">
                        Tgl. Rencana Akad
                    </label>
                    <input type="date" class="form-control" name="tgl_rencana_akad"
                        value="{{ Session::get('form_akad')['tgl_rencana_akad'] ?? '' }}">
                </div>
                <div class="col-md-4">
                    <label for="" class="form-label required">
                        Keterangan
                    </label>
                    <textarea name="keterangan" class="form-control">{{ Session::get('form_akad')['keterangan'] ?? '' }}</textarea>
                </div>
            </div>


            <div class="d-flex justify-content-end mt-5">
                <button type="submit" class="btn btn-primary">Simpan & Lanjut</button>
            </div>

        </form>
    </div>
</div>
