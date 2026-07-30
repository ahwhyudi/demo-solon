<div class="container">

    {{-- ========================================================= --}}
    {{-- DATA PERUSAHAAN --}}
    {{-- ========================================================= --}}
    <div class="card mb-4">
        <div class="card-body">

            <h2 class="mb-4 border-bottom pb-2">Data Perusahaan / Notaris</h2>

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label required">Nama Notaris</label>
                    <input type="text" name="nama_perusahaan" class="form-control"
                        value="{{ old('nama_perusahaan', $item->nama_perusahaan ?? null) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="telepon" class="form-control"
                        value="{{ old('telepon', $item->telepon ?? null) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                        value="{{ old('email', $item->email ?? null) }}">
                </div>

                <div class="col-md-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $item->alamat ?? null) }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Logo</label>
                    <input type="file" name="logo" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">foto notaris</label>
                    <input type="file" name="foto_profil" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">foto KTP</label>
                    <input type="file" name="foto_ktp" class="form-control">
                </div>

            </div>

        </div>
    </div>

    {{-- ========================================================= --}}
    {{-- SK NOTARIS --}}
    {{-- ========================================================= --}}
    <div class="card mb-4">
        <div class="card-body">

            <h2 class="mb-4 border-bottom pb-2">SK Notaris</h2>

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Nomor SK Kemenkumham</label>
                    <input type="text" name="sk_notaris" class="form-control"
                        value="{{ old('sk_notaris', $item->skNotaris->sk_kemenkumham ?? null) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tanggal SK</label>
                    <input type="date" name="tanggal_sk_notaris" class="form-control"
                        value="{{ old('tanggal_sk_notaris', isset($item->skNotaris->tanggal_sk) ? \Carbon\Carbon::parse($item->skNotaris->tanggal_sk)->format('Y-m-d') : null) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Kota</label>
                    <select name="kota_notaris_id" class="form-select select2">
                        <option value="">Pilih Kota</option>
                        @foreach ($kotas as $kota)
                            <option value="{{ $kota->id }}"
                                @selected(old('kota_notaris_id', $item->skNotaris->kota_id ?? null) == $kota->id)>
                                {{ $kota->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Alamat SK Notaris</label>
                    <textarea name="alamat_notaris" class="form-control" rows="3">{{ old('alamat_notaris', $item->skNotaris->alamat ?? null) }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">File SK Notaris</label>
                    <input type="file" name="file_sk_notaris" class="form-control">
                </div>

            </div>

        </div>
    </div>

    {{-- ========================================================= --}}
    {{-- SK PPAT --}}
    {{-- ========================================================= --}}
    <div class="card mb-4">
        <div class="card-body">

            <h2 class="mb-4 border-bottom pb-2">SK PPAT</h2>

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Nomor SK BPN</label>
                    <input type="text" name="sk_ppat" class="form-control"
                        value="{{ old('sk_ppat', $item->skPpat->sk_kemenkumham ?? null) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tanggal SK</label>
                    <input type="date" name="tanggal_sk_ppat" class="form-control"
                        value="{{ old('tanggal_sk_ppat', isset($item->skPpat->tanggal_sk) ? \Carbon\Carbon::parse($item->skPpat->tanggal_sk)->format('Y-m-d') : null) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Kota</label>
                    <select name="kota_ppat_id" class="form-select select2">
                        <option value="">Pilih Kota</option>
                        @foreach ($kotas as $kota)
                            <option value="{{ $kota->id }}"
                                @selected(old('kota_ppat_id', $item->skPpat->kota_id ?? null) == $kota->id)>
                                {{ $kota->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Alamat SK PPAT</label>
                    <textarea name="alamat_ppat" class="form-control" rows="3">{{ old('alamat_ppat', $item->skPpat->alamat ?? null) }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">File SK PPAT</label>
                    <input type="file" name="file_sk_ppat" class="form-control">
                </div>

            </div>

        </div>
    </div>

    {{-- ========================================================= --}}
    {{-- BUTTON --}}
    {{-- ========================================================= --}}
    <div class="d-flex justify-content-end gap-2">

        <button type="submit" class="btn btn-primary">
            {{ $submitLabel }}
        </button>

        <a href="{{ route('setting.perusahaan.index') }}" class="btn btn-light">
            Kembali
        </a>

    </div>

</div>

@push('addScript')
    <script>
        $('.select2').select2({
            width: '100%',
            theme: 'bootstrap-5',
        });
         $('.select2').on('select2:open', function () {
        document.querySelector('.select2-search__field').focus();
    });
    </script>
@endpush