<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
    Tambah Data Bundle
</button>

<form action="{{ route('arsip.bundle.store') }}" method="post" id="bundleNomorForm">
    @csrf
    <input type="hidden" name="kategori" value="{{ $tipe }}">

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content ">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Form Bundle Nomor</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="" class="form-label required">
                            Nomor Bundle
                        </label>
                        <input type="text" class="form-control" name="nomor" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-5">
                            <label for="bundleBulan" class="form-label">
                                Bulan Nomor
                            </label>
                            <select class="form-select" id="bundleBulan" name="bulan"
                                {{ $tipe === 'ppat' ? 'disabled' : '' }}>
                                <option value="" {{ $bulan === null ? 'selected' : '' }}>Semua bulan</option>
                                @foreach ($bulanList as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ $bulan !== null && (int) $bulan === (int) $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($tipe === 'ppat')
                                <input type="hidden" name="bulan" value="">
                                <small class="form-hint">PPAT memakai penomoran tahunan.</small>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <label for="bundleTahun" class="form-label required">
                                Tahun Nomor
                            </label>
                            <select class="form-select" id="bundleTahun" name="tahun" required>
                                @foreach ($tahunList as $itemTahun)
                                    <option value="{{ $itemTahun }}"
                                        {{ (int) $tahun === (int) $itemTahun ? 'selected' : '' }}>
                                        {{ $itemTahun }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-center">
                            <button type="button" class="btn btn-outline-primary w-100" id="bundleApplyPeriod">
                                Tampilkan Nomor
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label required">
                            Keterangan
                        </label>
                        <textarea name="keterangan" class="form-control" required></textarea>
                    </div>

                    <div class="mb-0">
                        <label class="form-label required">Nomor yang Dibundling</label>
                        <div class="border rounded">
                            <div class="px-3 pt-3">
                                <div class="alert alert-info mb-0" id="bundlePeriodeInfo">
                                    Menampilkan nomor {{ $bulan ? $bulanList[$bulan] ?? $bulan : 'Tahun' }}
                                    {{ $tahun }} yang belum
                                    masuk bundle.
                                </div>
                            </div>
                            <div class="p-3 border-bottom">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-3">
                                        <input type="number" class="form-control" id="bundleNomorStart"
                                            placeholder="Dari nomor" min="1">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" class="form-control" id="bundleNomorEnd"
                                            placeholder="Sampai nomor" min="1">
                                    </div>
                                    <div class="col-md-auto">
                                        <label class="form-check mb-0">
                                            <input class="form-check-input" type="checkbox" id="bundleNomorSelectAll">
                                            <span class="form-check-label">Pilih semua yang tampil</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="p-3">
                                <div id="bundleNomorList" class="row g-2" style="max-height: 260px; overflow-y: auto;">
                                    <div class="col-12 text-secondary text-center py-4">
                                        Pilih bulan dan tahun, lalu klik Tampilkan Nomor.
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center gap-2 mt-3 d-none"
                                    id="bundleNomorPagination">
                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                        id="bundleNomorPrev">
                                        Sebelumnya
                                    </button>
                                    <div class="text-secondary small text-center" id="bundleNomorPageInfo"></div>
                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                        id="bundleNomorNext">
                                        Berikutnya
                                    </button>
                                </div>

                                <div class="mt-3 pt-3 border-top">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="fw-bold">Nomor terpilih</div>
                                        <span class="badge bg-primary-lt" id="bundleNomorCount">0 nomor</span>
                                    </div>
                                    <div class="alert alert-warning py-2 px-3 mb-3 d-none" id="bundleNomorLimitAlert">
                                        Maksimal 50 nomor dalam satu bundle.
                                    </div>
                                    <div class="alert alert-danger py-2 px-3 mb-3 d-none"
                                        id="bundleNomorSequenceAlert">
                                        Nomor harus berurutan tanpa loncat. Contoh: 1, 2, 3.
                                    </div>
                                    <div id="bundleNomorSelected" class="d-flex flex-wrap gap-2">
                                        <span class="text-secondary small">Belum ada nomor dipilih.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <small class="form-hint">Centang nomor akta yang ingin dimasukkan ke bundle ini.</small>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </div>
        </div>
    </div>
</form>

@push('addScript')
    <script>
        let data_nomor = @json($dataNomor);
        const maxBundleNomor = 50;
        const bundleNomorPerPage = 10;
        let bundleNomorCurrentPage = 1;
        const selectedBundleNomor = new Set();

        const bundleNomorList = $('#bundleNomorList');
        const bundleNomorStart = $('#bundleNomorStart');
        const bundleNomorEnd = $('#bundleNomorEnd');
        const bundleNomorSelected = $('#bundleNomorSelected');
        const bundleNomorCount = $('#bundleNomorCount');
        const bundleNomorSelectAll = $('#bundleNomorSelectAll');
        const bundleNomorLimitAlert = $('#bundleNomorLimitAlert');
        const bundleNomorSequenceAlert = $('#bundleNomorSequenceAlert');
        const bundleNomorForm = $('#bundleNomorForm');
        const bundleNomorPagination = $('#bundleNomorPagination');
        const bundleNomorPrev = $('#bundleNomorPrev');
        const bundleNomorNext = $('#bundleNomorNext');
        const bundleNomorPageInfo = $('#bundleNomorPageInfo');
        const bundleBulan = $('#bundleBulan');
        const bundleTahun = $('#bundleTahun');
        const bundleApplyPeriod = $('#bundleApplyPeriod');
        const bundlePeriodeInfo = $('#bundlePeriodeInfo');

        const showBundleLimitAlert = () => {
            bundleNomorLimitAlert.removeClass('d-none');

            setTimeout(() => {
                bundleNomorLimitAlert.addClass('d-none');
            }, 3000);
        };

        const normalizeBundleNomor = (item) => {
            if (typeof item === 'object' && item !== null) {
                return {
                    nomor: item.nomor?.toString() || '',
                    nama_proses: item.nama_proses || '-',
                    nama_debitur: item.nama_debitur || '-',
                    objek: item.objek || '-',
                };
            }

            return {
                nomor: item?.toString() || '',
                nama_proses: '-',
                nama_debitur: '-',
                objek: '-',
            };
        };

        const escapeHtml = (value) => {
            return $('<div>').text(value).html();
        };

        const getSortedSelectedNomor = () => {
            return Array.from(selectedBundleNomor)
                .map((nomor) => Number(nomor))
                .filter((nomor) => Number.isFinite(nomor))
                .sort((a, b) => a - b);
        };

        const isSelectedNomorSequential = () => {
            const selected = getSortedSelectedNomor();

            return selected.every((nomor, index) => index === 0 || nomor === selected[index - 1] + 1);
        };

        const updateSequenceAlert = () => {
            const isValid = isSelectedNomorSequential();

            bundleNomorSequenceAlert.toggleClass('d-none', isValid);

            return isValid;
        };

        const getFilteredBundleNomor = () => {
            const startNomor = Number(bundleNomorStart.val());
            const endNomor = Number(bundleNomorEnd.val());
            const hasStart = Number.isFinite(startNomor) && startNomor > 0;
            const hasEnd = Number.isFinite(endNomor) && endNomor > 0;

            return data_nomor.filter((nomor) => {
                const item = normalizeBundleNomor(nomor);
                const itemNomor = Number(item.nomor);

                if (!Number.isFinite(itemNomor)) {
                    return false;
                }

                if (hasStart && itemNomor < startNomor) {
                    return false;
                }

                if (hasEnd && itemNomor > endNomor) {
                    return false;
                }

                return true;
            });
        };

        const getPageBundleNomor = (items = getFilteredBundleNomor()) => {
            const startIndex = (bundleNomorCurrentPage - 1) * bundleNomorPerPage;

            return items.slice(startIndex, startIndex + bundleNomorPerPage);
        };

        const syncSelectedWithFilteredNomor = () => {
            const allowedNomor = new Set(getFilteredBundleNomor().map((nomor) => normalizeBundleNomor(nomor).nomor));

            Array.from(selectedBundleNomor).forEach((nomor) => {
                if (!allowedNomor.has(nomor)) {
                    selectedBundleNomor.delete(nomor);
                }
            });
        };

        const renderBundleNomorList = () => {
            const filteredNomor = getFilteredBundleNomor();

            if (!filteredNomor.length) {
                bundleNomorPagination.addClass('d-none');
                bundleNomorList.html(`
                    <div class="col-12 text-secondary text-center py-4">
                        Belum ada nomor untuk ditampilkan.
                    </div>
                `);
                bundleNomorSelectAll.prop('checked', false);
                return;
            }

            const totalPages = Math.max(1, Math.ceil(filteredNomor.length / bundleNomorPerPage));

            if (bundleNomorCurrentPage > totalPages) {
                bundleNomorCurrentPage = totalPages;
            }

            const pageNomor = getPageBundleNomor(filteredNomor);

            bundleNomorList.html(pageNomor.map((nomor) => {
                const item = normalizeBundleNomor(nomor);
                const checked = selectedBundleNomor.has(item.nomor) ? 'checked' : '';
                const disabled = !checked && selectedBundleNomor.size >= maxBundleNomor ? 'disabled' : '';

                return `
                    <div class="col-12 col-md-6 bundle-nomor-item">
                        <label class="form-selectgroup-item w-100">
                            <input type="checkbox" name="nomor_akta[]" value="${escapeHtml(item.nomor)}"
                                class="form-selectgroup-input bundle-nomor-checkbox" ${checked} ${disabled}>
                            <span class="form-selectgroup-label text-start w-100">
                                <span class="d-block fw-bold">Nomor: ${escapeHtml(item.nomor)}</span>
                                <span class="d-block text-secondary small text-truncate">${escapeHtml(item.nama_proses)}</span>
                                <span class="d-block text-secondary small text-truncate">Debitur: ${escapeHtml(item.nama_debitur)}</span>
                                <span class="d-block text-secondary small text-truncate">Objek: ${escapeHtml(item.objek)}</span>
                            </span>
                        </label>
                    </div>
                `;
            }).join(''));

            bundleNomorPagination.toggleClass('d-none', totalPages <= 1);
            bundleNomorPageInfo.text(
                `Halaman ${bundleNomorCurrentPage} / ${totalPages} - ${filteredNomor.length} nomor`);
            bundleNomorPrev.prop('disabled', bundleNomorCurrentPage <= 1);
            bundleNomorNext.prop('disabled', bundleNomorCurrentPage >= totalPages);

            updateSelectAllState(pageNomor.map((nomor) => normalizeBundleNomor(nomor).nomor));
        };

        const renderSelectedBundleNomor = () => {
            const selected = Array.from(selectedBundleNomor);

            bundleNomorCount.text(`${selected.length}/${maxBundleNomor} nomor`);

            if (!selected.length) {
                bundleNomorSelected.html('<span class="text-secondary small">Belum ada nomor dipilih.</span>');
                return;
            }

            bundleNomorSelected.html(selected.map((nomor) => {
                return `
                    <span class="badge bg-primary text-white">
                        ${nomor}
                        <button type="button" class="btn-close btn-close-white ms-2 bundle-nomor-remove"
                            data-nomor="${nomor}" aria-label="Hapus"></button>
                    </span>
                `;
            }).join(''));
        };

        const getVisibleNomor = () => {
            return getPageBundleNomor()
                .map((nomor) => normalizeBundleNomor(nomor))
                .map((item) => item.nomor);
        };

        const updateSelectAllState = (visibleNomor = getVisibleNomor()) => {
            const hasVisible = visibleNomor.length > 0;
            const isAllSelected = hasVisible && visibleNomor.every((nomor) => selectedBundleNomor.has(nomor
                .toString()));

            bundleNomorSelectAll.prop('checked', isAllSelected);
        };

        bundleNomorStart.add(bundleNomorEnd).on('input', function() {
            bundleNomorCurrentPage = 1;
            syncSelectedWithFilteredNomor();
            renderBundleNomorList();
            renderSelectedBundleNomor();
            updateSequenceAlert();
        });

        bundleNomorPrev.on('click', function() {
            if (bundleNomorCurrentPage > 1) {
                bundleNomorCurrentPage--;
                renderBundleNomorList();
            }
        });

        bundleNomorNext.on('click', function() {
            bundleNomorCurrentPage++;
            renderBundleNomorList();
        });

        bundleApplyPeriod.on('click', function() {
            bundleApplyPeriod.prop('disabled', true).text('Memuat...');
            bundleNomorList.html(`
                <div class="col-12 text-center py-4">
                    <div class="spinner-border text-primary mb-2" role="status"></div>
                    <div class="text-secondary">Mengambil nomor...</div>
                </div>
            `);

            $.ajax({
                    url: @json(route('arsip.bundle.index')),
                    method: 'GET',
                    dataType: 'json',
                    data: {
                        tipe: @json($tipe),
                        bulan: @json($tipe === 'ppat') ? '' : bundleBulan.val(),
                        tahun: bundleTahun.val(),
                    },
                })
                .done(function(res) {
                    data_nomor = res.dataNomor || [];
                    selectedBundleNomor.clear();
                    bundleNomorCurrentPage = 1;
                    bundleNomorStart.val('');
                    bundleNomorEnd.val('');
                    bundleNomorSelectAll.prop('checked', false);
                    bundlePeriodeInfo.text(`Menampilkan nomor ${res.labelPeriode} yang belum masuk bundle.`);
                    renderBundleNomorList();
                    renderSelectedBundleNomor();
                    updateSequenceAlert();
                })
                .fail(function(xhr) {
                    bundleNomorList.html(`
                        <div class="col-12 text-danger text-center py-4">
                            Gagal mengambil nomor. HTTP ${xhr.status || 'timeout'}.
                        </div>
                    `);
                })
                .always(function() {
                    bundleApplyPeriod.prop('disabled', false).text('Tampilkan Nomor');
                });
        });

        bundleNomorSelectAll.on('change', function() {
            const visibleNomor = getVisibleNomor();

            visibleNomor.forEach((nomor) => {
                if ($(this).is(':checked')) {
                    if (selectedBundleNomor.size >= maxBundleNomor) {
                        showBundleLimitAlert();
                        return false;
                    }

                    selectedBundleNomor.add(nomor);
                } else {
                    selectedBundleNomor.delete(nomor);
                }
            });

            renderBundleNomorList();
            renderSelectedBundleNomor();
            updateSequenceAlert();
        });

        bundleNomorList.on('change', '.bundle-nomor-checkbox', function() {
            const nomor = $(this).val().toString();

            if ($(this).is(':checked')) {
                if (selectedBundleNomor.size >= maxBundleNomor) {
                    $(this).prop('checked', false);
                    showBundleLimitAlert();
                    return;
                }

                selectedBundleNomor.add(nomor);
            } else {
                selectedBundleNomor.delete(nomor);
            }

            renderSelectedBundleNomor();
            updateSequenceAlert();
            updateSelectAllState();
        });

        bundleNomorSelected.on('click', '.bundle-nomor-remove', function() {
            selectedBundleNomor.delete($(this).data('nomor').toString());
            renderBundleNomorList();
            renderSelectedBundleNomor();
            updateSequenceAlert();
        });

        bundleNomorForm.on('submit', function(event) {
            if (!updateSequenceAlert()) {
                event.preventDefault();
                bundleNomorSequenceAlert.get(0)?.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            } else {
                $(".loading__global").show();
            }
        });

        renderSelectedBundleNomor();
    </script>
@endpush
