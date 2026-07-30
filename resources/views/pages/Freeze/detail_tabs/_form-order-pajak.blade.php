<div class="card mt-3">
    <div class="card-status-top bg-yellow"></div>
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
            <div class="card-title">
                Pajak
            </div>

        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>
                        No
                    </th>
                    <th>
                        Nama
                    </th>
                    <th>
                        Harga Estimasi
                    </th>
                    <th>
                        Harga Realisasi
                    </th>
                    <th>

                    </th>
                    <th>

                    </th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 0;
                @endphp
                @foreach ($dataFormOrder as $index => $item)
                    @php
                        $no++;
                    @endphp
                    <tr class=" row__item_{{ $item->id }}">
                        <td>
                            {{ $no }}
                        </td>
                        <td>
                            {{ $item->nama }}
                        </td>
                        <td>
                            <input type="text" name="form_order[{{ $item->id }}][harga_modal] "
                                class="money form-control" value="{{ number_format($item->harga_modal, '0') }}"
                                style="width:  10rem">
                        </td>
                        <td>
                            Rp. {{ number_format($item->harga_jual) }}
                            <input type="text" name="form_order[{{ $item->id }}][harga_jual]"
                                value="{{ number_format($item->harga_jual, '0') }}" hidden>
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                    name="form_order[{{ $item->id }}][masuk_invoice]" value="1" role="switch"
                                    id="switchCheckChecked{{ $item->id }}_inv"
                                    {{ $item->masuk_invoice ? 'checked' : '' }}>
                                <label class="form-check-label" for="switchCheckChecked{{ $item->id }}_inv">Masuk
                                    Invoice</label>
                            </div>
                        </td>
                        <td>
                            <div class="btn btn-danger" onclick="removeItem('{{ $item->id }}')">
                                <i class="bi bi-trash"></i>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
