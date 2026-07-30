<div class="card card_kepala_legal">
    <div class="card-header">
        <div class="card-title">Kepala Legal</div>
    </div>
    <div class="card-body">
        <div class="row list_kepala_legal g-3">
            @php
                // prioritas: old() -> DB (edit) -> default 1 baris
                if (old('kepala_legal') !== null) {
                    $kepalas = array_values(old('kepala_legal'));
                } else {
                    $kepalas = isset($bank) ? $bank->kepalaLegal->map(fn($x) => ['nama' => $x->nama])->toArray() : [];
                    $kepalas = array_values($kepalas);
                }

                if (count($kepalas) === 0) {
                    $kepalas = [['nama' => '']];
                }
            @endphp

            @foreach ($kepalas as $i => $kepala)
                <div class="col-md-6 col_kepala_legal_{{ $i }}">
                    <label class="form-label required">Nama</label>
                    <div class="d-flex gap-3">
                        <input type="text" style="width:100%" name="kepala_legal[{{ $i }}][nama]"
                            class="form-control" value="{{ $kepala['nama'] ?? '' }}">
                        <button type="button" class="btn btn-sm btn-danger remove_kepala_legal"
                            data-target=".col_kepala_legal_{{ $i }}">x</button>
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
        let idx_kepala_legal = {{ count($kepalas) }};

        $(document).on("click", ".add_kepala_legal", function() {
            const cur = idx_kepala_legal++;
            const html = `
            <div class="col-md-6 col_kepala_legal_${cur}">
                <label class="form-label required">Nama</label>
                <div class="d-flex gap-3">
                    <input type="text" style="width:100%" name="kepala_legal[${cur}][nama]" class="form-control" value="">
                    <button type="button" class="btn btn-sm btn-danger remove_kepala_legal" data-target=".col_kepala_legal_${cur}">x</button>
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
