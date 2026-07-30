@if (count($bank))
    <div class="card mt-4">
        <div class="card-header">
            <div class="card-title">
                Bank
            </div>
        </div>
        <div class="card-body">

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Bank</th>
                            <th>Pimpinan</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($bank as $index => $item)
                            <tr>
                                <td>
                                    {{ $index + 1 }}
                                </td>

                                <td>
                                    {{ $item['nama_bank'] }}
                                </td>
                                <td>
                                    {{ $item['pimpinan'] }}
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
