<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama Lengkap</th>
            <th>Nomor Telepon</th>
            <th>Email</th>
            <th>File</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $debitur)
            <tr>
                <td>{{ $debitur->nama }}</td>
                <td>{{ $debitur->nomor_telepon }}</td>
                <td>{{ $debitur->email }}</td>
                <td>
                    {{-- {{ $debitur->file }} --}}
                    @if ($debitur->file)
                        <a href="{{ asset('storage/debitur' . $debitur->file) }}" target="_blank">{{$debitur->file}}</a>
                    @else
                        Tidak ada file
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
