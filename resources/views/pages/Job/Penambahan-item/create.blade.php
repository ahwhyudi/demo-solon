@extends('layouts.admin')

@section('title')
    Penambahan Item
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('job.penambahan-item.store') }}" method="post">
                @csrf
                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="" class="form-label required">
                            Pilih Parent
                        </label>
                        <select name="parent" class="form-select select2" data-placeholder="Pilih Parent">
                            <option value=""></option>
                            @foreach ($jobDivisi as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->kode }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="" class="form-label required">
                            keterangan
                        </label>
                        <textarea name="keterangan" class="form-control" placeholder="Masukkan Keterangan"></textarea>
                    </div>
                </div>

                <div class="list__pekerjaan mt-4">
                    <div class="card card_row_0 mt-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="" class="form-label required">
                                        Pilih Pekerjaan
                                    </label>
                                    <select name="pekerjaan[]" id="" class="form-select select__pekerjaan"
                                        data-placeholder="Pilih Pekerjaan">
                                        <option value=""></option>
                                        @foreach ($pekerjaan as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <div class="d-flex align-items-end gap-3">
                                        <div class="flex-1">
                                            <label for="" class="form-label required">
                                                Harga Jual
                                            </label>
                                            <input type="text" class="form-control money" name="harga_jual[]">
                                        </div>

                                        <div class="flex-1">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                    id="switchCheckChecked" checked name="masuk_invoice[]">
                                                <label class="form-check-label" for="switchCheckChecked">Masuk
                                                    Invoice</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end mt-2">
                                <div class="btn btn-danger" onclick="removePekerjaan('card_row_0')">
                                    <i class="bi bi-trash"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-3 text-center">
                    <div class="add_more py-3 ">
                        Tambah Pekerjaan
                    </div>
                </div>

                <div class="mt-4">
                    <button type="button" class="btn btn-primary btn__simpan">Simpan</button>
                </div>

            </form>
        </div>
    </div>
@endsection

@push('addScript')
    <script>
        $(document).ready(function() {
            $(".btn__simpan").on("click", function() {
                $(".loading__global").show();
                $(this).closest("form").submit();
            })
        })
    </script>

    <script>
        $(".select__pekerjaan").select2({
            theme: 'bootstrap-5',
        });

        const removePekerjaan = (el) => {
            $(`.${el}`).remove();
        }

        let i = 2;
        $(".add_more").on("click", function() {
            i++;
            $(".list__pekerjaan").append(`
                    <div class="card card_row_${i} mt-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="" class="form-label required">
                                        Pilih Pekerjaan
                                    </label>
                                    <select name="pekerjaan[]" id="" class="form-select select__pekerjaan"
                                        data-placeholder="Pilih Pekerjaan">
                                        <option value=""></option>
                                        @foreach ($pekerjaan as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <div class="d-flex align-items-end gap-3">
                                        <div class="flex-1">
                                            <label for="" class="form-label required">
                                                Harga Jual
                                            </label>
                                            <input type="text" class="form-control money" name="harga_jual[]">
                                        </div>

                                        <div class="flex-1">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                    id="switchCheckChecked" checked name="masuk_invoice[]">
                                                <label class="form-check-label" for="switchCheckChecked">Masuk Invoice</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end mt-2">
                                <div class="btn btn-danger" onclick="removePekerjaan('card_row_${i}')">
                                    <i class="bi bi-trash"></i>
                                </div>
                            </div>
                        </div>
                    </div>

            `);

            $(".select__pekerjaan").select2({
                theme: 'bootstrap-5',
            });

            $('.money').mask('#.##0', {
                reverse: true
            });
        })
    </script>
@endpush
