<div class="card card_legal">
    <div class="card-header">
        <div class="card-title">Legal</div>
    </div>
    <div class="card-body">
        <div class="row list_legal g-3">
            @php
                $legals = array_values(old('legal', [['nama' => '']]));
            @endphp

            @foreach ($legals as $i => $legal)
                <div class="col-md-6 col_{{ $i }}">
                    <label class="form-label required">Nama</label>
                    <div class="d-flex gap-3">
                        <input type="text" style="width:100%" name="legal[{{ $i }}][nama]"
                            class="form-control" value="{{ $legal['nama'] ?? '' }}">
                        <button type="button" class="btn btn-sm btn-danger remove_legal"
                            data-target=".col_{{ $i }}">x</button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="add_more add_legal py-3 text-center mt-3">
            + Tambah Legal
        </div>
    </div>
</div>

@push('addScript')
    <script>
        // mulai dari jumlah old() yang sudah ada
        let idx_legal = {{ count($legals) + 2 }};

        $(document).on("click", ".add_legal", function() {
            const cur = idx_legal++;
            const html = `
            <div class="col-md-6 col_${cur}">
                <label class="form-label required">Nama</label>
                <div class="d-flex gap-3">
                    <input
                        type="text"
                        style="width:100%"
                        name="legal[${cur}][nama]"
                        class="form-control"
                        value=""
                    >
                    <button
                        type="button"
                        class="btn btn-sm btn-danger remove_legal"
                        data-target=".col_${cur}"
                    >x</button>
                </div>
            </div>
        `;
            $(".list_legal").append(html);
        });

        $(document).on("click", ".remove_legal", function() {
            $($(this).data("target")).remove();
        });
    </script>
@endpush
