@can('data-pendukung/objek/view')
    <x-job.detail.list-objek-component :listObjek="$jobDivisi->objek" />
@endcan

@can('data-pendukung/objek/create')
    @php
        $formData = session('form_data', []);
        $oldObjek =
            old('objek') ??
            (session('form_data.objek') ?? [
                [
                    'desa_id' => '',
                    'jenis_sertifikat' => '',
                    'no_sertifikat' => '',
                    'nama_pemilik' => '',
                    'luas_tanah' => '',
                    'nilai_ht' => '',
                    'nilai_transaksi' => '',
                    'nilai_plafond' => '',
                    'alamat' => '',
                    'nib' => '',
                    'njop' => '',
                    'pbb' => '',
                    'file' => '',
                ],
            ]);

        $lastIndex = is_array($oldObjek) ? array_key_last($oldObjek) : -1;
        if ($lastIndex === null) {
            $lastIndex = -1;
        }
    @endphp

    <form action="{{ route('job.divisi-data-pendukung.store') }}" method="post" enctype="multipart/form-data" id="formObjek">
        @csrf
        <input type="text" hidden name="job_divisi_id" value="{{ $jobDivisi->id }}">
        
        <div class="card-body-objek">
            @foreach ($oldObjek as $idx => $o)
                <div class="card shadow-sm border border-light-subtle rounded-3 card_objek_{{ $idx }} @if ($idx > 0) mt-4 @endif" data-index="{{ $idx }}">
                    <div class="card-header bg-light bg-opacity-50 py-3 border-bottom border-light-subtle d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-dark">
                            <i class="bi bi-geo-alt-fill me-2 text-primary"></i>Form Data Objek
                        </h6>
                        @if ($idx > 0)
                            <button type="button" class="btn btn-sm btn-outline-danger border-0 px-2 py-1" onclick="removeObjek({{ $idx }})" title="Hapus Form Ini">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        @endif
                    </div>
                    <div class="card-body pt-3">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold required">DESA</label>
                                <select name="objek[{{ $idx }}][desa_id]" class="form-select select2_desa @error("objek.$idx.desa_id") is-invalid @enderror"
                                    data-placeholder="Pilih Desa">
                                    <option value=""></option>
                                    @if(isset($o['desa_id']) && $o['desa_id'])
                                        <option value="{{ $o['desa_id'] }}" selected>{{ $o['desa_text'] ?? 'Desa Terpilih' }}</option>
                                    @endif
                                </select>
                                @error("objek.$idx.desa_id")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold required">JENIS SERTIFIKAT</label>
                                <select name="objek[{{ $idx }}][jenis_sertifikat]"
                                    class="form-select @error("objek.$idx.jenis_sertifikat") is-invalid @enderror">
                                    <option value="" disabled {{ !isset($o['jenis_sertifikat']) || $o['jenis_sertifikat'] == '' ? 'selected' : '' }}>Pilih Jenis Sertifikat</option>
                                    <option value="SHM" {{ (old("objek.$idx.jenis_sertifikat", $o['jenis_sertifikat'] ?? '') == 'SHM') ? 'selected' : '' }}>SHM - Sertifikat Hak Milik</option>
                                    <option value="HGB" {{ (old("objek.$idx.jenis_sertifikat", $o['jenis_sertifikat'] ?? '') == 'HGB') ? 'selected' : '' }}>HGB - Hak Guna Bangunan</option>
                                    <option value="HGU" {{ (old("objek.$idx.jenis_sertifikat", $o['jenis_sertifikat'] ?? '') == 'HGU') ? 'selected' : '' }}>HGU - Hak Guna Usaha</option>
                                    <option value="HAK PAKAI" {{ (old("objek.$idx.jenis_sertifikat", $o['jenis_sertifikat'] ?? '') == 'HAK PAKAI') ? 'selected' : '' }}>HAK PAKAI</option>
                                    <option value="HPL" {{ (old("objek.$idx.jenis_sertifikat", $o['jenis_sertifikat'] ?? '') == 'HPL') ? 'selected' : '' }}>HPL - Hak Pengelolaan</option>
                                    <option value="HMSRS" {{ (old("objek.$idx.jenis_sertifikat", $o['jenis_sertifikat'] ?? '') == 'HMSRS') ? 'selected' : '' }}>HMSRS - Hak Milik Satuan Rumah Susun</option>
                                    <option value="SHSRS" {{ (old("objek.$idx.jenis_sertifikat", $o['jenis_sertifikat'] ?? '') == 'SHSRS') ? 'selected' : '' }}>SHSRS - Sertifikat Hak Satuan Rumah Susun</option>
                                    <option value="SERTIFIKAT WAKAF" {{ (old("objek.$idx.jenis_sertifikat", $o['jenis_sertifikat'] ?? '') == 'SERTIFIKAT WAKAF') ? 'selected' : '' }}>SERTIFIKAT WAKAF</option>
                                    <option value="HAK TANGGUNGAN" {{ (old("objek.$idx.jenis_sertifikat", $o['jenis_sertifikat'] ?? '') == 'HAK TANGGUNGAN') ? 'selected' : '' }}>HAK TANGGUNGAN</option>
                                    <option value="SERTIFIKAT ROYA" {{ (old("objek.$idx.jenis_sertifikat", $o['jenis_sertifikat'] ?? '') == 'SERTIFIKAT ROYA') ? 'selected' : '' }}>SERTIFIKAT ROYA</option>
                                    <option value="GIRIK" {{ (old("objek.$idx.jenis_sertifikat", $o['jenis_sertifikat'] ?? '') == 'GIRIK') ? 'selected' : '' }}>GIRIK</option>
                                    <option value="LETTER C" {{ (old("objek.$idx.jenis_sertifikat", $o['jenis_sertifikat'] ?? '') == 'LETTER C') ? 'selected' : '' }}>LETTER C</option>
                                    <option value="PETOK D" {{ (old("objek.$idx.jenis_sertifikat", $o['jenis_sertifikat'] ?? '') == 'PETOK D') ? 'selected' : '' }}>PETOK D</option>
                                    <option value="VERPONDING" {{ (old("objek.$idx.jenis_sertifikat", $o['jenis_sertifikat'] ?? '') == 'VERPONDING') ? 'selected' : '' }}>VERPONDING</option>
                                    <option value="KOHIR" {{ (old("objek.$idx.jenis_sertifikat", $o['jenis_sertifikat'] ?? '') == 'KOHIR') ? 'selected' : '' }}>KOHIR</option>
                                    <option value="PATOK" {{ (old("objek.$idx.jenis_sertifikat", $o['jenis_sertifikat'] ?? '') == 'PATOK') ? 'selected' : '' }}>PATOK</option>
                                    <option value="BPKB" {{ (old("objek.$idx.jenis_sertifikat", $o['jenis_sertifikat'] ?? '') == 'BPKB') ? 'selected' : '' }}>BPKB</option>
                                    <option value="IMB" {{ (old("objek.$idx.jenis_sertifikat", $o['jenis_sertifikat'] ?? '') == 'IMB') ? 'selected' : '' }}>IMB - Izin Mendirikan Bangunan</option>
                                    <option value="PBG" {{ (old("objek.$idx.jenis_sertifikat", $o['jenis_sertifikat'] ?? '') == 'PBG') ? 'selected' : '' }}>PBG - Persetujuan Bangunan Gedung</option>
                                    <option value="SK" {{ (old("objek.$idx.jenis_sertifikat", $o['jenis_sertifikat'] ?? '') == 'SK') ? 'selected' : '' }}>SK - Surat Keputusan</option>
                                    <option value="SURAT UKUR" {{ (old("objek.$idx.jenis_sertifikat", $o['jenis_sertifikat'] ?? '') == 'SURAT UKUR') ? 'selected' : '' }}>SURAT UKUR</option>
                                    <option value="SPPT PBB" {{ (old("objek.$idx.jenis_sertifikat", $o['jenis_sertifikat'] ?? '') == 'SPPT PBB') ? 'selected' : '' }}>SPPT PBB</option>
                                    <option value="BPHTB" {{ (old("objek.$idx.jenis_sertifikat", $o['jenis_sertifikat'] ?? '') == 'BPHTB') ? 'selected' : '' }}>BPHTB</option>
                                    <option value="SSB" {{ (old("objek.$idx.jenis_sertifikat", $o['jenis_sertifikat'] ?? '') == 'SSB') ? 'selected' : '' }}>SSB - Surat Setoran Bea</option>
                                </select>
                                @error("objek.$idx.jenis_sertifikat")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold required">NO. SERTIFIKAT</label>
                                <input type="text"
                                    class="form-control @error("objek.$idx.no_sertifikat") is-invalid @enderror"
                                    name="objek[{{ $idx }}][no_sertifikat]"
                                    value="{{ old("objek.$idx.no_sertifikat", $o['no_sertifikat'] ?? '') }}"
                                    placeholder="Masukkan nomor sertifikat">
                                @error("objek.$idx.no_sertifikat")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold required">NAMA PEMILIK</label>
                                <input type="text"
                                    class="form-control @error("objek.$idx.nama_pemilik") is-invalid @enderror"
                                    name="objek[{{ $idx }}][nama_pemilik]"
                                    value="{{ old("objek.$idx.nama_pemilik", $o['nama_pemilik'] ?? '') }}"
                                    placeholder="Masukkan nama pemilik">
                                @error("objek.$idx.nama_pemilik")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold">LUAS TANAH</label>
                                <input type="text"
                                    class="form-control @error("objek.$idx.luas_tanah") is-invalid @enderror"
                                    name="objek[{{ $idx }}][luas_tanah]"
                                    value="{{ old("objek.$idx.luas_tanah", $o['luas_tanah'] ?? '') }}"
                                    placeholder="Contoh: 150 m2">
                                @error("objek.$idx.luas_tanah")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold required">NO. SPPT</label>
                                <input type="text"
                                    class="form-control @error("objek.$idx.no_sppt") is-invalid @enderror"
                                    name="objek[{{ $idx }}][no_sppt]"
                                    value="{{ old("objek.$idx.no_sppt", $o['no_sppt'] ?? '') }}"
                                    placeholder="Masukkan nomor SPPT">
                                @error("objek.$idx.no_sppt")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold required">PBB</label>
                                <input type="text" class="form-control @error("objek.$idx.pbb") is-invalid @enderror"
                                    name="objek[{{ $idx }}][pbb]"
                                    value="{{ old("objek.$idx.pbb", $o['pbb'] ?? '') }}"
                                    placeholder="Masukkan nilai PBB">
                                @error("objek.$idx.pbb")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold required">NJOP</label>
                                <input type="text"
                                    class="form-control @error("objek.$idx.njop") is-invalid @enderror"
                                    name="objek[{{ $idx }}][njop]"
                                    value="{{ old("objek.$idx.njop", $o['njop'] ?? '') }}"
                                    placeholder="Masukkan nilai NJOP">
                                @error("objek.$idx.njop")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold">NIB</label>
                                <input type="text" class="form-control @error("objek.$idx.nib") is-invalid @enderror"
                                    name="objek[{{ $idx }}][nib]"
                                    value="{{ old("objek.$idx.nib", $o['nib'] ?? '') }}"
                                    placeholder="Masukkan NIB">
                                @error("objek.$idx.nib")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label text-secondary small fw-bold required">ALAMAT</label>
                                <input type="text"
                                    class="form-control @error("objek.$idx.alamat") is-invalid @enderror"
                                    name="objek[{{ $idx }}][alamat]"
                                    value="{{ old("objek.$idx.alamat", $o['alamat'] ?? '') }}"
                                    placeholder="Masukkan alamat lengkap objek">
                                @error("objek.$idx.alamat")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @if ($dataPendukungObjek)
                                @if (in_array('nilai_ht', $dataPendukungObjek))
                                    <div class="col-md-4">
                                        <label class="form-label text-secondary small fw-bold">NILAI HT</label>
                                        <input type="text"
                                            class="form-control money @error("objek.$idx.nilai_ht") is-invalid @enderror"
                                            name="objek[{{ $idx }}][nilai_ht]"
                                            value="{{ old("objek.$idx.nilai_ht", $o['nilai_ht'] ?? '') }}"
                                            placeholder="0">
                                        @error("objek.$idx.nilai_ht")
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif
                                @if (in_array('nilai_transaksi', $dataPendukungObjek))
                                    <div class="col-md-4">
                                        <label class="form-label text-secondary small fw-bold required">NILAI TRANSAKSI</label>
                                        <input type="text"
                                            class="form-control money @error("objek.$idx.nilai_transaksi") is-invalid @enderror"
                                            name="objek[{{ $idx }}][nilai_transaksi]"
                                            value="{{ old("objek.$idx.nilai_transaksi", $o['nilai_transaksi'] ?? '') }}"
                                            placeholder="0">
                                        @error("objek.$idx.nilai_transaksi")
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif
                                @if (in_array('nilai_plafond', $dataPendukungObjek))
                                    <div class="col-md-4">
                                        <label class="form-label text-secondary small fw-bold required">NILAI PLAFOND</label>
                                        <input type="text"
                                            class="form-control money @error("objek.$idx.nilai_plafond") is-invalid @enderror"
                                            name="objek[{{ $idx }}][nilai_plafond]"
                                            value="{{ old("objek.$idx.nilai_plafond", $o['nilai_plafond'] ?? '') }}"
                                            placeholder="0">
                                        @error("objek.$idx.nilai_plafond")
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif
                            @endif

                            <div class="col-12">
                                <div class="p-3 border border-2 border-dashed border-secondary border-opacity-25 rounded-3 bg-light bg-opacity-25">
                                    <label class="form-label text-dark small fw-bold mb-1">DOKUMEN LAMPIRAN OBJEK</label>
                                    <p class="text-muted small mb-2" style="font-size: 0.75rem;">Unggah dokumen pendukung objek.</p>
                                    <input type="file"
                                        class="form-control bg-white @error("objek.$idx.files") is-invalid @enderror"
                                        name="objek[{{ $idx }}][files][]"
                                        multiple>
                                    @error("objek.$idx.files")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Tombol Tambah Lebih Elegan -->
        <div class="mt-4">
            <button type="button" class="btn btn-outline-primary w-100 py-3 border-2 border-dashed fw-bold rounded-3 add_objek shadow-sm transition-hover" style="border-style: dashed !important;">
                <i class="bi bi-plus-circle me-1"></i> Tambah Form Objek Lainnya
            </button>
        </div>

        @if ($jobDivisi->status !== 'Batal Akad')
            <div class="d-flex justify-content-end mt-4 mb-5">
                <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm btn__simpan_objek">
                    Simpan Objek
                </button>
            </div>
        @endif
    </form>

    <!-- Modal Konfirmasi Hapus Form Objek -->
    <div class="modal fade" id="modalDeleteObjek" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow">
                <div class="modal-body text-center p-4">
                    <div class="text-danger mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-exclamation-circle" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                        </svg>
                    </div>
                    <h6 class="fw-bold mb-1">Hapus form ini?</h6>
                    <p class="text-muted small mb-4">Input data di baris ini akan hilang dan tidak disimpan.</p>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-light btn-sm px-3 border" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger btn-sm px-3 fw-bold" id="btn-confirm-remove-objek">Ya, Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('addScript')
        <script>
            $(document).ready(function() {
                $(".btn__simpan_objek").on("click", function() {
                    $(this).prop("disabled", true);
                    $(this).text("Menyimpan...");
                    $(".loading__global").show();
                    $("#formObjek").submit();
                });

                let objekIndexToRemove = null;

                window.removeObjek = function(idx) {
                    objekIndexToRemove = idx;
                    let myModal = new bootstrap.Modal(document.getElementById('modalDeleteObjek'));
                    myModal.show();
                };

                $("#btn-confirm-remove-objek").on("click", function() {
                    if (objekIndexToRemove !== null) {
                        $(`.card-body-objek .card_objek_${objekIndexToRemove}`).fadeOut(300, function() {
                            $(this).remove();
                        });
                        $('#modalDeleteObjek').modal('hide');
                        objekIndexToRemove = null;
                    }
                });

                // Template untuk append (pakai index dinamis)
                const objekTemplate = (i) => `
                <div class="card shadow-sm border border-light-subtle rounded-3 card_objek_${i} mt-4" data-index="${i}" style="display:none;">
                    <div class="card-header bg-light bg-opacity-50 py-3 border-bottom border-light-subtle d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-dark">
                            <i class="bi bi-geo-alt-fill me-2 text-primary"></i>Form Data Objek
                        </h6>
                        <button type="button" class="btn btn-sm btn-outline-danger border-0 px-2 py-1" onclick="removeObjek(${i})" title="Hapus Form Ini">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </div>
                    <div class="card-body pt-3">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold required">DESA</label>
                                <select name="objek[${i}][desa_id]" class="form-select select2_desa" data-placeholder="Pilih Desa">
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold required">JENIS SERTIFIKAT</label>
                                <select name="objek[${i}][jenis_sertifikat]" class="form-select">
                                    <option value="" disabled selected>Pilih Jenis Sertifikat</option>
                                    <option value="SHM">SHM - Sertifikat Hak Milik</option>
                                    <option value="HGB">HGB - Hak Guna Bangunan</option>
                                    <option value="HGU">HGU - Hak Guna Usaha</option>
                                    <option value="HAK PAKAI">HAK PAKAI</option>
                                    <option value="HPL">HPL - Hak Pengelolaan</option>
                                    <option value="HMSRS">HMSRS - Hak Milik Satuan Rumah Susun</option>
                                    <option value="SHSRS">SHSRS - Sertifikat Hak Satuan Rumah Susun</option>
                                    <option value="SERTIFIKAT WAKAF">SERTIFIKAT WAKAF</option>
                                    <option value="HAK TANGGUNGAN">HAK TANGGUNGAN</option>
                                    <option value="SERTIFIKAT ROYA">SERTIFIKAT ROYA</option>
                                    <option value="GIRIK">GIRIK</option>
                                    <option value="LETTER C">LETTER C</option>
                                    <option value="PETOK D">PETOK D</option>
                                    <option value="VERPONDING">VERPONDING</option>
                                    <option value="KOHIR">KOHIR</option>
                                    <option value="PATOK">PATOK</option>
                                    <option value="BPKB">BPKB</option>
                                    <option value="IMB">IMB - Izin Mendirikan Bangunan</option>
                                    <option value="PBG">PBG - Persetujuan Bangunan Gedung</option>
                                    <option value="SK">SK - Surat Keputusan</option>
                                    <option value="SURAT UKUR">SURAT UKUR</option>
                                    <option value="SPPT PBB">SPPT PBB</option>
                                    <option value="BPHTB">BPHTB</option>
                                    <option value="SSB">SSB - Surat Setoran Bea</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold required">NO. SERTIFIKAT</label>
                                <input type="text" class="form-control" name="objek[${i}][no_sertifikat]" placeholder="Masukkan nomor sertifikat">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold required">NAMA PEMILIK</label>
                                <input type="text" class="form-control" name="objek[${i}][nama_pemilik]" placeholder="Masukkan nama pemilik">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold">LUAS TANAH</label>
                                <input type="text" class="form-control" name="objek[${i}][luas_tanah]" placeholder="Contoh: 150 m2">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold required">NO. SPPT</label>
                                <input type="text" class="form-control" name="objek[${i}][no_sppt]" placeholder="Masukkan nomor SPPT">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold required">PBB</label>
                                <input type="text" class="form-control" name="objek[${i}][pbb]" placeholder="Masukkan nilai PBB">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold required">NJOP</label>
                                <input type="text" class="form-control" name="objek[${i}][njop]" placeholder="Masukkan nilai NJOP">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold">NIB</label>
                                <input type="text" class="form-control" name="objek[${i}][nib]" placeholder="Masukkan NIB">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label text-secondary small fw-bold required">ALAMAT</label>
                                <input type="text" class="form-control" name="objek[${i}][alamat]" placeholder="Masukkan alamat lengkap objek">
                            </div>
                            @if ($dataPendukungObjek)
                                @if (in_array('nilai_ht', $dataPendukungObjek))
                                    <div class="col-md-4">
                                        <label class="form-label text-secondary small fw-bold">NILAI HT</label>
                                        <input type="text" class="form-control money" name="objek[${i}][nilai_ht]" placeholder="0">
                                    </div>
                                @endif
                                @if (in_array('nilai_transaksi', $dataPendukungObjek))
                                    <div class="col-md-4">
                                        <label class="form-label text-secondary small fw-bold required">NILAI TRANSAKSI</label>
                                        <input type="text" class="form-control money" name="objek[${i}][nilai_transaksi]" placeholder="0">
                                    </div>
                                @endif
                                @if (in_array('nilai_plafond', $dataPendukungObjek))
                                    <div class="col-md-4">
                                        <label class="form-label text-secondary small fw-bold required">NILAI PLAFOND</label>
                                        <input type="text" class="form-control money" name="objek[${i}][nilai_plafond]" placeholder="0">
                                    </div>
                                @endif
                            @endif
                            <div class="col-12">
                                <div class="p-3 border border-2 border-dashed border-secondary border-opacity-25 rounded-3 bg-light bg-opacity-25">
                                    <label class="form-label text-dark small fw-bold mb-1">DOKUMEN LAMPIRAN OBJEK</label>
                                    <p class="text-muted small mb-2" style="font-size: 0.75rem;">Unggah dokumen pendukung objek.</p>
                                    <input type="file" class="form-control bg-white" name="objek[${i}][file]">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;

                let iObjek = {{ $lastIndex }};

                $(".add_objek").on("click", function() {
                    iObjek++;
                    const html = objekTemplate(iObjek);
                    $(".card-body-objek").append(html);
                    
                    $(`.card_objek_${iObjek}`).fadeIn(300);

                    // Inisialisasi Select2 Desa untuk row baru
                    $(`.card_objek_${iObjek} .select2_desa`).select2({
                        theme: 'bootstrap-5',
                        width: '100%',
                        placeholder: "Pilih Desa",
                        ajax: {
                            url: "{{ route('desa.cari') }}",
                            type: "POST",
                            delay: 500,
                            data: function(param) {
                                return {
                                    _token: "{{ csrf_token() }}",
                                    search: param.term
                                }
                            },
                            processResults: function(data) {
                                return {
                                    results: data
                                };
                            }
                        }
                    });

                    $('.money').mask('#.##0', {
                        reverse: true
                    });
                });

                // Inisialisasi Select2 Desa awal
                $('.select2_desa').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: "Pilih Desa",
                    ajax: {
                        url: "{{ route('desa.cari') }}",
                        type: "POST",
                        delay: 500,
                        data: function(param) {
                            return {
                                _token: "{{ csrf_token() }}",
                                search: param.term
                            }
                        },
                        processResults: function(data) {
                            return {
                                results: data
                            };
                        }
                    }
                });

                $('.money').mask('#.##0', {
                    reverse: true
                });
            });
        </script>
    @endpush
@endcan