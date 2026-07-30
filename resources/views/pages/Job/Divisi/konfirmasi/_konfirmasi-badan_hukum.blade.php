@if (count($badan_hukum))
    <div class="card mt-4">
        <div class="card-header">
            <div class="card-title">
                Badan Hukum
            </div>
        </div>
        <div class="card-body">

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama PT</th>
                            <th>NPWP</th>
                            <th>NIB</th>
                            <th>Nama Direktur</th>
                            <th>No. Telepon</th>
                            <th>Alamat</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($badan_hukum as $index => $item)
                            <tr>
                                <td>
                                    {{ $index + 1 }}
                                </td>

                                <td>
                                    {{ $item['nama_pt'] }}
                                </td>
                                <td>
                                    {{ $item['npwp'] }}
                                </td>
                                <td>
                                    {{ $item['nib'] }}
                                </td>
                                <td>
                                    {{ $item['nama_dirut'] }}
                                </td>
                                <td>
                                    {{ $item['no_telepon'] }}
                                </td>
                                <td>
                                    {{ $item['alamat'] }}
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
