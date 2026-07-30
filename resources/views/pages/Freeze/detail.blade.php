@extends('layouts.admin')

@section('title')
    Detail Freeze {{ $freeze->jobDivisi->kode }}
@endsection

@push('addStyle')
    <style>
        .card-header-tabs {
            background: #f3f8ff;
        }

        .card-header-tabs .nav-link.active {
            font-weight: bold;
        }
    </style>
@endpush

@section('content')
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a href="#tabs-ringkasan" class="nav-link active" data-bs-toggle="tab" aria-selected="true"
                        role="tab"><!-- Download SVG icon from http://tabler.io/icons/icon/home -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon me-2 icon-2">
                            <path d="M5 12l-2 0l9 -9l9 9l-2 0"></path>
                            <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"></path>
                            <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"></path>
                        </svg>Ringkasan</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="#tabs-form-order" class="nav-link" data-bs-toggle="tab" aria-selected="false" tabindex="-1"
                        role="tab"><!-- Download SVG icon from http://tabler.io/icons/icon/user -->

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

                        Form Order
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="#tabs-finance" class="nav-link" data-bs-toggle="tab" aria-selected="false" tabindex="-1"
                        role="tab"><!-- Download SVG icon from http://tabler.io/icons/icon/user -->

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-coin me-2 icon-2">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                            <path d="M14.8 9a2 2 0 0 0 -1.8 -1h-2a2 2 0 1 0 0 4h2a2 2 0 1 1 0 4h-2a2 2 0 0 1 -1.8 -1" />
                            <path d="M12 7v10" />
                        </svg>

                        Finance
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
                    @include('pages.Freeze.detail_tabs._ringkasan', [
                        'jobDivisi' => $freeze->jobDivisi,
                    ])
                </div>
                <div class="tab-pane" id="tabs-form-order" role="tabpanel">
                    @foreach ($jobFormOrder as $kategori => $item)
                        @if ($kategori === 'pajak')
                            @include('pages.Job.Divisi.detail_tabs._form-order-pajak', [
                                'dataFormOrder' => $item,
                            ])
                        @else
                            @include('pages.Job.Divisi.detail_tabs._form-order', [
                                'dataFormOrder' => $item,
                                'nama_kategori' => $kategori,
                            ])
                        @endif
                    @endforeach
                </div>

                <div class="tab-pane" id="tabs-finance" role="tabpanel">
                    @include('pages.Freeze.detail_tabs._finance_tabs', [
                        'jobDivisi' => $freeze->jobDivisi,
                    ])
                </div>
                <div class="tab-pane " id="tabs-data-pendukung" role="tabpanel">
                    @include('pages.Freeze._card_data_pendukung')
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <div class="">
                <form action="{{ route('berkas-bermasalah.freeze.update', $freeze->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <input type="text" name="status" value="1" hidden class="status">
                    <label for="" class="form-label">
                        Alasan Freeze
                    </label>
                    <textarea readonly class="form-control">{{ $freeze->keterangan }}</textarea>
                    @if ($freeze->status === 'menunggu persetujuan')
                        @can('berkas-bermasalah/freeze/tolak')
                            <div class="btn btn-outline-danger mt-4 me-3 btn__tolak">
                                Tolak
                            </div>
                        @endcan
                        @can('berkas-bermasalah/freeze/setuju')
                            <div class="btn btn-primary mt-4 btn__setujui">
                                Setujui
                            </div>
                        @endcan
                    @endif
                </form>

                @if ($freeze->status === 'Disetujui')
                    @can('berkas-bermasalah/freeze/buka')
                        <div class="btn btn-primary mt-4" data-bs-toggle="modal" data-bs-target="#modalBukaFreeze">
                            Buka Freeze
                        </div>
                    @endcan

                    {{-- modal buka freeze --}}
                    <div class="modal fade" id="modalBukaFreeze" tabindex="-1" aria-labelledby="modalBukaFreezeLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="modalBukaFreezeLabel">Buka Freeze</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('berkas-bermasalah.freeze.update', $freeze->id) }}"
                                        method="post" id="formBukaFreeze">
                                        @csrf
                                        @method('PUT')
                                        <input type="number" name="job_id" hidden value="{{ $jobDivisi->id }}">
                                        <input type="text" name="status" hidden value="Buka Freeze">
                                        <div class="mb-3">
                                            <label for="" class="form-label required">
                                                Tambah SLA (hari)
                                            </label>
                                            <input type="number" class="form-control" name="tambah_sla">
                                        </div>

                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Batal</button>
                                    <button type="button" class="btn btn-primary btn__buka_freeze">Buka Freeze</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- modal buka freeze end --}}
                @endif
            </div>
        </div>
    </div>
@endsection

@push('addScript')
    <script>
        $(".btn__setujui").on("click", function() {
            $(".loading__global").show();
            var form = $(this).closest("form");
            form.find(".status").val(1);
            form.submit();
        });

        $(".btn__tolak").on("click", function() {
            $(".loading__global").show();
            var form = $(this).closest("form");
            form.find(".status").val(0);
            form.submit();
        });
        $(".btn__buka_freeze").on("click", function() {
            $(".loading__global").show();

            $("#formBukaFreeze").submit();
        });
    </script>
@endpush
