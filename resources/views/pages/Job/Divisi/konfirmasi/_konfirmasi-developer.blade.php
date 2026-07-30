@if (count($developer))
    <div class="card mt-4">
        <div class="card-header">
            <div class="card-title">
                Developer
            </div>
        </div>
        <div class="card-body">

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Developer</th>
                            <th>Pimpinan</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($developer as $index => $item)
                            <tr>
                                <td>
                                    {{ $index + 1 }}
                                </td>

                                <td>
                                    {{ $item['nama_developer'] }}
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
