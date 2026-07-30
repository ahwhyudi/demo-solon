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
                            <input type="text" class="form-control" value="{{ strtoupper($penambahan_item->status) }}"
                                disabled>
                        </div>
                        <div class="col-md-3">
                            <label for="" class="form-label">
                                Tanggal Penambahan
                            </label>
                            <input type="text" class="form-control"
                                value="{{ strtoupper($penambahan_item->created_at) }}" disabled>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="table-responsive">
                            <table class="table table-bordered  ">
                                <thead>
                                    <tr>
                                        <th>
                                            Pekerjaan
                                        </th>
                                        <th>
                                            Harga Jual
                                        </th>
                                        <th>

                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($penambahan_item->detail as $item)
                                        <tr>
                                            <td>
                                                {{ $item->pekerjaan->nama }}
                                            </td>
                                            <td>
                                                {{ $item->harga_jual }}
                                            </td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        id="switchCheckChecked" name="masuk_invoice[]"
                                                        {{ $item->masuk_invoice ? 'checked' : '' }} disabled>
                                                    <label class="form-check-label" for="switchCheckChecked">Masuk
                                                        Invoice</label>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
                            Keterangan Penambahan Item
                        </label>
                        <textarea readonly class="form-control">{{ $penambahan_item->keterangan }}</textarea>
                    </div>
                </div>
            </div>

            @if ($penambahan_item->status === 'menunggu approval')
                @can('job/penambahan-item/edit')
                    <div class="mt-4">
                        <form action="{{ route('job.penambahan-item.update', $penambahan_item->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="d-flex gap-4">
                                <button class="btn btn-danger" value="1" name="rejected">
                                    Tolak
                                </button>
                                <button name="approved" value="1" class="btn btn-primary">
                                    Setujui
                                </button>
                            </div>
                        </form>
                    </div>
                @endcan
            @endif
        </div>
    </div>
@endsection
