@extends('layouts.admin')

@section('title')
    Setting Step Ops
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap gap-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahStepOps">
                Tambah Step
            </button>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Urutan</th>
                            <th>Nama Step</th>
                            <th>Data Objek</th>
                            <th>Penugasan Staff</th>
                            <th>Konfirmasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            <tr>
                                <td>{{ $item->urutan }}</td>
                                <td>{{ $item->nama_step }}</td>
                                <td class="text-white">
                                    @if ($item->data_objek)
                                        <span class="badge text-bg-success">Ya</span>
                                    @else
                                        <span class="badge text-bg-secondary">Tidak</span>
                                    @endif
                                </td>
                                <td class="text-white">
                                    @if ($item->penugasan_staff)
                                        <span class="badge text-bg-success">Ya</span>
                                    @else
                                        <span class="badge text-bg-secondary">Tidak</span>
                                    @endif
                                </td>
                                <td class="text-white">
                                    @if ($item->konfirmasi)
                                        <span class="badge text-bg-success">Ya</span>
                                    @else
                                        <span class="badge text-bg-secondary">Tidak</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('setting.step-ops.show', $item->id) }}"
                                            class="btn btn-info btn-sm">Detail</a>
                                        <a href="{{ route('setting.step-ops.edit', $item->id) }}"
                                            class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('setting.step-ops.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm confirm_delete"
                                                data-message="{{ $item->nama_step }}">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Data step operasional belum ada</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="modalTambahStepOps" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('setting.step-ops.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Setting Step Ops</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @include('pages.setting.step-ops._sortable-form', [
                            'item' => null,
                            'items' => $items,
                            'submitLabel' => 'Simpan',
                            'formKey' => 'create',
                            'formType' => 'create-step-ops',
                            'isModal' => true,
                        ])
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('addScript')
    @if (($errors->any() && old('_form_type') === 'create-step-ops') || request('open-modal') === 'create')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modalElement = document.getElementById('modalTambahStepOps');
                if (modalElement) {
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                }
            });
        </script>
    @endif
@endpush
