@extends('layouts.admin')

@section('title')
    Detail Penambahan Item
@endsection


@section('content')
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a href="#tabs-ringkasan" class="nav-link active" data-bs-toggle="tab" aria-selected="true"
                        role="tab"><!-- Download SVG icon from http://tabler.io/icons/icon/home -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-briefcase me-2 icon-2">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M3 7m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                            <path d="M8 7v-2a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v2" />
                            <path d="M12 12l0 .01" />
                            <path d="M3 13a20 20 0 0 0 18 0" />
                        </svg>
                        Penamabah Proses
                    </a>
                </li>

                <li class="nav-item" role="presentation">
                    <a href="#tabs-data-pendukung" class="nav-link" data-bs-toggle="tab" aria-selected="false"
                        tabindex="-1" role="tab"><!-- Download SVG icon from http://tabler.io/icons/icon/user -->

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-table-shortcut me-2 icon-2">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M3 13v-8a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-8" />
                            <path d="M3 10h18" />
                            <path d="M10 3v11" />
                            <path d="M2 22l5 -5" />
                            <path d="M7 21.5v-4.5h-4.5" />
                        </svg>

                        Data Pendukung
                    </a>
                </li>

            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane active show" id="tabs-ringkasan" role="tabpanel">

                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="" class="form-label">
                                Parent
                            </label>
                            <input type="text" class="form-control" value="{{ $jobDivisi->kode }}" disabled>
                        </div>
                        <div class="col-md-3">
                            <label for="" class="form-label">
                                Proses
                            </label>
                            <input type="text" class="form-control" value="{{ $jobDivisi->jenisAkad->nama }}" disabled>
                        </div>
                        <div class="col-md-3">
                            <label for="" class="form-label">
                                Status
                            </label>
                            <input type="text" class="form-control" value="{{ strtoupper($pembatalanItem->status) }}"
                                disabled>
                        </div>
                        <div class="col-md-3">
                            <label for="" class="form-label">
                                Tanggal Pembatalan
                            </label>
                            <input type="text" class="form-control" value="{{ strtoupper($pembatalanItem->created_at) }}"
                                disabled>
                        </div>
                        <div class="col-md-3">
                            <label for="" class="form-label">
                                Diajukan Oleh
                            </label>
                            <input type="text" class="form-control" value="{{ strtoupper($pembatalanItem->user->name) }}"
                                disabled>
                        </div>
                    </div>

                    <div class="mt-4">

                        <div class="row">
                            <div class="col-md-8">
                                <div class="table-responsive">
                                    <table class="table table-bordered  ">
                                        <thead>
                                            <tr>
                                                <th>
                                                    Pekerjaan
                                                </th>
                                                <th>
                                                    Kategori
                                                </th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pembatalanItem->detail as $item)
                                                <tr>
                                                    <td>
                                                        {{ $item->pekerjaan->nama }}
                                                    </td>
                                                    <td style="text-transform: uppercase">
                                                        {{ str_replace('_', '', $item->pekerjaan->kategori) }}
                                                    </td>
                                                    
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="tab-pane " id="tabs-data-pendukung" role="tabpanel">
                    @include('pages.Freeze._card_data_pendukung')
                </div>
            </div>

            <div class="mt-3">
                <div class="row">
                    <div class="col-md-4">
                        <label for="" class="form-label">
                            Keterangan Pembatalan Item
                        </label>
                        <textarea readonly class="form-control">{{ $pembatalanItem->keterangan }}</textarea>
                    </div>
                </div>
            </div>

            @if ($pembatalanItem->status === 'menunggu persetujuan')
                @can('job/pembatalan-item/edit')
                    <div class="mt-4">
                        <div class="d-flex gap-4">
                            @include('pages.Job.pembatalan-item._modal_tolak')
                            <form action="{{ route('job.pembatalan-items.update', $pembatalanItem->id) }}" method="post">
                                @csrf
                                @method('PUT')
                                <button name="approved" value="1" class="btn__submit btn btn-primary">
                                    Setujui
                                </button>
                            </form>
                        </div>
                    </div>
                @endcan
            @endif
        </div>
    </div>
@endsection


@push('addScript')
    <script>
        $(".btn__submit").on("click", function() {
            $(".loading__global").show();
        });
    </script>
@endpush
