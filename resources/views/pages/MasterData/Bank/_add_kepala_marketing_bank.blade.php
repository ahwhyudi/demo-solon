<div class="card card_kepala_marketing">
    <div class="card-header">
        <div class="card-title">Kepala Marketing</div>
    </div>
    <div class="card-body">
        <div class="row list_kepala_marketing g-3">
            @php
                $kepalaMarketings = array_values(old('kepala_marketing', [['nama' => '']]));
            @endphp

            @foreach ($kepalaMarketings as $i => $kepala)
                <div class="col-md-6 col_{{ $i }}">
                    <label class="form-label required">Nama</label>
                    <div class="d-flex gap-3">
                        <input type="text" style="width:100%" name="kepala_marketing[{{ $i }}][nama]"
                            class="form-control" value="{{ $kepala['nama'] ?? '' }}">
                        <button type="button" class="btn btn-sm btn-danger remove_kepala_marketing"
                            data-target=".col_{{ $i }}">x</button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="add_more add_kepala_marketing py-3 text-center mt-3">
            + Tambah Kepala Marketing
        </div>
    </div>
</div>

@push('addScript')
    <script>
        // mulai dari jumlah data lama
        let idx_kepala_marketing = {{ count($kepalaMarketings) + 2 }};

        $(document).on("click", ".add_kepala_marketing", function() {
            const cur = idx_kepala_marketing++;
            const html = `
            <div class="col-md-6 col_${cur}">
                <label class="form-label required">Nama</label>
                <div class="d-flex gap-3">
                    <input
                        type="text"
                        style="width:100%"
                        name="kepala_marketing[${cur}][nama]"
                        class="form-control"
                        value=""
                    >
                    <button
                        type="button"
                        class="btn btn-sm btn-danger remove_kepala_marketing"
                        data-target=".col_${cur}"
                    >x</button>
                </div>
            </div>
        `;
            $(".list_kepala_marketing").append(html);
        });

        $(document).on("click", ".remove_kepala_marketing", function() {
            $($(this).data("target")).remove();
        });
    </script>
@endpush
