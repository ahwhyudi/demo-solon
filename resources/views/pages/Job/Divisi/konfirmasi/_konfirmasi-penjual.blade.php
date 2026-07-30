@if (count($penjual))
    <div class="card mt-4">
        <div class="card-header">
            <div class="card-title">
                Penjual
            </div>
        </div>
        <div class="card-body">

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Lengkap</th>
                            <th>NIK</th>
                            <th>No. Telepon</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($penjual as $index => $item)
                            <tr>
                                <td>
                                    {{ $index + 1 }}
                                </td>

                                <td>
                                    {{ $item['nama_lengkap'] }}
                                </td>
                                <td>
                                    {{ $item['nik'] }}
                                </td>
                                <td>
                                    {{ $item['phone'] }}
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
