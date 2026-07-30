@extends('layouts.admin')

@section('title')
    Pembatalan Item
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('job.pembatalan-items.store') }}" method="post">
                @csrf
                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="" class="form-label required">
                            Job parent
                        </label>
                        <input type="text" class="form-control" hidden name="job_divisi_id" value="{{ $jobDivisi->id }}">
                        <input type="text" class="form-control" disabled value="{{ $jobDivisi->kode }}">
                    </div>

                </div>

                <div class="row g-3">
                    <div class="col-md-8">
                        <div class="table-responsive mt-4">
                            <table class="table-bordered table">
                                <thead>
                                    <tr>
                                        <th width="10">
                                            <input class="form-check-input" type="checkbox" value="1" id="checkAll">
                                        </th>
                                        <th>
                                            ID
                                        </th>
                                        <th>Proses</th>
                                        <th>Kategori</th>
                                        <th>

                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($formOrder as $item)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input checkItem" type="checkbox"
                                                        value="{{ $item->id }}" name="item[{{ $item->id }}]"
                                                        id="checkDefault{{ $item->id }}">
                                                </div>
                                            </td>
                                            <td>
                                                {{ $item->id }}
                                            </td>
                                            <td>
                                                {{ $item->nama }}
                                            </td>
                                            <td>
                                                {{ $item->kategori }}
                                            </td>
                                            <td>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <label for="" class="form-label required">
                            keterangan
                        </label>
                        <textarea name="keterangan" class="form-control" placeholder="Masukkan Keterangan"></textarea>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="button" class="btn btn-primary btn__simpan">Simpan</button>
                </div>

            </form>
        </div>
    </div>
@endsection

@push('addScript')
    <script>
        $(document).ready(function() {
            $(".btn__simpan").on("click", function() {
                $(".loading__global").show();
                $(this).closest("form").submit();
            })
        })

        $("#checkAll").on('change', function() {
            const value = $(this).is(":checked");
            console.log('value :>> ', value);
            if ($(this).is(":checked")) {
                $(".checkItem").prop("checked", true);
            } else {
                $(".checkItem").prop("checked", false);
            }
        });
    </script>
@endpush
