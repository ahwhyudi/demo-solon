@extends('layouts.admin')

@section('title')
    History Pekerjaan Staff
@endsection


@section('content')
    <div class="card">
        <div class="card-header">
            @include('pages.Laporan.PekerjaanStaff.Home._modal_select_pekerjaan_staff')
        </div>
        <div class="card-body">
            <div class="">

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>
                                    Nama
                                </th>
                                <th>
                                    Total Pekerjaan Keseluruhan
                                </th>
                                <th>
                                    Total Pekerjaan Selesai
                                </th>
                                <th>
                                    Total Pekerjaan Belum Selesai
                                </th>
                                <th>
                                    Lihat Detail
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($datas['users'] as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->total_pekerjaan}}</td>
                                    <td>{{ $user->total_pekerjaan_selesai}}</td>
                                    <td>{{ $user->total_pekerjaan_belum_selesai}}</td>
                                   <td>
                                   <a href="{{ route('laporan.detail-pekerjaan-staff', \Illuminate\Support\Facades\Crypt::encryptString($user->id)) }}" class="btn btn-secondary">
                                    Lihat Detail
                                </a>
                                </td>
                                 
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="12" class="text-center">
                                        Data Kosong
                                    </td>
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
