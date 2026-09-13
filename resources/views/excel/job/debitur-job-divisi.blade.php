<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama Lengkap</th>
            <th>NIK</th>
            <th>Nomor Telepon</th>
            <th>Email</th>
            <th>Tempat Lahir</th>
            <th>Tanggal Lahir</th>
            <th>Alamat Lengkap</th>
            <th>File</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $debitur)
            <tr>
                <td>{{ $debitur->nama }}</td>
                <td>{{ $debitur->nik ?? '-' }}</td>
                <td>{{ $debitur->nomor_telepon ?? '-' }}</td>
                <td>{{ $debitur->email ?? '-' }}</td>
                <td>
                    {{ $debitur->tempat_lahir ?? '-' }}
                </td>
                <td>
                  
                    {{ $debitur->tanggal_lahir ? \Carbon\Carbon::parse($debitur->tanggal_lahir)->format('d/m/Y') : '-' }}
                </td>
                <td>{{ $debitur->alamat_lengkap ?? '-' }}</td>
                <td>
                    @if ($debitur->files && $debitur->files->count() > 0)
                        <ul class="list-unstyled mb-0">
                            @foreach ($debitur->files as $f)
                                <li>
                                    <a href="{{ asset('storage/' . $f->file_path) }}" target="_blank">
                                        {{ $f->file_name ?? 'Dokumen' }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        Tidak ada file
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
