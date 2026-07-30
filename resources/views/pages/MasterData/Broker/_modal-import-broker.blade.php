<!-- Button trigger modal -->
<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
    Import Data
</button>

<!-- Modal -->
<form action="{{ route('import.master-data.broker.store') }}" method="post" enctype="multipart/form-data"
    id="formImportBank">
    @csrf

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Import Data Broker</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="" class="form-label">
                            File Excel
                        </label>
                        <input type="file" name="data" class="form-control " required
                            accept=".xlsx, .xls, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                        <a href="" class="btn btn-outline-info mt-3" download>Download Contoh Format Excel</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Upload Data</button>
                </div>
            </div>
        </div>
    </div>
</form>


@push('addScript')
    <script>
        $("#formImportBank").on("submit", function() {
            $(".loading__global").show();
        });
    </script>
@endpush
