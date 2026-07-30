<!DOCTYPE html>
<html lang="id">

<head>
    <style>

        @page {
            margin: 225px 60px 120px 120px;
            font-family: 'Times New Roman', serif;
        }

        .header-notaris { 
            position: fixed; 
            top: -225px;
            left: 0px;
            right: 0px;
            width: 100%;
            text-align: center;
            border-bottom: 1px solid #000;

        }

        .header-notaris h1 { 
            text-align: center; 
            margin-top: 0px;
            margin-bottom: 15px; 
        }

        .header-notaris h2 { 
            text-align: center; margin: 0px; 
        }
        .header-notaris h1, .header-notaris h2 {
            line-height: 1.1;
        }

        h3 {
            margin-bottom: 15px; 
        }

        .terms-section { margin-top: 30px; font-size: 11px; }
        
        .signature-grid td {
            border: none !important;
            padding: 0;
        }

        
        footer {
            position: fixed; 
            bottom: -100px;
            left: 0px; 
            right: 0px;
            text-align: center;
            font-size: 10px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }


        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .table tbody tr:last-child td {
            text-align: center;
            font-weight: bold;
        }

    </style>



</head>

<body>

        <header class="header-notaris">
            <ol>
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('garuda.png'))) }}" 
                style="width: 100px; height: auto;">
            <h2 style="font-size: 14px;">NOTARIS & PPAT</h2>
            <h1 style="font-size: 14px;"> {{ $notaris_name }}</h1>
            <div style="font-size: 8px;">{{ $sk_kumham }}</div>            
            <div style="font-size: 8px;">{{ $tanggal_sk_kumham}}</div>
            <div style="font-size: 8px;">{{ $sk_bpn }}</div>
            <div style="font-size: 8px;">{{ $tanggal_sk_bpn }}</div>
            {{-- <div class="divider"></div> --}}
            </ol>
        </header>

    <footer>
        <div style="font-size: 9px;">
            <div>{!! nl2br(e($notaris_address_location)) !!}</div>
            <div>{{ $notaris_phone_number }}</li>
            <div>{{ $notaris_email }}</li>
        </div>
    </footer>
    
    <h3 style="text-align: center; text-decoration: underline; font-size: 16px; font-weight: bold">
        QUOTATION
    </h3>

    <div style="margin-bottom: 100px; font-size: 12px; font-weight: bold;">
        Kepada Yth, <br> <strong>{{ $client_name }}</strong>
    </div>

    <div style="font-size: 12px;"> Berikut kami sampaikan quotation yang harus dibayarkan:</div>
        <div style="margin-bottom: 10px;"></div>
    <table class="table" style="font-size: 12px;">
        <thead>
            <tr>
                <th>No.</th>
                <th>Keterangan</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item['nama'] }}</td>
                <td>{{ number_format($item['total'], 2, ',', '.')}}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="2" style="text-align: center;">T O T A L yang harus dibayar</td>
                <td><strong>{{ number_format($total_bayar, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="terms-section">
        <strong>Syarat Ketentuan:</strong>
        <ol style="font-size: 9px;">
            <li>Harus melakukan tanda jadi setelah memberikan dokumen kepada Notaris, DP berlaku 2 minggu.</li>
            <li>Jika klien tidak melengkapi dokumen, namun sudah memberikan DP, dalam kurun waktu 2 minggu, maka DP dianggap hangus.</li>
            <li>Pembayaran DP minimal 50% dari total keseluruhan biaya.</li>
            <li>Jika melakukan pembatalan secara sepihak, dan pihak notaris sudah melakukan pekerjaan sesuai permintaan klien, maka pihak klien wajib membayar 75% (dp 50% + 25% Pinalty).</li>
        </ol>
    </div>

    <table class="signature-grid" style="width: 100%; margin-top: 50px; border-collapse: collapse;">
    <tr>
        <td style="width: 50%; text-align: center; vertical-align: top;">
            MENYETUJUI,<br>
            <div style="height: 100px;"></div>
            <strong>{{ $client_name }}</strong>
        </td>
        <td style="width: 50%; text-align: center; vertical-align: top;">
            {{ $notaris_location }}, {{ $date }}<br>
            <div style="height: 100px;"></div>
            <strong>{{ $notaris_name }}</strong>
        </td>
    </tr>
</table>

</body>
</html>