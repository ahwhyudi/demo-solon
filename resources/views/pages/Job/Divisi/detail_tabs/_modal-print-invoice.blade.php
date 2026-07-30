<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalPrintInv">
    Print Invoice
</button>

<form action="{{ route('invoice.store') }}" method="post" id="formPrintInv" target="_blank">
    @csrf
    <input type="hidden" name="job_divisi_id" value="{{ $jobDivisi->id }}">
    <div class="modal fade" id="modalPrintInv" tabindex="-1" aria-labelledby="modalPrintInvLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalPrintInvLabel">Form Print Invoice</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                {{--  --}}
                <div class="modal-body p-4">
    <!-- BAGIAN 1: RIWAYAT CETAKAN (DESAIN TABEL MODERN) -->
    <div class="mb-4">
        <!-- Header Section dengan Icon -->
        <div class="d-flex align-items-center justify-content-between mb-2">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-2 d-flex align-items-center justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022l-.074.997zm2.004.45a7 7 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342l-.36.933zm1.37.71a7 7 0 0 0-.439-.27l.493-.868c.191.108.376.226.556.353l-.61.785zm1.114 1.135a7 7 0 0 0-.27-.44c.176-.226.335-.465.474-.716l.849.529a8 8 0 0 1-.555.842l-.798-.215zm.693 1.488a7 7 0 0 0-.083-.51l.982-.187c.046.196.082.395.108.596l-.99.1z"/>
                        <path d="M8 4a.5.5 0 0 1 .5.5V8a.5.5 0 0 1-.252.434l-3.5 2a.5.5 0 0 1-.496-.868L7.5 7.866V4.5A.5.5 0 0 1 8 4z"/>
                        <path d="M8 16A8 8 0 1 0 8 0a8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
                    </svg>
                </div>
                <div>
                    <h6 class="fw-bold text-dark mb-0">Riwayat Cetakan Invoice</h6>
                    <small class="text-muted" style="font-size: 0.75rem;">Daftar seluruh arsip revisi dokumen yang pernah dicetak</small>
                </div>
            </div>
            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-normal" style="font-size: 0.75rem;">
                Total: <strong>{{ $jobDivisi->invoice->count() }}</strong> Dokumen
            </span>
        </div>

        <!-- Tabel Riwayat -->
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                    <thead class="bg-light text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <tr>
                            <th class="py-3 px-3 border-bottom-0" style="width: 5%;">No.</th>
                            <th class="py-3 border-bottom-0" style="width: 35%;">Nomor Invoice</th>
                            <th class="py-3 border-bottom-0" style="width: 20%;">Kategori</th>
                            <th class="py-3 border-bottom-0" style="width: 25%;">Tanggal Cetak</th>
                            <th class="py-3 px-3 text-end border-bottom-0" style="width: 15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse ($jobDivisi->invoice->sortByDesc('id') as $index => $item)
                            @php
                                $route = '';
                                if (in_array($item->kategori, ['penjual', 'pembeli'])) {
                                    $route = route('pdf.invoice.print', ['id' => $jobDivisi->id, 'kategori' => $item->kategori]);
                                } elseif ($item->kategori == 'bank') {
                                    $route = route('pdf.invoice-bank.print', ['id' => $jobDivisi->id, 'kategori' => $item->kategori]);
                                } elseif ($item->kategori == 'umum') {
                                    $route = route('pdf.invoice-umum.print', ['id' => $jobDivisi->id, 'kategori' => $item->kategori]);
                                }

                                // Menentukan apakah ini versi tertinggi (terbaru) di kategorinya
                                $maxVersiInCat = $jobDivisi->invoice->where('kategori', $item->kategori)->max('versi') ?? 1;
                                $isLatest = ($item->versi ?? 1) == $maxVersiInCat;
                            @endphp

                            <tr class="{{ $isLatest ? 'bg-primary bg-opacity-10 fw-semibold' : '' }}" style="transition: background-color 0.2s ease;">
                                <td class="px-3 text-muted">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <!-- Icon Dokumen -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="{{ $isLatest ? 'text-primary' : 'text-secondary opacity-50' }}" viewBox="0 0 16 16">
                                            <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                                            <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.305 11.305 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 0 1.01-.193 11.744 11.744 0 0 1-.51-.858 20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.315.3.479.398.095.056.196.096.284.113.04.007.078.008.106.003.018-.003.03-.009.038-.016a.152.152 0 0 0 .02-.037c.004-.015.006-.03.003-.046a.152.152 0 0 0-.02-.043c-.035-.052-.099-.089-.186-.113-.19-.052-.455-.078-.724-.058zm-.015-1.986c-.019.245-.032.487-.038.718-.112-.132-.224-.26-.336-.381.127-.107.253-.217.374-.337z"/>
                                        </svg>
                                        <span class="text-">{{ $item->kode }}</span>
                                        <span class="text-white badge {{ $isLatest ? 'bg-primary' : 'bg-secondary' }} rounded-pill" style="font-size: 0.65rem;">
                                            V{{ $item->versi ?? 1 }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <!-- Badge Kategori Styling -->
                                    @php
                                        $badgeColor = match(strtolower($item->kategori)) {
                                            'penjual' => 'bg-success-subtle text-success border-success-subtle',
                                            'pembeli' => 'bg-info-subtle text-info border-info-subtle',
                                            'bank' => 'bg-warning-subtle text-warning border-warning-subtle',
                                            default => 'bg-secondary-subtle text-secondary border-secondary-subtle',
                                        };
                                    @endphp
                                    <span class="badge border {{ $badgeColor }} px-2 py-1" style="font-size: 0.75rem;">
                                        {{ ucfirst($item->kategori) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-secondary d-flex align-items-center gap-1" style="font-size: 0.8rem;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="text-muted opacity-75" viewBox="0 0 16 16">
                                            <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                        </svg>
                                        {{ $item->created_at->format('d/m/Y') }}
                                        <span class="text-muted" style="font-size: 0.75rem;">({{ $item->created_at->format('H:i') }})</span>
                                    </div>
                                </td>
                                <td class="px-3 text-end">
                                    @if ($route)
                                        <a href="{{ $route }}?invoice_id={{ $item->id }}"
                                           target="_blank"
                                           class="btn btn-sm {{ $isLatest ? 'btn-primary shadow-sm' : 'btn-outline-secondary bg-white' }} d-inline-flex align-items-center gap-1 px-2 py-1"
                                           style="font-size: 0.75rem;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293V6.5z"/>
                                                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                                            </svg>
                                            Preview
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <div class="bg-light p-3 rounded-circle mb-2 text-secondary opacity-50">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1h-2.5a2 2 0 0 0-1.6.8L8 14.333 6.1 11.8a2 2 0 0 0-1.6-.8H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2.5a1 1 0 0 1 .8.4l1.9 2.533a1 1 0 0 0 1.6 0l1.9-2.533a1 1 0 0 1 .8-.4H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                                            </svg>
                                        </div>
                                        <span class="fw-semibold">Belum ada riwayat invoice</span>
                                        <small class="text-muted">Gunakan form di bawah untuk membuat cetakan invoice pertama.</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Divider Halus -->
    <div class="position-relative my-4">
        <hr class="text-secondary opacity-25">
        <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted fw-semibold uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">
            Atau
        </span>
    </div>

    <!-- BAGIAN 2: BUAT CETAKAN BARU / REVISI -->
    <div class="bg-light p-3 rounded-3 border">
        <div class="d-flex align-items-center gap-2 mb-3">
            <div class="bg-success bg-opacity-10 text-success p-1 rounded">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
            </div>
            <h6 class="fw-bold mb-0 text-dark">Buat Cetakan Baru / Revisi Invoice</h6>
        </div>

        <div class="mb-3">
            <label for="" class="form-label required fw-semibold text-secondary" style="font-size: 0.85rem;">
                Pilih Kategori Invoice
            </label>
            <select name="kategori" required id="" class="form-select __select_kategori_invoice shadow-sm">
                <option value="">-- Pilih Kategori Invoice --</option>
                {{-- @if (in_array('bank', $dataPendukung))
                    <option value="bank">Bank</option>
                @endif --}}

                @if (in_array('penjual', $dataPendukung) || in_array('developer', $dataPendukung))
                    <option value="penjual">Penjual</option>
                @endif

                @if (in_array('pembeli', $dataPendukung) || in_array('debitur', $dataPendukung))
                    <option value="pembeli">Pembeli</option>
                @endif

                <option value="umum">Umum</option>
            </select>
        </div>

        <div class="table-responsive table_penjual_pembeli bg-white rounded border mt-3" style="display: none; max-height: 250px; overflow-y: auto;">
            <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.85rem;">
                <thead class="bg-light text-secondary sticky-top">
                    <tr>
                        <th class="py-2 px-3 text-center" style="width: 40px;">#</th>
                        <th class="py-2">Nama Proses/Item Tagihan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jobDivisi->formOrder as $item)
                        <tr>
                            <td class="text-center px-3">
                                <div class="form-check d-flex justify-content-center">
                                    <input class="form-check-input item_print_inv item__{{ $item->id }}"
                                        type="checkbox" name="item_print_inv[{{ $item->id }}]"
                                        value="{{ $item->id }}" id="print_inv{{ $item->id }}">
                                </div>
                            </td>
                            <td>
                                <label for="print_inv{{ $item->id }}" class="d-block cursor-pointer py-1 mb-0 fw-normal">
                                    {{ $item->nama }}
                                </label>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary btn__print_inv">Print Invoice Baru</button>
                </div>
            </div>
        </div>
    </div>
</form>

@push('addScript')
    <script>
        $("#formPrintInv").on("submit", function() {
            $(".loading__global").show();

            // Tambahan: Otomatis menutup modal & menyembunyikan loading setelah 1 detik 
            // karena PDF sudah dibuka di tab sebelah (target="_blank")
            setTimeout(function() {
                $('#modalPrintInv').modal('hide');
                $(".loading__global").hide();
            }, 1000);
        });

        const invoicePrint = @json($jobDivisi->invoice);

        $(".__select_kategori_invoice").on("change", function() {
            const value = $(this).val();

            if (value === "penjual" || value === "pembeli" || value === "umum" || value === "bank") {
                $(".table_penjual_pembeli").show();

                // Filter invoice sesuai kategori yang dipilih
                const invoicesByCategory = invoicePrint.filter((item) => item.kategori === value);

                // Ambil invoice dengan versi tertinggi (terakhir dicetak) untuk dijadikan default checklist
                const latest_invoice = invoicesByCategory.reduce((max, item) => {
                    const currentVersi = item.versi || 1;
                    const maxVersi = max ? (max.versi || 1) : 0;
                    return currentVersi > maxVersi ? item : max;
                }, null);

                $(".item_print_inv").prop("checked", false);

                // Centang otomatis item-item dari revisi terakhir
                if (latest_invoice && latest_invoice.detail) {
                    latest_invoice.detail.forEach(element => {
                        $(`#modalPrintInv .item__${element.job_divisi_form_order_id}`).prop("checked",
                        true);
                    });
                }
            } else {
                $(".table_penjual_pembeli").hide();
            }
        });
    </script>
@endpush
