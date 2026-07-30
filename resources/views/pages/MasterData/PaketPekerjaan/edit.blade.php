@extends('layouts.admin')

@section('title')
    Edit Master Data Paket Pekerjaan
@endsection

@php
    $selectedJenis = old('jenis_data', $formOrder->jenis_data ? explode(',', $formOrder->jenis_data) : []);
@endphp

@section('content')
    <div class="card">
        <div class="card-body">
            <form id="formPaket" action="{{ route('master-data.form-order.update', $formOrder->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="row row__item g-3">
                    <div class="col-md-6">
                        <label class="form-label required">Nama Paket Pekerjaan</label>
                        <input type="text" class="form-control" name="nama" placeholder="Masukkan Nama Paket Pekerjaan"
                            autocomplete="off" value="{{ $formOrder->nama }}" />
                    </div>

                    <div class="col-md-6">
                        <label class="form-label required">Jenis Data</label>
                        <select name="jenis_data[]" multiple class="form-select select2" data-placeholder="Pilih Item">
                            <option value=""></option>
                            <option value="debitur" {{ in_array('debitur', $jenis_data) ? 'selected' : '' }}>Debitur</option>
                            <option value="bank" {{ in_array('bank', $jenis_data) ? 'selected' : '' }}>Bank</option>
                            <option value="badan hukum" {{ in_array('badan hukum', $jenis_data) ? 'selected' : '' }}>Badan Hukum</option>
                            <option value="broker" {{ in_array('broker', $jenis_data) ? 'selected' : '' }}>Broker</option>
                            <option value="developer" {{ in_array('developer', $jenis_data) ? 'selected' : '' }}>Developer</option>
                            <option value="badan usaha pembeli" {{ in_array('badan usaha pembeli', $jenis_data) ? 'selected' : '' }}>Badan Usaha Pembeli</option>
                            <option value="badan usaha penjual" {{ in_array('badan usaha penjual', $jenis_data) ? 'selected' : '' }}>Badan Usaha Penjual</option>
                            <option value="pendirian lembaga" {{ in_array('pendirian lembaga', $jenis_data) ? 'selected' : '' }}>Pendirian Lembaga</option>
                            <option value="usaha debitur" {{ in_array('usaha debitur', $jenis_data) ? 'selected' : '' }}>Usaha Debitur</option>
                            <option value="objek" {{ in_array('objek', $jenis_data) ? 'selected' : '' }}>Objek</option>
                            <option value="penjual" {{ in_array('penjual', $jenis_data) ? 'selected' : '' }}>Penjual</option>
                            <option value="pembeli" {{ in_array('pembeli', $jenis_data) ? 'selected' : '' }}>Pembeli</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label required">Data Pendukung</label>
                        <select name="data_pendukung[]" multiple class="form-select select2" data-placeholder="Pilih Item">
                            <option value=""></option>
                            <option value="nilai_ht" {{ in_array('nilai_ht', $data_pendukung) ? 'selected' : '' }}>Nilai HT</option>
                            <option value="nilai_transaksi" {{ in_array('nilai_transaksi', $data_pendukung) ? 'selected' : '' }}>Nilai Transaksi</option>
                            <option value="nilai_plafond" {{ in_array('nilai_plafond', $data_pendukung) ? 'selected' : '' }}>Nilai Plafond</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label required">SLA Internal</label>
                        <input type="number" value="{{ $formOrder->sla_internal }}" class="form-control" name="sla_internal">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label required">SLA Eksternal</label>
                        <input type="number" value="{{ $formOrder->sla_eksternal }}" class="form-control" name="sla_eksternal">
                    </div>

                    {{-- LOOPING DATA DETAIL SEBELUMNYA --}}
                    @foreach ($formOrder->details as $detail)
                        <div class="col-md-12 item-pekerjaan-row">
                            <label class="form-label required">Pekerjaan</label>
                            <div class="d-flex">
                                <select name="pekerjaan[]" class="form-select select2 select-pekerjaan" data-placeholder="Pilih Item">
                                    <option value=""></option>
                                    @foreach ($proses as $item)
                                        <option value="{{ $item->id }}" {{ $detail->pekerjaan_id == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-danger ms-2 btn-remove-item">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
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
        // Inisialisasi select2 awal untuk komponen yang sudah ada di halaman edit
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
        });

        // Tambah item dinamis secara live
        $('.add_more').on('click', function() {
            const html = `
                <div class="col-md-12 item-pekerjaan-row">
                    <label class="form-label required">Pekerjaan</label>
                    <div class="d-flex">
                        <select name="pekerjaan[]" class="form-select select2 select-pekerjaan" data-placeholder="Pilih Item">
                            <option value=""></option>
                            @foreach ($proses as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-danger ms-2 btn-remove-item">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `;

            // Append item ke form container
            $('.row__item').append(html);

            // Inisialisasi Select2 khusus pada komponen yang baru saja ditambahkan
            const $newSelect = $('.row__item .item-pekerjaan-row').last().find('select.select2');
            $newSelect.select2({
                theme: 'bootstrap-5',
                width: '100%'
            });

            // Tambahkan rules validasi jquery-validation jika fungsi tersedia
            if (typeof $newSelect.rules === 'function') {
                $newSelect.rules('add', {
                    required: true,
                    distinctPekerjaan: true,
                    messages: {
                        required: 'Pilih pekerjaan'
                    }
                });
            }
        });

        // Hapus item secara spesifik menggunakan Event Delegation
        $(document).on('click', '.btn-remove-item', function() {
            // Mencari elemen pembungkus terdekat dengan class .item-pekerjaan-row, lalu menghapusnya
            $(this).closest('.item-pekerjaan-row').remove();

            // Re-validasi constraint distinct pasca penghapusan baris dilakukan
            if (typeof $('.select-pekerjaan').valid === 'function') {
                $('.select-pekerjaan').valid();
            }
        });
    </script>
@endpush