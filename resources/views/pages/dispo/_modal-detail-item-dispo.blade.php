<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#detailDispo{{ $key }}">
    Detail
</button>

<!-- Modal -->
<div class="modal fade" id="detailDispo{{ $key }}" tabindex="-1"
    aria-labelledby="detailDispo{{ $key }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="">
                    <h1 class="modal-title fs-5" id="detailDispo{{ $key }}Label">
                        Dispo {{ $item->jobDivisiFormOrder->nama }}
                    </h1>
                    <div class="text-secondary">
                        {{ $item->jobDivisiFormOrder->jobDivisi->kode }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('berkas-bermasalah.dispo.update', $item->id) }}" method="post"
                    id="formDispo{{ $key }}">
                    @csrf
                    @method('PUT')
                    <label for="" class="form-label">
                        Keterangan
                    </label>
                    <textarea class="form-control">{{ $item->keterangan }}</textarea>
                </form>
            </div>
            @if (!$item->end_date)
                @can('berkas-bermasalah/dispo/buka')
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary btn__buka_dispo_{{ $key }}">Buka
                            Dispo</button>
                    </div>
                @endcan
            @endif
        </div>
    </div>
</div>

@push('addScript')
    <script>
        $(".btn__buka_dispo_{{ $key }}").on("click", function() {
            $(".loading__global").show();
            $("#formDispo{{ $key }}").submit();
        });
    </script>
@endpush
