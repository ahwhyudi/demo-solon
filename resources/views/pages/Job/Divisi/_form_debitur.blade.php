<x-job.detail.list-debitur-component :list-debitur="$jobDivisi->listDebitur" />

@can('data-pendukung/debitur/create')
    <form action="{{ route('job.divisi-data-pendukung.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="text" hidden name="job_divisi_id" value="{{ $jobDivisi->id }}">
        
        <div class="body__debitur">
            @php
                // Ambil old input; jika kosong, buat 1 baris default
                $oldDebiturs = old('debitur') ?? (session('form_data.debitur') ?? [['nama_lengkap' => '', 'phone' => '', 'email' => '']]);

                // Tentukan index terakhir untuk JS (agar append lanjut rapi)
                $lastIndex = is_array($oldDebiturs) ? array_key_last($oldDebiturs) : -1;
                if ($lastIndex === null) {
                    $lastIndex = -1;
                }
            @endphp

            @foreach ($oldDebiturs as $key => $item)
                <div class="card shadow-sm border border-light-subtle rounded-3 card_{{ $key }} @if ($key > 0) mt-4 @endif" data-index="{{ $key }}">
                    <div class="card-header bg-light bg-opacity-50 py-3 border-bottom border-light-subtle d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-dark">
                            <i class="bi bi-person-lines-fill me-2 text-primary"></i>Form Data Debitur
                        </h6>
                        @if ($key > 0)
                            <button type="button" class="btn btn-sm btn-outline-danger border-0 px-2 py-1" onclick="removeDebitur({{ $key }})" title="Hapus Form Ini">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        @endif
                    </div>
                    <div class="card-body pt-3">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold required">NAMA LENGKAP</label>
                                <input type="text"
                                    class="form-control @error("debitur.$key.nama_lengkap") is-invalid @enderror"
                                    name="debitur[{{ $key }}][nama_lengkap]"
                                    value="{{ old("debitur.$key.nama_lengkap", $item['nama_lengkap'] ?? '') }}"
                                    placeholder="Masukkan nama lengkap">
                                @error("debitur.$key.nama_lengkap")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold required">NOMOR TELEPON</label>
                                <input type="text"
                                    class="form-control @error("debitur.$key.phone") is-invalid @enderror"
                                    name="debitur[{{ $key }}][phone]"
                                    value="{{ old("debitur.$key.phone", $item['phone'] ?? '') }}"
                                    placeholder="Contoh: 081234567890">
                                @error("debitur.$key.phone")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold required">EMAIL</label>
                                <input type="email"
                                    class="form-control @error("debitur.$key.email") is-invalid @enderror"
                                    name="debitur[{{ $key }}][email]"
                                    value="{{ old("debitur.$key.email", $item['email'] ?? '') }}"
                                    placeholder="email@domain.com">
                                @error("debitur.$key.email")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <div class="p-3 border border-2 border-dashed border-secondary border-opacity-25 rounded-3 bg-light bg-opacity-25">
                                    <label class="form-label text-dark small fw-bold mb-1">DOKUMEN LAMPIRAN</label>
                                    <p class="text-muted small mb-2" style="font-size: 0.75rem;">Anda dapat memilih beberapa file sekaligus dengan menahan tombol Ctrl / Shift.</p>
                                    <!-- Perbaikan: langsung pakai file[] dan multiple -->
                                    <input type="file"
                                        class="form-control bg-white @error("debitur.$key.file.*") is-invalid @enderror"
                                        name="debitur[{{ $key }}][file][]" multiple>
                                    @error("debitur.$key.file.*")
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
            <button type="button" class="btn btn-outline-primary w-100 py-3 border-2 border-dashed fw-bold rounded-3 add_debitur shadow-sm transition-hover" style="border-style: dashed !important;">
                <i class="bi bi-plus-circle me-1"></i> Tambah Form Debitur Lainnya
            </button>
        </div>

        @if ($jobDivisi->status !== 'Batal Akad')
            <div class="d-flex justify-content-end mt-4 mb-5">
                <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm btn__simpan_debitur">
                    Simpan Data Debitur
                </button>
            </div>
        @endif
    </form>

    <!-- Modal Konfirmasi Hapus Form Debitur -->
    <div class="modal fade" id="modalDeleteCard" tabindex="-1" aria-hidden="true">
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
                        <button type="button" class="btn btn-danger btn-sm px-3 fw-bold" id="btn-confirm-remove-card">Ya, Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('addScript')
        @can('data-pendukung/debitur/add')
            <script>
                $(document).ready(function() {
                    $(".btn__simpan_debitur").on("click", function() {
                        $(this).prop("disabled", true);
                        $(this).text("Menyimpan...");
                        $(".loading__global").show();
                        $(this).closest("form").submit();
                    });

                    let i_debitur = {{ $lastIndex }};
                    let cardIndexToRemove = null;

                    // Fungsi append dengan UI baru yang selaras
                    $(".add_debitur").on("click", function() {
                        i_debitur++;
                        let i = i_debitur;
                        let cardNumber = $('.body__debitur .card').length + 1;

                        $(".body__debitur").append(`
                            <div class="card shadow-sm border border-light-subtle rounded-3 card_${i} mt-4" data-index="${i}" style="display:none;">
                                <div class="card-header bg-light bg-opacity-50 py-3 border-bottom border-light-subtle d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 fw-bold text-dark">
                                        <i class="bi bi-person-lines-fill me-2 text-primary"></i>Form Data Debitur 
                                    </h6>
                                    <button type="button" class="btn btn-sm btn-outline-danger border-0 px-2 py-1" onclick="removeDebitur(${i})" title="Hapus Form Ini">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </div>
                                <div class="card-body pt-3">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label text-secondary small fw-bold required">NAMA LENGKAP</label>
                                            <input type="text" class="form-control" name="debitur[${i}][nama_lengkap]" placeholder="Masukkan nama lengkap">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label text-secondary small fw-bold required">NOMOR TELEPON</label>
                                            <input type="text" class="form-control" name="debitur[${i}][phone]" placeholder="Contoh: 081234567890">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label text-secondary small fw-bold required">EMAIL</label>
                                            <input type="email" class="form-control" name="debitur[${i}][email]" placeholder="email@domain.com">
                                        </div>

                                        <div class="col-12">
                                            <div class="p-3 border border-2 border-dashed border-secondary border-opacity-25 rounded-3 bg-light bg-opacity-25">
                                                <label class="form-label text-dark small fw-bold mb-1">DOKUMEN LAMPIRAN</label>
                                                <p class="text-muted small mb-2" style="font-size: 0.75rem;">Anda dapat memilih beberapa file sekaligus dengan menahan tombol Ctrl / Shift.</p>
                                                <input type="file" class="form-control bg-white" name="debitur[${i}][file][]" multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `);
                        
                        // Efek muncul halus
                        $(`.card_${i}`).fadeIn(300);
                    });

                    // Hapus menggunakan Modal Bootstrap
                    window.removeDebitur = function(idx) {
                        cardIndexToRemove = idx;
                        let myModal = new bootstrap.Modal(document.getElementById('modalDeleteCard'));
                        myModal.show();
                    };

                    $("#btn-confirm-remove-card").on("click", function() {
                        if (cardIndexToRemove !== null) {
                            $(`.body__debitur .card_${cardIndexToRemove}`).fadeOut(300, function() {
                                $(this).remove();
                                // Re-numbering judul form setelah ada yang dihapus
                                $('.body__debitur .card').each(function(index) {
                                    $(this).find('h6').html(`<i class="bi bi-person-lines-fill me-2 text-primary"></i>Form Data Debitur #${index + 1}`);
                                });
                            });
                            $('#modalDeleteCard').modal('hide');
                            cardIndexToRemove = null;
                        }
                    });
                });
            </script>
        @endcan
    @endpush
@endcan