<div class="card card_marketing">
    <div class="card-header">
        <div class="card-title">Marketing</div>
    </div>
    <div class="card-body">
        <div class="row list_marketing g-3">
            @php
                if (old('marketing') !== null) {
                    $marketings = array_values(old('marketing'));
                } else {
                    $marketings = isset($bank) ? $bank->marketing->map(fn($x) => ['nama' => $x->nama])->toArray() : [];
                    $marketings = array_values($marketings);
                }

                if (count($marketings) === 0) {
                    $marketings = [['nama' => '']];
                }
            @endphp

            @foreach ($marketings as $i => $marketing)
                <div class="col-md-6 col_marketing_{{ $i }}">
                    <label class="form-label required">Nama</label>
                    <div class="d-flex gap-3">
                        <input type="text" style="width:100%" name="marketing[{{ $i }}][nama]"
                            class="form-control" value="{{ $marketing['nama'] ?? '' }}">
                        <button type="button" class="btn btn-sm btn-danger remove_marketing"
                            data-target=".col_marketing_{{ $i }}">x</button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="add_more add_marketing py-3 text-center mt-3">
            + Tambah Marketing
        </div>
    </div>
</div>

@push('addScript')
    <script>
        let idx_marketing = {{ count($marketings) }};

        $(document).on("click", ".add_marketing", function() {
            const cur = idx_marketing++;
            const html = `
            <div class="col-md-6 col_marketing_${cur}">
                <label class="form-label required">Nama</label>
                <div class="d-flex gap-3">
                    <input type="text" style="width:100%" name="marketing[${cur}][nama]" class="form-control" value="">
                    <button type="button" class="btn btn-sm btn-danger remove_marketing" data-target=".col_marketing_${cur}">x</button>
                </div>
            </div>
        `;
            $(".list_marketing").append(html);
        });

        $(document).on("click", ".remove_marketing", function() {
            $($(this).data("target")).remove();
        });
    </script>
@endpush
