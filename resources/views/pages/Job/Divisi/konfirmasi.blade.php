@extends('layouts.admin')

@section('title')
    Tambah Job Divisi
@endsection

@section('content')
    <ul class="steps steps-primary steps-counter my-4">
        <li class="step-item ">Form Akad</li>
        <li class="step-item ">Form Data</li>
        <li class="step-item active">Konfirmasi</li>
    </ul>

    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="" class="fw-bold">
                        Group Proses
                    </label>
                    <p>
                        {{ $masterDataFormOrder->nama }}
                    </p>
                </div>
                <div class="col-md-3">
                    <label for="" class="fw-bold">
                        Divisi
                    </label>
                    <p>
                        {{ $divisi->nama }}
                    </p>
                </div>
                <div class="col-md-3">
                    <label for="" class="fw-bold">
                        Penanggung Jawab Berkas
                    </label>
                    <p>
                        {{ $userOps->name }}
                    </p>
                </div>
                <div class="col-md-3">
                    <label for="" class="fw-bold">
                        Nama Agent
                    </label>
                    <p>
                        {{ $form_akad['nama_agent'] }}
                    </p>
                </div>
                <div class="col-md-3">
                    <label for="" class="fw-bold">
                        Tanggal Rencana Akad
                    </label>
                    <p>
                        {{ $form_akad['tgl_rencana_akad'] }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    @if (count($objek))
        <div class="card mt-3">
            <div class="card-header">
                <div class="card-title" style="text-transform: uppercase">
                    Objek
                </div>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table ">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Jenis Sertifikat</th>
                                <th>No. Sertifikat</th>
                                <th>Nilai HT</th>
                                <th>Nilai Transaksi</th>
                                <th>Wilayah</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($objek as $index => $item)
                                <tr>
                                    <td>
                                        {{ $index + 1 }}
                                    </td>

                                    <td>
                                        {{ $item['jenis_sertifikat'] }}
                                    </td>
                                    <td>
                                        {{ $item['no_sertifikat'] }}
                                    </td>
                                    <td>
                                        {{ $item['nilai_ht'] }}
                                    </td>
                                    <td>
                                        {{ $item['nilai_transaksi'] }}
                                    </td>
                                    <td>
                                        {{ $item['desa']->name }}, {{ $item['desa']->kecamatan->name }},
                                        {{ $item['desa']->kecamatan->kota->name }},
                                        {{ $item['desa']->kecamatan->kota->provinsi->name }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <th class="text-center" colspan="12">Data Kosong</th>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    @endif

    @include('pages.Job.Divisi.konfirmasi._konfirmasi-debitur')
    @include('pages.Job.Divisi.konfirmasi._konfirmasi-bank')
    @include('pages.Job.Divisi.konfirmasi._konfirmasi-badan_hukum')
    @include('pages.Job.Divisi.konfirmasi._konfirmasi-developer')
    @include('pages.Job.Divisi.konfirmasi._konfirmasi-penjual')
    @include('pages.Job.Divisi.konfirmasi._konfirmasi-pembeli')

    <div class="card mt-3">
        <div class="card-header">
            <div class="card-title">
                List Pekerjaan
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                #
                            </th>
                            <th>Nama Pekerjaan</th>
                            <th>Divisi</th>

                        </tr>
                    </thead>

                    <tbody>
                        @for ($i = 0; $i < $kelipatanObjek; $i++)
                            @php
                                $no = 0;
                            @endphp
                            @foreach ($masterDataFormOrder->details as $index => $item)
                                @php
                                    $no++;
                                @endphp
                                <tr>
                                    <td>
                                        {{ $no }}
                                    </td>
                                    <td>
                                        {{ $item->pekerjaan->nama }}
                                    </td>
                                    <td style="text-transform: uppercase">
                                        {{ $item->pekerjaan->kategori }}
                                    </td>
                                </tr>
                            @endforeach
                        @endfor
                    </tbody>
                </table>
            </div>

        </div>
    </div>


    <div class="mt-5 d-flex gap-3 justify-content-end">
        <a href="{{ route('job.divisi-step2') }}" class="btn btn-secondary">
            Kembali Form Data
        </a>
        <form action="{{ route('job.divisi.store') }}" id="form_konfirmasi" method="post">
            @csrf
            <button class="btn btn-primary btn__save">Konfirmasi & Simpan</button>
        </form>
    </div>
@endsection
@push('addScript')
    <script>
        $(".btn__save").on("click", function() {
            $(".loading__global").show();
            $("#form_konfirmasi").submit();
        })
    </script>
@endpush
