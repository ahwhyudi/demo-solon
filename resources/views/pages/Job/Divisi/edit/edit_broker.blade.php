@extends('layouts.admin')

@section('title', 'Edit Broker ' . $jobDivisi->kode)

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <form action="{{ route('job.divisi-data-pendukung-broker.update') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $jobBroker->id }}">

                <div class="card shadow-sm border border-light-subtle mb-4 rounded-3">
                    <div class="card-header bg-light bg-opacity-50 py-3 border-bottom border-light-subtle">
                        <h5 class="mb-0 fw-bold text-dark">Informasi Broker</h5>
                    </div>
                    
                    <div class="card-body pt-3">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold required">NAMA PERUMAHAN</label>
                                <select name="broker" class="form-select select2 select_broker_edit">
                                    <option value="">Pilih Perumahan</option>
                                    @foreach ($broker as $item)
                                        <option value="{{ $item->id }}"
                                            {{ old('broker', $jobBroker->broker_id) == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama_perumahan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold required">NAMA PT</label>
                                <input type="text" class="form-control nama_pt_edit" name="nama_pt" readonly
                                    value="{{ old('nama_pt', $jobBroker->nama_pt) }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold required">NAMA PIMPINAN</label>
                                <input type="text" class="form-control nama_pimpinan_edit" name="nama_pimpinan" readonly
                                    value="{{ old('nama_pimpinan', $jobBroker->nama_pimpinan) }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold required">NAMA AGENT</label>
                                <div class="nama_agent_edit">
                                    <select name="nama_agent" class="form-select select2">
                                        <option value="">Pilih Agent</option>
                                        @php
                                            $selectedBroker = $broker->firstWhere('id', $jobBroker->broker_id);
                                        @endphp
                                        @if ($selectedBroker)
                                            @foreach ($selectedBroker->marketing as $marketing)
                                                <option value="{{ $marketing->id }}"
                                                    {{ old('nama_agent', $jobBroker->marketing_id) == $marketing->id ? 'selected' : '' }}>
                                                    {{ $marketing->nama }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('job.divisi.show', $jobDivisi->id) }}#tabs-data-pendukung"
                        class="btn btn-light px-4 border">
                        Batal
                    </a>
                    @if ($jobDivisi->status !== 'Batal Akad')
                        <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">
                            Simpan Perubahan
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @push('addScript')
        <script>
            const list_broker = @json($broker);

            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });

            $(document).on('change', '.select_broker_edit', function() {
                const brokerId = $(this).val();
                const item = list_broker.find(item => Number(item.id) === Number(brokerId));

                if (!item) return;

                $('.nama_pt_edit').val(item.nama_pt);
                $('.nama_pimpinan_edit').val(item.nama_pimpinan);

                $('.nama_agent_edit select').empty();
                $('.nama_agent_edit select').append(`<option value="">Pilih Agent</option>`);

                item.marketing.forEach((row) => {
                    $('.nama_agent_edit select').append(`
                        <option value="${row.id}">
                            ${row.nama}
                        </option>
                    `);
                });
            });
        </script>
    @endpush
@endsection