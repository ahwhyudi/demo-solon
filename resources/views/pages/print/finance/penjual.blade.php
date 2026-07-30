<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print {{ $kategori }}</title>
    <style>
        /* Mengatur box-sizing agar padding tidak merusak layout */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* --- TAMBAHAN CSS WATERMARK DOMPDF --- */
        .watermark-container {
            position: fixed;
            top: 35%;
            left: 10%;
            width: 80%;
            text-align: center;
            z-index: -1000;
            transform: rotate(-35deg);
            opacity: 0.12; /* Transparansi 12% agar teks tabel tetap terbaca jelas */
        }

        .watermark-text {
            font-size: 55px;
            font-weight: bold;
            color: #000000;
            border: 4px solid #000000;
            padding: 10px 30px;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 4px;
        }
        /* --- END CSS WATERMARK --- */

        main {
            border: 2px solid #000;
            padding: 8px;
            margin: 10px;
        }

        .kop {
            text-align: center;
            margin-bottom: 15px;
        }

        /* Solusi DomPDF 1: Pengganti flexbox header menggunakan table layout */
        .header-table {
            width: 100%;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .header-table td {
            padding: 8px 5px;
            vertical-align: top;
        }

        p,
        td {
            font-size: 14px;
            line-height: 1.4;
        }

        .header-table p {
            margin: 3px 0;
        }

        /* Solusi DomPDF 2: Menghilangkan celah putus-putus antar kolom table */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        .list_tagihan {
            margin-bottom: 20px;
        }

        .list_tagihan table td {
            padding: 4px 5px;
        }

        /* Kunci Sukses Border di DomPDF: Ditembak langsung ke elemen TD */
        .border-tb {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
        }

        .border-l {
            border-left: 2px solid #000;
        }

        footer {
            border-top: 2px solid #000;
            padding-top: 10px;
        }

        .footer-table td {
            padding: 4px 5px;
            vertical-align: top;
        }
    </style>
</head>

<body>
    <!-- ELEMEN WATERMARK DITARUH LANGSUNG SETELAH BODY DAN SEBELUM MAIN -->
    @if (isset($watermark_text))
        <div class="watermark-container">
            <div class="watermark-text">{{ $watermark_text }}</div>
        </div>
    @endif

    <main>
        <div class="kop">
            <h2 style="margin: 0 0 5px 0; font-size: 18px; letter-spacing: 1px;">
                {{ cache('setting_perusahaan')->nama_perusahaan }}
            </h2>
            <div class="" style="font-size: 12px;">
                <span>Notaris & PPAT Kabupaten Tangerang</span><br>
                Alamat : {{ cache('setting_perusahaan')->alamat }} <br>
                HP : {{ cache('setting_perusahaan')->telepon }} <br>
                Email : {{ cache('setting_perusahaan')->email }} <br>
                ~Kwitansi dengan {{ $kategori === 'pembeli' ? 'PENJUAL' : 'PEMBELI' }}
                {{ $kategori === 'pembeli' ? $nama_penjual : $nama_pembeli }}~
            </div>
        </div>

        <table class="header-table" style="font-size: 10px;">
            <tr>
                <td style="width: 55%; font-size: 10px;">
                    <p style="font-size: 12px;"><strong>Nama :</strong>
                        {{ $kategori === 'pembeli' ? $nama_pembeli : $nama_penjual }}</p>
                    <p style="font-size: 12px;"><strong>Jaminan :</strong> {{ $alamat_objek }}</p>
                </td>
                <td style="width: 45%; text-align: right;">
                    <p style="font-size: 12px;"><strong>No.Faktur :</strong> {{ $no_faktur }}</p>
                    <p style="font-size: 12px;"><strong>Tanggal :</strong> {{ $tanggal }}</p>
                </td>
            </tr>
        </table>

        <div class="list_tagihan" style="font-size: 10px;">
            <p style="text-decoration: underline; font-size: 10px; font-weight: bold; margin-bottom: 8px;">
                TAGIHAN BIAYA NOTARIS :
            </p>

            <table>
                @foreach ($items as $index => $item)
                    <tr style="font-size: 10px;">
                        <td style="width: 5%;"></td>
                        <td style="width: 65%; font-size: 10px;">
                            {{ $index + 1 }}. {{ $item->formOrder->nama }}
                        </td>
                        <td style="width: 5%; font-size: 10px;">Rp.</td>
                        <td style="width: 25%; text-align: right; padding-right: 10px; font-size: 10px;">
                            {{ number_format($item->formOrder->harga_jual, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach

                <tr style="font-size: 10px;">
                    <td colspan="2"
                        style="text-align: right; font-size: 10px; font-weight: bold; padding-right: 15px; padding-top: 10px;">
                        TOTAL &nbsp;&nbsp;
                    </td>
                    <td style="border-top: 1px solid #000; padding-top: 10px; font-size: 10px;">Rp.</td>
                    <td
                        style="border-top: 1px solid #000; text-align: right; padding-right: 10px; padding-top: 10px; font-size: 10px;">
                        <strong>{{ number_format($totalKeseluruhan, 0, ',', '.') }}</strong>
                    </td>
                </tr>
            </table>
        </div>

        <footer>
            <p style="margin: 0 0 10px 0; font-size: 12px;">
                Tangerang, {{ $tanggal }}
            </p>

            <table style="width: 100%;">
                <tr>
                    <td rowspan="{{ count($pembayaran) + 2 }}" style="width: 45%"></td>
                    <td style="font-weight: bold; font-style: italic; width: 55%; font-size: 12px;">
                        PERHITUNGAN :
                    </td>
                </tr>

                <tr>
                    <td class="border-tb border-l" style="padding-left: 8px; font-size: 12px;">
                        - Tagihan Notaris
                    </td>
                    <td class="border-tb" style="font-size: 12px;">Rp.</td>
                    <td class="border-tb" style="text-align: right; font-size: 12px;">
                        <strong>{{ number_format($totalKeseluruhan, 0, ',', '.') }}</strong>
                    </td>
                </tr>

                @foreach ($pembayaran as $item)
                    <tr>
                        <td style="padding-left: 8px; font-size: 12px;">
                            - Transfer tgl {{ $item->tanggal->format('d-M-y') }} {{ $item->metode_pembayaran }}
                        </td>
                        <td style="font-size: 12px;">Rp.</td>
                        <td style="text-align: right; font-size: 12px;">
                            <strong>{{ number_format($item->total, 0, ',', '.') }}</strong>
                        </td>
                    </tr>
                @endforeach

                <tr>
                    <td>
                        <p style="font-size: 12px;">{{ cache('setting_perusahaan')->nama_perusahaan }}</p>
                    </td>
                    <td style="font-weight: bold; padding-left: 8px; font-size: 12px;">
                        Kurang/Lebih Bayar
                    </td>
                    <td style="font-size: 12px;">Rp.</td>
                    <td style="text-align: right; font-size: 12px;">
                        <strong>{{ number_format($kurangLebihBayar, 0, ',', '.') }}</strong>
                    </td>
                </tr>
            </table>
        </footer>
    </main>
</body>

</html>