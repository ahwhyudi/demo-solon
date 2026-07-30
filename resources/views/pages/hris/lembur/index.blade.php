@extends('layouts.admin')

@section('title')
    Lembur Karyawan
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="mb-4">
                <a href="{{ route('hris.lembur.create') }}" class="btn btn-primary">
                    Tambah Data
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>
                                Nama
                            </th>
                            <th>
                                Tanggal Mulai
                            </th>
                            <th>
                                Tanggal Selesai
                            </th>
                            <th>
                                Alasan
                            </th>
                            <th>
                                Lama Lembur
                            </th>
                            <th>
                                Status
                            </th>
                            <th>
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>
                                    {{ $item->user->name }}
                                </td>
                                <td>
                                    {{ $item->start_date }}
                                </td>
                                <td>
                                    {{ $item->end_date }}
                                </td>
                                <td>
                                    {{ $item->keterangan }}
                                </td>
                                <td>
                                    {{ $item->lama }}
                                </td>
                                <td style="text-transform: uppercase">
                                    @if ($item->status === 'rejected')
                                        <span class="badge text-bg-danger">{{ $item->status }}</span>
                                    @elseif($item->status === 'approved')
                                        <span class="badge text-bg-success">{{ $item->status }}</span>
                                    @else
                                        <span class="badge text-bg-warning">{{ $item->status }}</span>
                                    @endif
                                </td>
                                <td>

                                    @if ($item->status === 'menunggu persetujuan')
                                        @can('hris/lembur/edit')
                                            <div class="dropdown-up">
                                                <button class="btn btn-secondary dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false" data-bs-boundary="body">
                                                    Aksi
                                                </button>
                                                <ul class="dropdown-menu">

                                                    <li>
                                                        <div class="dropdown-item text-success">
                                                            <form action="{{ route('hris.lembur.update', $item->id) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="text" value="approved" name="status" hidden>
                                                                <span class="btn_setuju">
                                                                    <i class="bi bi-check-circle-fill"></i> Setujui
                                                                </span>
                                                            </form>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="dropdown-item text-danger">
                                                            <form action="{{ route('hris.lembur.update', $item->id) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="text" value="rejected" name="status" hidden>
                                                                <span class="btn_reject">
                                                                    <i class="bi bi-x-circle-fill"></i> Tolak</a>
                                                                </span>
                                                            </form>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        @endcan
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
@endsection

@push('addScript')
    <script>
        $(".btn_setuju").on("click", function() {
            $(".loading__global").show();
            var form = $(this).closest("form");
            form.submit();
        });
        $(".btn_reject").on("click", function() {
            $(".loading__global").show();
            var form = $(this).closest("form");
            form.submit();
        });
    </script>
@endpush
