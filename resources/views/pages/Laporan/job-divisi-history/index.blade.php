{{-- ```blade --}}
@extends('layouts.admin')

@section('title')
    History Job Divisi
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1">
                    <i class="bi bi-clock-history me-2 text-primary"></i>
                    History Job Divisi
                </h5>
                <small class="text-muted">
                    Daftar seluruh pekerjaan yang telah selesai.
                </small>
            </div>
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">

                    <thead>
                        <tr>
                            <th width="15%">ID Job</th>
                            <th>Paket Pekerjaan</th>
                            <th>Status</th>
                            <th>Yang Menjalankan Akad</th>
                            <th>Penanggung Jawab Berkas</th>
                            <th>Tanggal Akad</th>
                            <th>Tanggal selesai</th>
                            <th width="8%">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($items as $item)
                            <tr>

                                <td>
                                    <a href="{{ route('job.divisi.show', $item->id) }}" class="fw-bold text-decoration-none">
                                        {{ $item->kode }}
                                    </a>
                                </td>

                                <td>
                                    <span class="badge bg-blue-lt text-blue">
                                        {{ $item->jenisAkad->nama ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    @if ($item->status == 'Selesai')
                                        <span class="badge bg-success-lt text-success">
                                            <i class="bi bi-check-circle-fill me-1"></i>
                                            Selesai
                                        </span>
                                    @elseif($item->status == 'Batal Akad')
                                        <span class="badge bg-danger-lt text-danger">
                                            <i class="bi bi-x-circle me-1"></i>
                                            Batal
                                        </span>
                                    @else
                                        <span class="badge bg-warning-lt text-warning">
                                            {{ $item->status }}
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person-check text-primary me-2"></i>
                                        {{ $item->perwakilanAkad->name ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person-workspace text-success me-2"></i>
                                        {{ $item->penanggungJawab->name ?? '-' }}
                                    </div>
                                </td>

                                <td>
                                    <i class="bi bi-calendar-event text-muted me-1"></i>
                                    {{ $item->tanggal_akad?->format('d M Y') ?? '-' }}
                                </td>

                                <td>
                                    <i class="bi bi-calendar-check text-success me-1"></i>
                                    {{ $item->updated_at?->format('d M Y') }}
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('job.divisi.show', $item->id) }}" class="btn btn-icon btn-primary"
                                        data-bs-toggle="tooltip" title="Lihat Detail">

                                        <i class="bi bi-box-arrow-up-right"></i>

                                    </a>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Belum ada data.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>

            <div class="mt-4">
                {{ $items->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>
@endsection
{{-- ``` --}}
