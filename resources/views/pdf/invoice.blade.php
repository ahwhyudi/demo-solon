<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            padding: 30px;
            color: #333;
        }

        .company-info,
        .client-info {
            margin-bottom: 20px;
        }

        .company-info h2 {
            margin: 0;
            font-size: 20px;
            color: #007bff;
        }

        .invoice-meta {
            margin-bottom: 20px;
        }

        .invoice-meta table {
            width: 100%;
        }

        .invoice-meta td {
            padding: 4px 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #aaa;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f0f0f0;
        }

        .total {
            text-align: right;
            font-weight: bold;
        }

        .signature-section {
            margin-top: 50px;
            width: 100%;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            width: 45%;
            text-align: center;
        }

        .signature-space {
            height: 80px;
            margin-top: 40px;
            border-bottom: 1px solid #444;
        }

        .footer {
            text-align: center;
            font-size: 11px;
            color: #666;
            margin-top: 50px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>

<body>

    {{-- Company Info --}}
    <div class="company-info">
        <h2>PT. SOLON TEKNOLOGI INDONESIA</h2>
        <div>Jl. Melati No. 123, Jakarta Selatan</div>
        <div>Telp: (021) 12345678 | Email: info@solon.id</div>
    </div>

    {{-- Client Info --}}
    <div class="client-info">
        <strong>Kepada Yth:</strong><br>
        {{ $client }}<br>
        {{ $client_address ?? 'Jl. Contoh Alamat No. 45, Bandung' }}
    </div>

    {{-- Invoice Meta --}}
    <div class="invoice-meta">
        <table>
            <tr>
                <td><strong>Kode Invoice:</strong> {{ $kode_invoice }}</td>
                <td><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($tanggal)->format('d-m-Y') }}</td>
            </tr>
        </table>
    </div>

    {{-- Items Table --}}
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Item</th>
                <th>Harga</th>
                <th>Diskon</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
                $no = 1;
            @endphp
            @foreach ($items as $i => $item)
                @php $total += $item['total']; @endphp
                <tr>
                    <td>{{ $no }}</td>
                    <td>{{ $item['nama'] }} </td>
                    <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item['diskon'], 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item['total'], 0, ',', '.') }}</td>
                </tr>
                @php $no++; @endphp
            @endforeach
            <tr>
                <td colspan="2" class="total">Total</td>
                <td><strong>Rp {{ number_format($items->sum('harga'), 0, ',', '.') }}</strong></td>
                <td><strong>Rp {{ number_format($items->sum('diskon'), 0, ',', '.') }}</strong></td>
                <td><strong>Rp {{ number_format($items->sum('total'), 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    {{-- Signature Section --}}
    <div style="width: 100%; margin-top: 60px;">
        <div style="width: 45%; float: right; text-align: center;">
            Hormat Kami,<br>
            <strong>PT. SOLON TEKNOLOGI INDONESIA</strong><br><br>
            @if ($ttd_admin ?? false)
                <img src="{{ public_path($ttd_admin) }}" alt="TTD Admin" height="80"><br>
            @else
                <div style="height: 80px;"></div>
            @endif
            <strong style="text-decoration: underline">Shawn Timothy</strong>
            <div class="">
                <div>General Affair</div>
            </div>
        </div>
    </div>
    <div style="clear: both;"></div>


    {{-- Footer --}}
    <div class="footer">
        Invoice ini dicetak secara otomatis dan tidak memerlukan tanda tangan basah.
    </div>

</body>

</html>
