<div class="card mt-3">
    <div class="card-status-top bg-blue"></div>
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
            <div class="card-title" style="text-transform: uppercase">
                {{ str_replace('_', ' ', $nama_kategori) }}
            </div>

        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>
                        ID
                    </th>
                    <th>
                        Nama
                    </th>
                    <th>
                        Harga Modal
                    </th>
                    <th>
                        Harga Jual
                    </th>
                    <th>
                        Diskon
                    </th>
                    <th>
                        Total
                    </th>
                    <th>

                    </th>
                    <th></th>
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

                    <tr class=" row__item_{{ $item->id }} {{ $item->status === 'Dibatalkan' ? 'text-danger' : '' }}">
                        <td>
                            {{ $item->id }}
                        </td>
                        <td>
                            {{ $item->nama }}
                            @if ($item->status === 'Dibatalkan')
                                <br> <small>
                                    Dibatalkan!
                                </small>
                            @endif
                        </td>
                        <td>
                            Rp. {{ number_format($item->harga_modal, '0', ',', '.') }}
                            <input type="text" name="form_order[{{ $item->id }}][harga_modal]"
                                value="{{ number_format($item->harga_modal, '0', ',', '.') }}" hidden
                                {{ $item->status === 'Dibatalkan' ? 'disabled' : '' }}>
                        </td>
                        <td>
                            <input type="text" class="form-control money" value="{{ (int) $item->harga_jual }}"
                                name="form_order[{{ $item->id }}][harga_jual]" style="width:  10rem"
                                {{ $item->status === 'Dibatalkan' ? 'disabled' : '' }}>
                        </td>
                        <td>
                            <input type="text" class="form-control money"
                                value="{{ number_format($item->diskon, 0, ',', '.') }}"
                                name="form_order[{{ $item->id }}][diskon]" style="width:  10rem"
                                {{ $item->status === 'Dibatalkan' ? 'disabled' : '' }}>
                        </td>
                        <td>
                            <input type="text" class="form-control money"
                                value="{{ number_format($item->harga_jual - $item->diskon, 0, ',', '.') }}" disabled
                                style="width:  10rem" {{ $item->status === 'Dibatalkan' ? 'disabled' : '' }}>
                        </td>
                        <td class="">
                            <div class="d-flex align-items-center">
                                @if ($item->status !== 'Dibatalkan')
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox"
                                            name="form_order[{{ $item->id }}][masuk_invoice]" value="1"
                                            role="switch" id="switchCheckChecked{{ $item->id }}_inv"
                                            {{ $item->masuk_invoice ? 'checked' : '' }}
                                            {{ $item->status === 'Dibatalkan' ? 'disabled' : '' }}>
                                        <label class="form-check-label"
                                            for="switchCheckChecked{{ $item->id }}_inv">Masuk
                                            Invoice</label>
                                    </div>
                                @endif
                            </div>
                        </td>

                        <td>
                            @if ($item->status !== 'Dibatalkan' && $jobDivisi->status !== 'Akad')
                                <div class="btn btn-danger" onclick="removeItem('{{ $item->id }}')">
                                    <i class="bi bi-trash"></i>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
