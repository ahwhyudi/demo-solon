@extends('layouts.admin')

@section('title')
    Master Data Paket Pekerjaan
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form id="formPaket" action="{{ route('master-data.form-order.store') }}" method="post">
                @csrf

                <div class="row row__item g-3">
                    <div class="col-md-6">
                        <label class="form-label required">Nama Paket Pekerjaan</label>
                        <input type="text" class="form-control" name="nama" placeholder="Masukkan Nama Paket Pekerjaan"
                            autocomplete="off" />
                    </div>

                    <div class="col-md-6">
                        <label class="form-label required">Jenis Data</label>
                        <select name="jenis_data[]" multiple class="form-select select2" data-placeholder="Pilih Item">
                            <option value=""></option>
                            <option value="debitur">Debitur</option>
                            <option value="bank">Bank</option>
                            <option value="badan hukum">Badan Hukum</option>
                            <option value="broker">
                                Broker
                            </option>
                            <option value="developer">
                                Developer
                            </option>
                            <option value="badan usaha pembeli">
                                Badan Usaha Pembeli
                            </option>
                            <option value="badan usaha penjual">
                                Badan Usaha Penjual
                            </option>
                            <option value="pendirian lembaga">
                                Pendirian Lembaga
                            </option>
                            <option value="usaha debitur">
                                Usaha Debitur
                            </option>
                            <option value="objek">Objek</option>
                            <option value="penjual">Penjual</option>
                            <option value="pembeli">Pembeli</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label ">Data Pendukung</label>
                        <select name="data_pendukung[]" multiple class="form-select select2" data-placeholder="Pilih Item">
                            <option value=""></option>
                            <option value="nilai_ht">Nilai HT</option>
                            <option value="nilai_transaksi">Nilai Transaksi</option>
                            <option value="nilai_plafond">Nilai Plafond</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label required">SLA Internal</label>
                        <input type="number" value="1" class="form-control" name="sla_internal">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label required">SLA Eksternal</label>
                        <input type="number" value="1" class="form-control" name="sla_eksternal">
                    </div>

                    <div class="col-md-12 col__item_1">
                        <label class="form-label required">Pekerjaan</label>
                        <div class="d-flex">
                            <select name="pekerjaan[]" class="form-select select2 select-pekerjaan"
                                data-placeholder="Pilih Item">
                                <option value=""></option>
                                @foreach ($proses as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-danger ms-2" onclick="removeItem(1)">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <div class="btn btn-outline-success add_more">
                        <i class="bi bi-plus"></i>
                        Tambah Item
                    </div>
                </div>

                <div class="mt-5">
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('addScript')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script>
        // Inisialisasi select2 awal
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
        });

        // Tambah item dinamis
        let i = 1;
        $('.add_more').on('click', function() {
            i++;
            const html = `
                <div class="col-md-12 col__item_${i}">
                    <label class="form-label required">Pekerjaan</label>
                    <div class="d-flex">
                        <select name="pekerjaan[]" class="form-select select2 select-pekerjaan" data-placeholder="Pilih Item">
                            <option value=""></option>
                            @foreach ($proses as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-danger ms-2" onclick="removeItem(${i})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `;

            $('.row__item').append(html);

            const $newSelect = $(`.col__item_${i} select.select2`);
            $newSelect.select2({
                theme: 'bootstrap-5',
                width: '100%'
            });

            // Tambahkan rules validasi untuk select pekerjaan yang baru
            $newSelect.rules('add', {
                required: true,
                distinctPekerjaan: true,
                messages: {
                    required: 'Pilih pekerjaan'
                }
            });
        });

        // Hapus item
        window.removeItem = function(idx) {
            $(`.col__item_${idx}`).remove();

            // Setelah hapus, revalidasi distinct agar error duplikat (kalau ada) diperbarui
            $('.select-pekerjaan').valid();
        };

        // window.removeItem = function(idx) {
        //     console.log(idx);
        //     $(`.col__item_${idx}`).remove();
        // };

        // window.removeItem = function(idx) {
        //     $(`.col__item_${idx}`).remove();
        // };
    </script>
@endpush
