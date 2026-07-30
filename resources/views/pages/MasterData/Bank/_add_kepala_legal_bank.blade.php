<div class="card card_kepala_legal">
    <div class="card-header">
        <div class="card-title">Kepala Legal</div>
    </div>
    <div class="card-body">
        <div class="row list_kepala_legal g-3">
            @php
                $kepalas = array_values(old('kepala_legal', [['nama' => '']]));
            @endphp

            @foreach ($kepalas as $i => $kepala)
                <div class="col-md-6 col_{{ $i }}">
                    <label class="form-label required">Nama</label>
                    <div class="d-flex gap-3">
                        <input type="text" style="width:100%" name="kepala_legal[{{ $i }}][nama]"
                            class="form-control" value="{{ $kepala['nama'] ?? '' }}">
                        <button type="button" class="btn btn-sm btn-danger remove_kepala_legal"
                            data-target=".col_{{ $i }}">x</button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="add_more add_kepala_legal py-3 text-center mt-3">
            + Tambah Kepala Legal
        </div>
    </div>
</div>

@push('addScript')
    <script>
        // mulai dari jumlah item old()
        let idx_kepala = {{ count($kepalas) + 1 }};

        $(document).on("click", ".add_kepala_legal", function() {
            const cur = idx_kepala++;
            const html = `
            <div class="col-md-6 col_${cur}">
                <label class="form-label required">Nama</label>
                <div class="d-flex gap-3">
                    <input
                        type="text"
                        style="width:100%"
                        name="kepala_legal[${cur}][nama]"
                        class="form-control"
                        value=""
                    >
                    <button
                        type="button"
                        class="btn btn-sm btn-danger remove_kepala_legal"
                        data-target=".col_${cur}"
                    >x</button>
                </div>
            </div>
        `;
            $(".list_kepala_legal").append(html);
        });

        $(document).on("click", ".remove_kepala_legal", function() {
            $($(this).data("target")).remove();
        });
    </script>
@endpush
