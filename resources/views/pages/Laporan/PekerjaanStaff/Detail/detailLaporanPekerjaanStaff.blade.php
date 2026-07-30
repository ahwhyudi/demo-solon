@extends('layouts.admin')

@section('title')
    Detail Laporan Pekerjaan Staff
@endsection


@section('content')
    <div class="card">
        <div class="card-header">
            @include('pages.Laporan.PekerjaanStaff.Detail._modal_sort_dataPekerjaanStaff')
        </div>
        <div class="card-body">
            <div class="">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>
                                    No
                                </th>
                                <th>
                                    Nama
                                </th>
                                <th>
                                    Nomor Job Divisi
                                </th>
                                <th>
                                    Nama Pekerjaan
                                </th>
                                <th>
                                    Tanggal Mulai
                                </th>
                                <th>
                                    Tanggal Selesai
                                </th>
                                <th>
                                    Keterangan
                                </th>
                            </tr>
                        </thead>
                      <tbody>
                        @forelse ($datas['jobOps'] as $data) 
                        <tr>
                            <td>{{ $loop->iteration }}</td>                          
                            <td>{{ $datas['user']->name }}</td>
                            <td>{{ $data->kode }}</td> 
                            <td>{{ $data->nama }}</td> 
                            <td>
                                @if ($loop->first)
                                    {{ $data->form_created_at }}
                                @else
                                {{ $data->created_at }}
                                @endif
                            </td>
                            <td>{{ $data->next_created_at ?? '-' }}</td>
                                @if ($data->status_penolakan != null)
                                <td>{{ 'Data ditolak - ' . $data->status_penolakan }}</td>
                                @else
                                <td>{{ $data->status }}</td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Data Kosong</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-5">
                    {{-- {{ $items->links() }} --}}
                </div>
            </div>
        </div>
    </div>
@endsection
