<!-- Button trigger modal -->
<div class="text-primary fw-bold" data-bs-toggle="modal" data-bs-target="#ModalParentOps{{ $key }}">
    {{ $jobDivisi->kode }}
</div>

<!-- Modal -->
<div class="modal fade" id="ModalParentOps{{ $key }}" tabindex="-1"
    aria-labelledby="ModalParentOps{{ $key }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="ModalParentOps{{ $key }}Label">
                    Parent {{ $jobDivisi->kode }}
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <label for="" class="fw-bold">
                            Jenis Akad
                        </label>
                        <p class="text-secondary">
                            {{ $jobDivisi->jenisAkad->nama }}
                        </p>
                    </div>
                    <div class="col-md-4">
                        <label for="" class="fw-bold">
                            Status Akad
                        </label>
                        <p class="text-secondary">
                            {{ $jobDivisi->status }}
                        </p>
                    </div>
                    <div class="col-md-4">
                        <label for="" class="fw-bold">
                            Tgl. Rencana Akad
                        </label>
                        <p class="text-secondary">
                            {{ $jobDivisi->tanggal_rencana_akad }}
                        </p>
                    </div>
                    {{-- <div class="col-md-4">
                        <label for="" class="fw-bold">
                            Penanggung Jawab Berkas
                        </label>
                        <p class="text-secondary">
                            {{ $jobDivisi->userOps->name }}
                        </p>
                    </div> --}}
                </div>
                @if (count($jobDivisi->objek))
                    <div class="card">
                        <div class="card-status-top bg-blue"></div>
                        <div class="card-body">
                            <div class="card-title">
                                Objek
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>
                                            Desa
                                        </th>
                                        <th>
                                            Jenis Sertifikat
                                        </th>
                                        <th>
                                            No. Sertifikat
                                        </th>
                                        <th>
                                            Luas Tanah
                                        </th>
                                        <th>
                                            Nilai HT
                                        </th>
                                        <th>
                                            Nilai Transaksi
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($jobDivisi->objek as $item)
                                        <tr>
                                            {{-- {{ dd($item) }} --}}
                                            <td>
                                                {{ $item->desa->name }}
                                            </td>
                                            <td>
                                                {{ $item->jenis_sertifikat }}
                                            </td>
                                            <td>
                                                {{ $item->no_sertifikat }}
                                            </td>
                                            <td>
                                                {{ $item->luas_tanah }} M
                                            </td>
                                            <td>
                                                Rp. {{ number_format($item->nilai_ht, '2', ',', '.') }}
                                            </td>
                                            <td>
                                                Rp. {{ number_format($item->nilai_transaksi, '2', ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                @endif

                @if (count($jobDivisi->debitur))
                    <div class="card mt-3">
                        <div class="card-status-top bg-green"></div>
                        <div class="card-body">
                            <div class="card-title">
                                Debitur
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>
                                            Nama
                                        </th>
                                        <th>
                                            No. KTP
                                        </th>
                                        <th>
                                            No. Tlp
                                        </th>
                                        <th>
                                            Email
                                        </th>
                                        <th>
                                            File
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jobDivisi->debitur as $item)
                                        <tr>
                                            <td>
                                                {{-- {{ dd($item) }} --}}
                                                {{ $item->nama }}
                                            </td>
                                            <td>
                                                {{ $item->nik }}
                                            </td>
                                            <td>
                                                {{ $item->nomor_telepon }}
                                            </td>
                                            <td>
                                                {{ $item->email }}
                                            </td>
                                            <td>
                                                <a href="{{ asset('storage/' . $item->file) }}" target="_blank">{{ $item->file}}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>

            </div>
        </div>
    </div>
</div>
