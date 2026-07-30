@extends('layouts.admin')

@section('title')
    Tambah Lembur Baru
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('hris.lembur.store') }}" method="post" enctype="multipart/form-data" id="formLembur">
                @csrf

                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="" class="form-label required">
                            Tanggal & Jam Mulai
                        </label>
                        {{-- UBAH TYPE menjadi datetime-local --}}
                        <input type="datetime-local" name="start_date" class="form-control start_date" id="start_date">
                    </div>
                    <div class="col-md-3">
                        <label for="" class="form-label required">
                            Tanggal & Jam Berakhir
                        </label>
                        {{-- UBAH TYPE menjadi datetime-local --}}
                        <input type="datetime-local" name="end_date" class="form-control end_date" id="end_date">
                    </div>
                    <div class="col-md-3">
                        <label for="" class="form-label ">
                            Lama Lembur
                        </label>
                        {{-- Hasil akan ditampilkan di sini --}}
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
                            Keterangan Lembur
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
        // Fungsi baru untuk menghitung selisih jam dan menit
        function hitungLamaLembur() {
            const startDateValue = $('#start_date').val();
            const endDateValue = $('#end_date').val();
            const $lamaLemburInput = $('.lama_cuti');

            if (startDateValue && endDateValue) {
                // Konversi string datetime-local menjadi objek Date
                const startDate = new Date(startDateValue);
                const endDate = new Date(endDateValue);

                // Pastikan Tanggal Berakhir TIDAK mendahului Tanggal Mulai
                if (endDate.getTime() < startDate.getTime()) {
                    $lamaLemburInput.val('Durasi tidak valid');
                    return;
                }

                // Hitung selisih waktu dalam milidetik
                const timeDiff = endDate.getTime() - startDate.getTime();

                // 1. Hitung total jam (pembulatan ke bawah)
                const totalHours = Math.floor(timeDiff / (1000 * 3600));

                // 2. Hitung sisa menit setelah jam dihitung
                const remainingMilliseconds = timeDiff % (1000 * 3600);
                const totalMinutes = Math.round(remainingMilliseconds / (1000 * 60));

                // Penyesuaian jika menit mencapai 60 (jarang terjadi dengan Math.round, tapi untuk keamanan)
                let finalHours = totalHours;
                let finalMinutes = totalMinutes;

                if (finalMinutes >= 60) {
                    finalHours += Math.floor(finalMinutes / 60);
                    finalMinutes = finalMinutes % 60;
                }

                // Format output
                let output = '';
                if (finalHours > 0) {
                    output += finalHours + ' Jam';
                }
                if (finalMinutes > 0) {
                    if (finalHours > 0) {
                        output += ' '; // Tambahkan spasi jika ada jam
                    }
                    output += finalMinutes + ' Menit';
                }

                // Tampilkan hasil, jika selisihnya 0 (atau kurang dari 1 menit), tampilkan "0 Jam"
                $lamaLemburInput.val(output || '0 Jam');

            } else {
                // Kosongkan jika salah satu atau kedua tanggal belum terisi
                $lamaLemburInput.val('');
            }
        }

        // Jalankan fungsi hitungLamaLembur setiap kali nilai pada input datetime-local berubah
        $('#start_date, #end_date').on('change', hitungLamaLembur);

        // Tambahkan kembali fungsi submit yang sudah ada
        $(".btn_submit").on("click", function() {
            $(".loading__global").show();
            $("#formLembur").submit();
        });
    </script>
@endpush
