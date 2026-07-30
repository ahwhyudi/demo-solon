@extends('layouts.admin')

@section('title')
    Laporan Penomoran
@endsection


@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">

            <div>
                @include('pages.Laporan.nomor-notaris._modal_add_nomor')
            </div>

            <div class="ms-auto">
                <a href="{{ route('laporan.nomor-notaris.export', $kategori) }}"class="btn btn-success shadow-sm">
                    <i class="bi bi-file-earmark-excel me-1"></i>
                    Export
                </a>
            </div>

        </div>
        <div class="card-body">
            <div class="">

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>
                                    Parent
                                </th>
                                <th>
                                    Nomor
                                </th>
                                <th>
                                    Proses
                                </th>
                                <th>
                                    Tanggal
                                </th>
                                <th>
                                    Nama Debitur
                                </th>
                                <th>
                                    Pengguna
                                </th>
                                <th>
                                    Upload Doc
                                </th>
                                <th>
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $item)
                                <tr>
                                    {{-- {{ dd($item) }} --}}
                                    <td>{{ $item->formOrder->jobDivisi->kode ?? 'Notaris Luar' }}</td>
                                    <td>{{ $item->nomor }}</td>
                                    <td>
                                        @if ($item->form_order_id)
                                            {{ $item->pekerjaan->nama }}
                                        @else
                                            {{ $item->formOrder->nama }}
                                        @endif
                                    </td>
                                    <td>{{ $item->tanggal }}</td>
                                    <td>{{ $item->nama_debitur_notaris_pengambil }}</td>
                                    @if ($item->notaris_pengambil)
                                        <td>{{ $item->notaris_pengambil }}</td>
                                    @else
                                        <td>{{ $item->rekanan ? 'Rekanan' : 'Internal' }}</td>
                                    @endif

                                    <td>
                                        <button class="btn btn-secondary"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="16" height="16" fill="currentColor" class="bi bi-cloud-upload"
                                                viewBox="0 0 16 16">
                                                <path fill-rule="evenodd"
                                                    d="M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773 16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318 1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383" />
                                                <path fill-rule="evenodd"
                                                    d="M7.646 4.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V14.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708z" />
                                            </svg></button>
                                        {{-- <button class="btn btn-primary">Bundle</button> --}}
                                    </td>
                                    <td>
                                        @include('pages.Laporan.nomor-notaris._modal_edit')

                                        @if ($item->rekanan === 1)
                                            <button class="btn btn-success btn-sm">
                                                Terima
                                            </button>
                                        @endif
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
                    {{ $items->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
