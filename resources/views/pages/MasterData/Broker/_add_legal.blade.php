@php
    $old_legal = old('legal') ?? [
        [
            'nama' => '',
        ],
    ];
    $idxLegal = 0;
@endphp

<div class="legal mt-4">
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                Legal
            </div>
        </div>
        <div class="card-body">
            <div class="row row__legal g-3">
                @foreach ($old_legal as $item)
                    @php
                        $idxLegal++;
                    @endphp

                    <div class="col-md-6 col_legal_{{ $idxLegal }}">
                        <div class="d-flex align-items-center gap-3">
                            <input type="text" class="form-control" name="legal[{{ $idxLegal }}][nama]"
                                value="{{ $item['nama'] }}">
                            <div class="">
                                <div class="btn btn-danger" onclick="removeItemLegal('col_legal_{{ $idxLegal }}')">
                                    <i class="bi bi-trash"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="add_more text-center mt-4 py-3">
                + Legal
            </div>
        </div>
    </div>
</div>

@push('addScript')
    <script>
        const removeItemLegal = (idx) => {
            $(`.${idx}`).remove();
        }

        let idxLegal = {{ $idxLegal + 2 }};
        $(".legal .add_more").on("click", function() {
            idxLegal++;

            const html = `
            <div class="col-md-6 col_legal_${idxLegal}">
                <div class="d-flex align-items-center gap-3">
                    <input type="text" class="form-control" name="legal[${idxLegal}][nama]">
                    <div class="">
                        <div class="btn btn-danger" onclick="removeItemLegal('col_legal_${idxLegal}')">
                            <i class="bi bi-trash"></i>
                        </div>
                    </div>
                </div>
            </div>
            `;
            $(".row__legal").append(html);
        });
    </script>
@endpush
