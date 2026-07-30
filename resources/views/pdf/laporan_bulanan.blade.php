<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Bulanan Data Penduduk Berdasarkan Kewarganegaraan</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 1.2cm 1.2cm 1.2cm 1.2cm;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #000;
            line-height: 1.3;
        }
        .header-lampiran {
            font-size: 9pt;
            margin-bottom: 15px;
        }
        .main-title {
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        .sub-header {
            margin-bottom: 10px;
            font-size: 10pt;
            font-weight: bold;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #000;
            padding: 5px 6px;
            text-align: center;
            font-size: 9pt;
        }
        table.data-table th {
            font-weight: bold;
            background-color: #f2f2f2;
            text-transform: uppercase;
        }
        table.data-table td.text-left {
            text-align: left;
        }
        table.data-table tfoot td {
            font-weight: bold;
            background-color: #e6e6e6;
        }
        .footer-signature {
            float: right;
            width: 320px;
            text-align: center;
            margin-top: 15px;
            font-size: 10pt;
        }
        .signature-space {
            height: 60px;
        }
    </style>
</head>
<body>

    <div class="header-lampiran">
        Lampiran IV<br>
        Format Laporan Bulanan<br>
        Data Penduduk Berdasarkan Kewarganegaraan
    </div>

    <div class="main-title">
        DATA PENDUDUK BERDASARKAN KEWARGANEGARAAN
    </div>

    <div class="sub-header">
        Bulan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $data['bulan_nama'] }} {{ $data['tahun'] }}
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 25px;">NO</th>
                <th rowspan="2" style="width: 140px;" class="text-left">NAMA DUSUN</th>
                <th colspan="3">WARGA DESA (WNI TETAP)</th>
                <th colspan="3">WARGA LUAR DESA (TIDAK TETAP)</th>
                <th colspan="3">WARGA NEGARA ASING (WNA)</th>
                <th colspan="3">TOTAL KESELURUHAN</th>
            </tr>
            <tr>
                <th style="width: 35px;">L</th>
                <th style="width: 35px;">P</th>
                <th style="width: 45px;">JML</th>
                <th style="width: 35px;">L</th>
                <th style="width: 35px;">P</th>
                <th style="width: 45px;">JML</th>
                <th style="width: 35px;">L</th>
                <th style="width: 35px;">P</th>
                <th style="width: 45px;">JML</th>
                <th style="width: 35px;">L</th>
                <th style="width: 35px;">P</th>
                <th style="width: 50px;">JML</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['rows'] as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left" style="text-transform: uppercase;">{{ $row['dusun'] }}</td>
                    <td>{{ number_format($row['wni_l'], 0, ',', '.') }}</td>
                    <td>{{ number_format($row['wni_p'], 0, ',', '.') }}</td>
                    <td style="font-weight: bold;">{{ number_format($row['wni_total'], 0, ',', '.') }}</td>

                    <td>{{ $row['luar_l'] > 0 ? number_format($row['luar_l'], 0, ',', '.') : '' }}</td>
                    <td>{{ $row['luar_p'] > 0 ? number_format($row['luar_p'], 0, ',', '.') : '' }}</td>
                    <td style="font-weight: bold;">{{ $row['luar_total'] > 0 ? number_format($row['luar_total'], 0, ',', '.') : '' }}</td>

                    <td>{{ $row['wna_l'] > 0 ? number_format($row['wna_l'], 0, ',', '.') : '' }}</td>
                    <td>{{ $row['wna_p'] > 0 ? number_format($row['wna_p'], 0, ',', '.') : '' }}</td>
                    <td style="font-weight: bold;">{{ $row['wna_total'] > 0 ? number_format($row['wna_total'], 0, ',', '.') : '' }}</td>

                    <td>{{ number_format($row['all_l'], 0, ',', '.') }}</td>
                    <td>{{ number_format($row['all_p'], 0, ',', '.') }}</td>
                    <td style="font-weight: bold;">{{ number_format($row['all_total'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">Jumlah Total</td>
                <td>{{ number_format($data['total']['wni_l'], 0, ',', '.') }}</td>
                <td>{{ number_format($data['total']['wni_p'], 0, ',', '.') }}</td>
                <td>{{ number_format($data['total']['wni_total'], 0, ',', '.') }}</td>

                <td>{{ $data['total']['luar_l'] > 0 ? number_format($data['total']['luar_l'], 0, ',', '.') : '' }}</td>
                <td>{{ $data['total']['luar_p'] > 0 ? number_format($data['total']['luar_p'], 0, ',', '.') : '' }}</td>
                <td>{{ $data['total']['luar_total'] > 0 ? number_format($data['total']['luar_total'], 0, ',', '.') : '' }}</td>

                <td>{{ $data['total']['wna_l'] > 0 ? number_format($data['total']['wna_l'], 0, ',', '.') : '' }}</td>
                <td>{{ $data['total']['wna_p'] > 0 ? number_format($data['total']['wna_p'], 0, ',', '.') : '' }}</td>
                <td>{{ $data['total']['wna_total'] > 0 ? number_format($data['total']['wna_total'], 0, ',', '.') : '' }}</td>

                <td>{{ number_format($data['total']['all_l'], 0, ',', '.') }}</td>
                <td>{{ number_format($data['total']['all_p'], 0, ',', '.') }}</td>
                <td>{{ number_format($data['total']['all_total'], 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer-signature">
        <div>{{ $data['config']->nama_desa ?? 'Serdang' }}, {{ date('d') }} {{ $data['bulan_nama'] }} {{ date('Y') }}</div>
        <div>Kepala Desa {{ $data['config']->nama_desa ?? 'Serdang' }}</div>
        <div class="signature-space"></div>
        <div style="font-weight: bold; text-decoration: underline; text-transform: uppercase;">
            {{ $data['config']->nama_kepala_desa ?? 'NANANG LUKMAN' }}
        </div>
    </div>

</body>
</html>
