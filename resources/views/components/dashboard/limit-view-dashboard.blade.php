<div>
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle border">
            <thead class="table-dark">
                <tr>
                    <th scope="col" style="width: 80px;">No</th>
                    <th class="">
                        Parent
                    </th>
                    <th class="">
                        Proses
                    </th>
                    <th class="">
                        Nomor Akta
                    </th>
                    <th scope="col">Tanggal Penginputan Nomor</th>
                    <th scope="col">Tanggal Expired</th>
                    <th scope="col" style="width: 150px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($nomorPpats as $ppat)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>

                        <th class="text-primary ">
                            {{ $ppat->formOrder->jobDivisi->kode ?? '-' }}
                        </th>

                        <td class="">
                            {{ $ppat->formOrder->nama }}
                        </td>

                        <td class="">
                            {{ $ppat->nomor }}
                        </td>

                        <td>{{ $ppat->created_at->translatedFormat('d F Y') }}</td>

                        <td class="text-danger fw-bold">
                            {{ \Carbon\Carbon::parse($ppat->tanggal_expired)->translatedFormat('d F Y') }}
                        </td>

                        <td>
                            <span class="badge bg-danger text-white">Mendekati Expired</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">
                            <p class="mb-0">Tidak ada data PPAT yang expired dalam 1 minggu ke depan.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
