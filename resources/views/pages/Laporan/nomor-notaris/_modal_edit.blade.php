<button type="button" class="btn btn-outline-secondary btn-edit" data-id="{{ $item->id }}"
    data-proses="{{ $item->form_order_id }}" data-notaris="{{ $item->notaris_pengambil }}"
    data-debitur="{{ $item->nama_debitur_notaris_pengambil }}" data-objek="{{ $item->objek_notaris_pengambil }}"
    data-tanggal="{{ \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d') }}">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square"
        viewBox="0 0 16 16">
        <path
            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
        <path fill-rule="evenodd"
            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
    </svg>
</button>

<div class="modal fade" id="modalEditNomor" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('laporan.nomor-notaris.update') }}" method="POST">
                @csrf

                <input type="hidden" name="id" id="edit_id">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Nomor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label required">
                            Nama Proses
                        </label>

                        <select name="group_proses" id="edit_group_proses"
                            class="form-select select2_group_proses_edit">

                            @foreach ($masterPekerjaan as $pekerjaan)
                                @if (in_array($pekerjaan->kategori, ['surat-keluar', 'waarmerking', 'legalisasi', 'notaris', 'ppat']))
                                    <option value="{{ $pekerjaan->id }}">
                                        {{ $pekerjaan->nama }}
                                    </option>
                                @endif
                            @endforeach

                        </select>

                    </div>

                    <div class="mb-3">
                        <label class="form-label required">
                            Notaris Pengambil Nomor
                        </label>

                        <input type="text" class="form-control" name="notaris_pengambil" id="edit_notaris">
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">
                            Nama Debitur
                        </label>

                        <input type="text" class="form-control" name="nama_debitur_notaris_pengambil"
                            id="edit_debitur">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Objek
                        </label>

                        <input type="text" class="form-control" name="objek_notaris_pengambil" id="edit_objek">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Tanggal
                        </label>

                        <input type="date" class="form-control" name="tanggal_nomor" id="edit_tanggal">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Update
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@push('addScript')
    <script>
        $(document).ready(function() {
            $('.select2_group_proses_edit').select2({
                width: '100%',
                dropdownParent: $('#modalEditNomor'),
                theme: 'bootstrap-5'
            });

            $(document).on('click', '.btn-edit', function() {

                $('#edit_id').val($(this).data('id'));
                $('#edit_notaris').val($(this).data('notaris'));
                $('#edit_debitur').val($(this).data('debitur'));
                $('#edit_objek').val($(this).data('objek'));
                $('#edit_tanggal').val($(this).data('tanggal'));

                $('#edit_group_proses')
                    .val($(this).data('proses'))
                    .trigger('change');

                $('#modalEditNomor').modal('show');
            });

        });
    </script>
@endpush
