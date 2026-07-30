    @extends('layouts.admin')

    @section('title')
        Tambah Master Data Pekerjaan
    @endsection

    @push('page-title')
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);"
            aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                <li class="breadcrumb-item"><a href="{{ route('master-data.pekerjaan.index') }}">Pekerjaan</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Data</li>
            </ol>
        </nav>
    @endpush

    @section('content')
        @php
            // Hitung jumlah baris berdasarkan old() agar semua nilai lama muncul lagi saat gagal simpan
            $oldWilayah = old('wilayah', []);
            $rowCount = max(count($oldWilayah), 1);

            // Helper old() untuk masing2 kolom array per index
            $oldHargaLimit = old('harga_limit', []);
            $oldHargaJual = old('harga_jual', []);
            $oldHargaProses = old('harga_proses', []);
            $oldLama = old('lama_pengerjaan', []);
        @endphp

        <div class="card">
            <div class="card-body">
                <form action="{{ route('master-data.pekerjaan.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label required">Nama</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama"
                                value="{{ old('nama') }}">
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label required">Kategori</label>
                            <select name="kategori"
                                class="form-select select2 pilih__kategori @error('kategori') is-invalid @enderror"
                                data-placeholder="Pilih Kategori">
                                <option value=""></option>
                                @foreach ($kategori as $val => $label)
                                    <option value="{{ $val }}" {{ old('kategori') === $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-5 container_item" style="display: none">
                        <label class="form-label">Wilayah & Harga</label>

                        <div class="list__item d-flex flex-column gap-4">
                            @for ($i = 0; $i < $rowCount; $i++)
                                <div class="card row_{{ $i + 1 }}">
                                    <div class="card-body">
                                        <div class="row g-3 align-items-end">
                                            <div class="col-md-3">
                                                <label class="form-label required">Wilayah</label>
                                                <select name="wilayah[]"
                                                    class="form-select select2 @error('wilayah.' . $i) is-invalid @enderror"
                                                    data-placeholder="Pilih Wilayah">
                                                    <option value=""></option>
                                                    @foreach ($provinsi as $item)
                                                        <optgroup label="{{ $item->name }}">
                                                            @foreach ($item->kota as $kota)
                                                                <option value="{{ $kota->id }}"
                                                                    {{ old('wilayah.' . $i) === $kota->id ? 'selected' : '' }}>
                                                                    {{ $kota->name }}
                                                                </option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                </select>

                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label required">Harga Limit</label>
                                                <input type="text"
                                                    class="form-control money @error('harga_limit.' . $i) is-invalid @enderror"
                                                    name="harga_limit[]" value="{{ $oldHargaLimit[$i] ?? '' }}">

                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label required">Lama Pengerjaan</label>
                                                <input type="text"
                                                    class="form-control @error('lama_pengerjaan.' . $i) is-invalid @enderror"
                                                    name="lama_pengerjaan[]" value="{{ $oldLama[$i] ?? '' }}">

                                            </div>

                                            <div class="col-md-1 d-flex justify-content-end">
                                                <button type="button" onclick="removeElemet({{ $i + 1 }})"
                                                    class="btn btn-danger">
                                                    Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>

                        <div class="text-center mt-4">
                            <button type="button" class="btn btn-outline-success add_item">Tambah Wilayah</button>
                        </div>
                    </div>

                    <div class="mt-5">
                        <button class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endsection

    @push('addScript')
        <script>
            const removeElemet = (idx) => {
                $(`.list__item .row_${idx}`).remove();
            };

            $(".pilih__kategori").on("change", function() {
                const val = $(this).val();
                if (val === "operasional") {
                    $(".container_item").show();
                } else {
                    $(".container_item").hide();

                }
            })

            // idx start dari jumlah baris yang sudah dirender (termasuk old)
            let idx = {{ $rowCount }};

            const initPlugins = (scope = document) => {
                $(scope).find('.select2').select2({
                    theme: 'bootstrap-5',
                    width: '100%'
                });
                $(scope).find('.money').mask('#.##0', {
                    reverse: true
                });
            };

            $(document).ready(function() {
                initPlugins(document);

                $(".add_item").on("click", function() {
                    idx++;
                    const tpl = `
                    <div class="card row_${idx}">
                        <div class="card-body">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label class="form-label required">Wilayah</label>
                                    <select name="wilayah[]" class="form-select select2" data-placeholder="Pilih Wilayah">
                                        <option value=""></option>
                                        @foreach ($provinsi as $item)
                                            <optgroup label="{{ $item->name }}">
                                                @foreach ($item->kota as $kota)
                                                    <option value="{{ $kota->id }}">{{ $kota->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label required">Harga Limit</label>
                                    <input type="text" class="form-control money" name="harga_limit[]">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label required">Harga Jual</label>
                                    <input type="text" class="form-control money" name="harga_jual[]">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label required">Harga Proses</label>
                                    <input type="text" class="form-control money" name="harga_proses[]">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label required">Lama Pengerjaan</label>
                                    <input type="text" class="form-control" name="lama_pengerjaan[]">
                                </div>
                                <div class="col-md-1 d-flex justify-content-end">
                                    <button type="button" onclick="removeElemet(${idx})" class="btn btn-danger">Hapus</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                    const $node = $(tpl);
                    $(".list__item").append($node);
                    initPlugins($node);
                });
            });
        </script>
    @endpush
