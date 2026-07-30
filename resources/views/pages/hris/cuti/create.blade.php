@extends('layouts.admin')

@section('title')
    Tambah Cuti Baru
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('hris.cuti.store') }}" method="post" enctype="multipart/form-data" id="formCuti">
                @csrf

                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="" class="form-label required">
                            Tanggal Mulai
                        </label>
                        {{-- Tambahkan ID untuk memudahkan akses JS --}}
                        <input type="date" name="start_date" class="form-control start_date" id="start_date">
                    </div>
                    <div class="col-md-3">
                        <label for="" class="form-label required">
                            Tanggal Berakhir
                        </label>
                        {{-- Tambahkan ID untuk memudahkan akses JS --}}
                        <input type="date" name="end_date" class="form-control end_date" id="end_date">
                    </div>
                    <div class="col-md-3">
                        <label for="" class="form-label ">
                            Lama Cuti
                        </label>
                        {{-- Tetap gunakan class lama_cuti untuk menampilkan hasil --}}
                        <input type="text" class="form-control lama_cuti" disabled>
                    </div>
                    <div class="col-md-3">
                        <label for="" class="form-label">
                            Data Pendukung
                        </label>
                        <input type="file" class="form-control" accept="image/*" name="file">
                    </div>
                    <div class="col-md-6">
                        <label for="" class="form-label required">
                            Keterangan Cuti
                        </label>
                        <textarea name="keterangan" class="form-control"></textarea>
                    </div>
                </div>

                <div class="mt-5">
                    <button class="btn btn-primary btn_submit" type="button">
                        Simpan Data
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection

@push('addScript')
    <script>
        // Fungsi untuk menghitung selisih hari antara dua tanggal
        function hitungLamaCuti() {
            // Ambil nilai tanggal dari input
            const startDateValue = $('#start_date').val();
            const endDateValue = $('#end_date').val();

            // Pastikan kedua tanggal sudah terisi
            if (startDateValue && endDateValue) {
                // Konversi string tanggal menjadi objek Date
                const startDate = new Date(startDateValue);
                const endDate = new Date(endDateValue);

                // Hitung selisih waktu dalam milidetik
                // Tambahkan 1 hari (24 jam) agar tanggal berakhir ikut terhitung
                // Contoh: Cuti dari 1 Jan (Mulai) sampai 1 Jan (Berakhir) = 1 hari
                const timeDiff = endDate.getTime() - startDate.getTime();

                // Hitung selisih hari
                const dayDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;

                // Cek apakah tanggal berakhir lebih awal dari tanggal mulai
                if (dayDiff > 0) {
                    // Tampilkan hasilnya di input .lama_cuti
                    $('.lama_cuti').val(dayDiff + ' Hari');
                } else {
                    // Jika tanggal berakhir lebih awal, kosongkan dan beri peringatan (opsional)
                    $('.lama_cuti').val('');
                    // Anda bisa menambahkan validasi di sini, misalnya:
                    // alert('Tanggal berakhir harus setelah atau sama dengan tanggal mulai.');
                }
            } else {
                // Kosongkan jika salah satu atau kedua tanggal belum terisi
                $('.lama_cuti').val('');
            }
        }

        // Jalankan fungsi hitungLamaCuti setiap kali nilai pada Tanggal Mulai atau Tanggal Berakhir berubah
        $('#start_date, #end_date').on('change', hitungLamaCuti);

        // Tambahkan kembali fungsi submit yang sudah ada
        $(".btn_submit").on("click", function() {
            $(".loading__global").show();
            $("#formCuti").submit();
        });
    </script>
@endpush
