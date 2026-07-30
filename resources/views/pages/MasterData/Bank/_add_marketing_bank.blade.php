<div class="card card_marketing">
    <div class="card-header">
        <div class="card-title">Marketing</div>
    </div>
    <div class="card-body">
        <div class="row list_marketing g-3">
            @php
                $marketings = old('marketing', [['nama' => '']]); // default minimal 1 baris
            @endphp

            @foreach ($marketings as $i => $marketing)
                <div class="col-md-6 col_{{ $i }}">
                    <label class="form-label required">
                        Nama
                    </label>
                    <div class="d-flex gap-3">
                        <input type="text" style="width:100%" name="marketing[{{ $i }}][nama]"
                            class="form-control" value="{{ $marketing['nama'] ?? '' }}">
                        <button type="button" class="btn btn-sm btn-danger remove_marketing"
                            data-target=".col_{{ $i }}">x</button>
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
        let idx = {{ count($marketings) + 2 }};
        $(document).on("click", ".add_marketing", function() {
            idx++;
            const html = `
            <div class="col-md-6 col_${idx}">
                <label for="marketing_${idx}_nama" class="form-label required">Nama</label>
                <div class="d-flex gap-3">
                    <input type="text" style="width:100%" name="marketing[${idx}][nama]" class="form-control"
                        value="{{ $marketing['nama'] ?? '' }}">
                    <button type="button" class="btn btn-sm btn-danger remove_marketing"
                        data-target=".col_${idx}">x</button>
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
