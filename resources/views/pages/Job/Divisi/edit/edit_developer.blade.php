@extends('layouts.admin')

@section('title')
    Developer {{ $jobDivisi->kode }}
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <form action="{{ route('job.divisi-data-pendukung-developer.update') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $jobDeveloper->id }}">

                <!-- Card Informasi Developer (Standar UI/UX yang disamakan) -->
                <div class="card shadow-sm border border-light-subtle mb-4 rounded-3">
                    <div class="card-header bg-light bg-opacity-50 py-3 border-bottom border-light-subtle">
                        <h5 class="mb-0 fw-bold text-dark">Informasi Developer</h5>
                    </div>
                    <div class="card-body pt-3">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold required">Nama Developer</label>
                                <select name="developer"
                                    class="form-select select2 select_developer_edit @error('developer') is-invalid @enderror">
                                    <option value="">Pilih Developer</option>
                                    @foreach ($developer as $item)
                                        <option value="{{ $item->id }}"
                                            {{ old('developer', $jobDeveloper->developer_id) == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama_perumahan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('developer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold required">Nama PT</label>
                                <input type="text"
                                    class="form-control nama_pt_edit @error('nama_pt') is-invalid @enderror" readonly
                                    value="{{ old('nama_pt', $jobDeveloper->nama_pt) }}">
                                @error('nama_pt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-secondary small fw-bold required">Nama Agent</label>
                                <div class="nama_agent_edit">
                                    <select name="nama_agent"
                                        class="form-select select2 @error('nama_agent') is-invalid @enderror">
                                        <option value="">Pilih Agent</option>
                                        @php
                                            $selectedDeveloper = $developer->firstWhere(
                                                'id',
                                                $jobDeveloper->developer_id,
                                            );
                                        @endphp
                                        @if ($selectedDeveloper)
                                            @foreach ($selectedDeveloper->marketing as $marketing)
                                                <option value="{{ $marketing->id }}"
                                                    {{ old('nama_agent', $jobDeveloper->nama_agent ?? '') == $marketing->nama ? 'selected' : '' }}>
                                                    {{ $marketing->nama }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                @error('nama_agent')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('job.divisi.show', $jobDivisi->id) }}#tabs-data-pendukung"
                        class="btn btn-light px-4 border">Batal</a>
                    @if ($jobDivisi->status !== 'Batal Akad')
                        <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">Simpan Perubahan</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection

@push('addScript')
    <script>
        const list_developer = @json($developer);

        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });

        $(document).on('change', '.select_developer_edit', function() {
            const developerId = $(this).val();

            const item = list_developer.find(
                item => Number(item.id) === Number(developerId)
            );

            if (!item) return;

            $('.nama_pt_edit').val(item.nama_pt);

            $('.nama_agent_edit select').empty();

            $('.nama_agent_edit select').append(`
            <option value="">
                Pilih Agent
            </option>
        `);

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
