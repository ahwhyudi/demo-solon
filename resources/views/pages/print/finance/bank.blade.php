<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>BANK</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .kop {
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;

        }

        th,
        td {
            padding: 5px;
        }
    </style>

</head>

<body>
    <main>
        <div class="kop">
            <h2 style="margin: 0 0 5px 0; font-size: 18px; letter-spacing: 1px;">
                {{ cache('setting_perusahaan')->nama_perusahaan }}
            </h2>
            <div class="" style="font-size: 12px;">
                <span>Notaris & PPAT Kabupaten Tangerang</span><br>
                Alamat : Foresta Business Loft THP 1 Unit 7, BSD City, Tangerang <br>
                Telp : 0877 7523 2059, 08778 8030 3539 <br>

            </div>
        </div>
        <div class="content-top">
            <table border="1" style="width: 100%; border: 2px solid #000">
                <tr>
                    <td colspan="2">
                        <h5 style="margin-top: 8px; margin-bottom: 8px; text-align: center;">
                            TAGIHAN BIAYA NOTARIS ~ {{ $bank }}
                        </h5>
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 12px; text-align: right">
                        Nama Debitur :
                    </td>
                    <td style="font-size: 12px; text-align: center">
                        <strong>
                            {{ $namaDebitur }}
                        </strong>
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 12px; text-align: right">
                        Fasilitas KPR :
                    </td>
                    <td style="font-size: 12px; text-align: center">
                        <strong>AJB LANJUTAN - KOTA BSD</strong>
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 12px; text-align: right">
                        Tanggal Akad :
                    </td>
                    <td style="font-size: 12px; text-align: center">
                        <strong>{{ $jobDivisi->tanggal_akad?->format('d-M-Y') }}</strong>
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 12px; text-align: right">
                        Nilai AJB :
                    </td>
                    <td style="font-size: 12px; text-align: center">
                        <strong>{{ number_format($nilaiTransaksi) }} (Rp)</strong>
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 12px; text-align: right">
                        Nilai Plafond :
                    </td>
                    <td style="font-size: 12px; text-align: center">
                        <strong>{{ number_format($nilaiPlafond) }} (Rp)</strong>
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 12px; text-align: right">
                        Nilai APHT :
                    </td>
                    <td style="font-size: 12px; text-align: center">
                        <strong>{{ number_format($nilaiHt) }} (Rp)</strong>
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 12px; text-align: right">
                        Jaminan Objek :
                    </td>
                    <td style="font-size: 12px; text-align: center">
                        {{ $jobDivisi->objek->first()->alamat ?? 'Objek Belum di Input' }}
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 12px; text-align: right">
                        No Referensi :
                    </td>
                    <td style="font-size: 12px; text-align: center">
                        {{ $invoice->kode }}
                    </td>
                </tr>
            </table>
        </div>

        <div class="content-top">
            <table border="1" style="width: 100%; margin-top: 10px">
                <tr style="background-color: #fde9d9">
                    <td colspan="4">
                        <h5 style="margin-top: 8px; margin-bottom: 8px; text-align: center;">
                            A. BIAYA AKTA
                        </h5>
                    </td>
                </tr>
                @foreach ($biayaAkta as $item)
                    <tr>
                        <td colspan="3" style="font-size: 12px; text-align: left; width: 15rem;" colspan="2">
                            {{ $item->nama }}
                        </td>
                        <td style="font-size: 12px; text-align: right">
                            {{ number_format($item->harga_jual) }}
                    </tr>
                @endforeach

                <tr>
                    <td colspan="3" style="font-size: 12px; text-align: right" colspan="2">
                        TOTAL AKTA
                    </td>
                    <td style="font-size: 12px; text-align: right">
                        {{ number_format($biayaAkta->sum('harga_jual')) }}
                    </td>
                </tr>

                @if (!empty($biayaPengurusan))
                    <tr style="background-color: #fde9d9">
                        <td colspan="4">
                            <h5 style="margin-top: 8px; margin-bottom: 8px; text-align: center;">
                                B. BIAYA PENGURUSAN
                            </h5>
                        </td>
                    </tr>

                    @foreach ($biayaPengurusan->where('kategori', 'operasional') as $item)
                        <tr>
                            <td style="font-size: 12px; text-align: left; width: 15rem;" colspan="3">
                                {{ $item->nama }}
                            </td>
                            <td style="font-size: 12px; text-align: right">
                                {{ number_format($item->harga_jual) }}
                            </td>
                        </tr>
                    @endforeach

                    @foreach ($biayaPengurusan->where('kategori', 'pnbp_voucher')->values() as $index => $item)
                        <tr>
                            @if ($index === 0)
                                <td rowspan="{{ count($biayaPengurusan->where('kategori', 'pnbp_voucher')) }}"
                                    style="font-size: 12px; text-align: left;">
                                    BIAYA PNBP
                                </td>
                            @endif
                            <td style="font-size: 12px; text-align: left; " colspan="2">
                                {{ $item->nama }}
                            </td>
                            <td style="font-size: 12px; text-align: right">
                                {{ number_format($item->harga_jual) }}
                        </tr>
                    @endforeach

                    <tr>
                        <td style="font-size: 12px; text-align: right" colspan="3">
                            TOTAL BIAYA PENGURUSAN
                        </td>
                        <td style="font-size: 12px; text-align: right">
                            {{ number_format($biayaPengurusan->sum('harga_jual')) }}
                        </td>
                    </tr>
                @endif

                @if (!empty($biayaPajak))
                    <tr style="background-color: #fde9d9">
                        <td colspan="4">
                            <h5 style="margin-top: 8px; margin-bottom: 8px; text-align: center;">
                                C. BIAYA PAJAK
                            </h5>
                        </td>
                    </tr>

                    @foreach ($biayaPajak as $item)
                        <tr>
                            <td style="font-size: 12px; text-align: left; width: 15rem;" colspan="3">
                                {{ $item->nama }}
                            </td>
                            <td style="font-size: 12px; text-align: right">
                                {{ number_format($item->harga_jual) }}
                            </td>
                        </tr>
                    @endforeach

                    <tr>
                        <td style="font-size: 12px; text-align: right" colspan="3">
                            TOTAL BIAYA PAJAK
                        </td>
                        <td style="font-size: 12px; text-align: right">
                            {{ number_format($biayaPajak->sum('harga_jual')) }}
                        </td>
                    </tr>
                @endif

                <tr>
                    <td style="font-size: 12px; text-align: right" colspan="3">
                        TOTAL BIAYA NOTARIS
                    </td>
                    <td style="font-size: 12px; text-align: right">
                        {{ number_format($totalBiayaNotaris) }}
                    </td>
                </tr>

            </table>
        </div>


        <div class="" style="text-align: center; margin-top: 10px; font-size: 12px">
            Pembayaran harap ditransfer melalui Rekening BCA <br>
            a/n DEBRA TC SCHRAM, SH <br>
            a/c 603-058-0701
        </div>
    </main>
</body>

</html>
