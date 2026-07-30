@php

    if (old('marketing')) {
        $old_marketing = old('marketing');
    } elseif (isset($item) && $item->marketing->isNotEmpty()) {
        $old_marketing = $item->marketing
            ->map(function ($m) {
                return [
                    'nama' => $m->nama,
                    'no_telepon' => $m->no_telepon,
                ];
            })
            ->toArray();
    }
    else{

        $old_marketing = old('marketing') ?? [
            [
                'nama' => '',
                'no_telepon' => '',
            ],
        ];
    }
    $idxM = 0;
@endphp

<div class="marketing">
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                Marketing
            </div>
        </div>
        <div class="card-body">
            <div class="list__marketing">
                @foreach ($old_marketing as $index => $m)
                    <div class="row row_marketing_{{ $idxM }}">
                        <div class="col-md-6">
                            <label for="" class="form-label required">
                                Nama
                            </label>
                            <input type="text" class="form-control" name="marketing[{{ $idxM }}][nama]" value="{{ $m['nama']??'' }}" required>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-3 align-items-end">
                                <div class="w-100">
                                    <label for="" class="form-label required">
                                        No. Telepon
                                    </label>
                                    <input type="number" class="form-control" name="marketing[{{ $idxM }}][no_telepon]" value="{{ $m['no_telepon'] }}" required>
                                </div>
                                <div class="">
                                    <div class="btn btn-danger"
                                        onclick="removeItem('row_marketing_{{ $idxM }}')">
                                        <i class="bi bi-trash"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @php
                        $idxM++;
                    @endphp
                @endforeach
            </div>
            <div class="text-center mt-4">
                <div class="add_more py-3 add_marketing">
                    + Marketing
                </div>
            </div>
        </div>
    </div>
</div>


@push('addScript')
    <script>
        const removeItem = (idx) => {
            console.log('idx', idx)
            $(`.${idx}`).remove();
        }

        let idxM = {{ $idxM + 2 }};

        $(".add_marketing").on("click", function() {
            idxM++;
            $(".list__marketing").append(`
                 <div class="row row_marketing_${idxM} mt-3">
                        <div class="col-md-6">
                            <label for="" class="form-label required">
                                Nama
                            </label>
                            <input type="text" class="form-control" name="marketing[${idxM}][nama]">
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-3 align-items-end">
                                <div class="w-100">
                                    <label for="" class="form-label required">
                                        No. Telepon
                                    </label>
                                    <input type="text" class="form-control" name="marketing[${idxM}][no_telepon]">
                                </div>
                                <div class="">
                                    <div class="btn btn-danger"
                                        onclick="removeItem('row_marketing_${idxM}')">
                                        <i class="bi bi-trash"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

            `)
        })
    </script>
@endpush
