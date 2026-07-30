@extends('layouts.admin')

@section('title')
    Job Divisi {{ $jobDivisi->kode }}
@endsection

@push('page-title')
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);"
        aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Job </a></li>
            <li class="breadcrumb-item"><a href="{{ route('job.divisi.index') }}">Job Divisi</a></li>
            <li class="breadcrumb-item active" aria-current="page">
                {{ $jobDivisi->kode }}
            </li>
        </ol>
    </nav>
@endpush

@push('addStyle')
    <style>
        .add_more {
            background-color: #f1f1f1;
            border: 1px dashed #ccc;
            border-radius: 0.25rem;

            cursor: pointer;
        }

        .validasi_request {
            display: none;
        }

        .card-header-tabs {
            background: #f3f8ff;
        }

        .card-header-tabs .nav-link.active {
            font-weight: bold;
        }

        .bg-batal {
            color: #ffa4a4 !important;
        }
    </style>
@endpush

@section('content')
    @can('job/divisi/edit')
        @include('pages.Job.Divisi._header-button_detail')
    @endcan
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
                @if (auth()->user()->can('finance/in/list'))
                    <li class="nav-item" role="presentation">
                        <a href="#tabs-finance" class="nav-link" data-bs-toggle="tab" aria-selected="false" tabindex="-1"
                            role="tab">

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
                @endif
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
                <li class="nav-item" role="presentation">
                    <a href="#tabs-data-penyelesaian" class="nav-link" data-bs-toggle="tab" aria-selected="false"
                        tabindex="-1" role="tab"><!-- Download SVG icon from http://tabler.io/icons/icon/user -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-file-check me-2 icon-2">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2" />
                            <path d="M9 15l2 2l4 -4" />
                        </svg>

                        Dokumen Penyelesaian
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="#tabs-riwayat" class="nav-link" data-bs-toggle="tab" aria-selected="false" tabindex="-1"
                        role="tab"><!-- Download SVG icon from http://tabler.io/icons/icon/user -->

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-history-toggle me-2 icon-2">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M10 20.777a8.942 8.942 0 0 1 -2.48 -.969" />
                            <path d="M14 3.223a9.003 9.003 0 0 1 0 17.554" />
                            <path d="M4.579 17.093a8.961 8.961 0 0 1 -1.227 -2.592" />
                            <path d="M3.124 10.5c.16 -.95 .468 -1.85 .9 -2.675l.169 -.305" />
                            <path d="M6.907 4.579a8.954 8.954 0 0 1 3.093 -1.356" />
                            <path d="M12 8v4l3 3" />
                        </svg>

                        Riwayat Pengerjaan
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane active show" id="tabs-ringkasan" role="tabpanel">
                    @include('pages.Job.Divisi.detail_tabs._ringkasan')
                </div>
                <div class="tab-pane" id="tabs-form-order" role="tabpanel">
                    @if ($jobDivisi->perubahanHarga?->status === 'menunggu persetujuan')
                        <div class="alert  alert-warning alert-dismissible " role="alert">
                            <div class="alert-icon">
                                <!-- Download SVG icon from http://tabler.io/icons/icon/alert-triangle -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="icon alert-icon icon-2">
                                    <path d="M12 9v4"></path>
                                    <path
                                        d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z">
                                    </path>
                                    <path d="M12 16h.01"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="alert-heading">Menunggu Persetujuan</h4>
                                <div class="alert-description">
                                    Perubahan Harga ini sedang menunggu persetujuan
                                </div>
                            </div>
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                        </div>
                    @endif
                    <div class="d-flex justify-content-end">
                        @can('job/divisi/form-order/tambah-item')
                            @include('pages.Job.Divisi.detail_tabs._modal-add-item')
                        @endcan

                    </div>
                    <form action="{{ route('job.form-order-job-divisi.updateHarga') }}" method="post"
                        id="form_update__harga">
                        @csrf
                        <input type="number" value="{{ $jobDivisi->id }}" hidden name="job_id">
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

                        @if (!$approveFormOrder)
                            @can('job/divisi/form-order/edit-item')
                                <button class="btn btn-primary mt-3 ">
                                    Simpan Perubahan
                                </button>
                            @endcan
                        @endif

                    </form>

                    @if ($approveFormOrder)
                        <div class="alert alert-important alert-warning alert-dismissible mt-5" role="alert">
                            <div class="alert-icon">
                                <!-- Download SVG icon from http://tabler.io/icons/icon/alert-triangle -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="icon alert-icon icon-2">
                                    <path d="M12 9v4"></path>
                                    <path
                                        d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z">
                                    </path>
                                    <path d="M12 16h.01"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="alert-heading">Menunggu Persetujuan</h4>
                                <div class="alert-description">
                                    Perubahan Harga ini sedang menunggu persetujuan
                                </div>
                            </div>
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                        </div>

                        <div class="mt-5">
                            <form action="{{ route('job.approvePerubahanHarga', $jobDivisi->id) }}" id="formApproveHarga"
                                method="post">
                                @csrf
                                <div class="d-flex gap-4">
                                    <button name="status" value="tolak" class="btn btn-danger">
                                        Tolak
                                    </button>
                                    <button class="btn btn-primary" name="status" value="disetujui">
                                        Setujui
                                    </button>

                                </div>
                            </form>
                        </div>
                    @endif
                </div>

                @if (auth()->user()->can('finance/in/list'))
                    <div class="tab-pane" id="tabs-finance" role="tabpanel">
                        @include('pages.Job.Divisi.detail_tabs._finance_tabs')
                    </div>
                @endif
                @can('job/divisi/data-pendukung')
                    <div class="tab-pane " id="tabs-data-pendukung" role="tabpanel">
                        @include('pages.Job.Divisi._card-data-pendukung')
                    </div>
                @endcan
                <div class="tab-pane" id="tabs-riwayat" role="tabpanel">
                    @include('pages.Job.Divisi.detail_tabs._riwayat')
                </div>
                <div class="tab-pane" id="tabs-data-penyelesaian" role="tabpanel">
                    @include('pages.Job.Divisi.detail_tabs._form-dokument-penyelesaian')
                </div>
            </div>
        </div>
    </div>
@endsection


@push('addScript')
    <script>
        $(document).ready(function() {
            $("#form_update__harga").on("submit", function(e) {
                $(".loading__global").show();
            });
            $("#formApproveHarga").on("submit", function(e) {
                $(".loading__global").show();
            });
        });
        $(".select_proses").select2({
            theme: 'bootstrap-5',
            dropdownParent: $("#ModalAddItem"),
        });
        $(".select_objek").select2({
            theme: 'bootstrap-5',
            dropdownParent: $("#ModalAddItem"),
        });


        const formOrder = @json($jobFormOrder->flatten(1)->toArray());

        const removeItem = (idx) => {

            const item = formOrder.find(item => Number(item.id) === Number(idx));

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Anda akan membatalkan item ${item.nama}, ID ${item.id}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Tidak, Batalkan!',
            }).then((result) => {
                if (result.isConfirmed) {
                    $(`.row__item_${idx}`).remove();
                }
            });

        }
    </script>
@endpush
