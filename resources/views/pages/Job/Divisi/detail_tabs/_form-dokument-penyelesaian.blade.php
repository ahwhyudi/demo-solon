@if (count($jobDivisi->fileJob) < 10 && auth()->user()->can('job/divisi/dokumen-penyelesaian/create'))
    <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Tambah Dokumen
    </button>


    <form action="{{ route('uploadFile') }}" method="post" id="formUploadFile" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="job_divisi_id" value="{{ $jobDivisi->id }}">
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Form Tambah Dokument</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="" class="form-label required">
                            Nama Dokument
                        </label>
                        <select name="nama" id="" class="form-select">
                            <option value="foto akad" selected>Foto Akad</option>
                            <option value="covernote">Covernote</option>
                            <option value="tanda terima berkas">Tanda terima berkas</option>
                        </select>
                        <div class="mt-4">
                            <label for="" class="form-label required">
                                File
                            </label>
                            <input type="file" class="form-control" name="file">
                            <small>Maksimal 5MB</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan Dokument</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endif


<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>
                    No
                </th>
                <th>
                    Download
                </th>
                <th>
                    Nama Dokumen
                </th>
                <th>
                    Tanggal Upload
                </th>
                <th>
                    Dikirim Oleh
                </th>
                <th>
                    Aksi
                </th>

            </tr>
        </thead>
        <tbody>
            @foreach ($jobDivisi->fileJob as $index => $item)
                <tr>
                    <td>
                        {{ $index + 1 }}
                    </td>
                    <td>
                        <a href="/storage/{{ $item->path }}" download>
                            <i class="bi bi-cloud-arrow-down"></i> Download
                        </a>
                    </td>
                    <td>
                        {{ $item->nama }}
                    </td>
                    <td>
                        {{ $item->created_at?->format('d-m-Y') }}
                    </td>
                    <td>
                        {{ $item->user->name }}
                    </td>
                    <td>
                        @can('job/divisi/dokumen-penyelesaian/delete')
                            <form action="{{ route('file.destroy', $item->id) }}" method="post">
                                @csrf
                                <div class="btn btn-danger confirm_delete"
                                    data-message="Dokumen {{ $item->nama }} Nomor {{ $index + 1 }}">
                                    <i class="bi bi-trash"></i>
                                </div>
                            </form>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('addScript')
    <script>
        $("#formUploadFile").on('submit', function(e) {

            const fileInput = $(this).find('input[name="file"]')[0];
            const file = fileInput.files[0];

            if (!file) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Silahkan pilih file terlebih dahulu!',
                })
                return false;
            }

            // 3. Validasi Ukuran (7MB = 7 * 1024 * 1024 bytes)
            const maxSize = 7 * 1024 * 1024; // 7.340.032 Bytes

            if (file.size > maxSize) {
                e.preventDefault(); // Menghentikan submit form
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'File terlalu besar! Maksimal ukuran adalah 5MB.',
                })

                // Opsional: Reset input file agar user pilih ulang
                fileInput.value = "";
                return false;
            }

            $(".loading__global").show();
        });
    </script>
@endpush
