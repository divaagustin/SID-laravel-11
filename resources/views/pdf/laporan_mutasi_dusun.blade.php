<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Mutasi Penduduk Per Dusun - {{ $data['config']->nama_desa ?? 'Serdang' }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 12mm 15mm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 8.5pt;
            color: #000;
            line-height: 1.2;
        }
        .header-title {
            margin-bottom: 12px;
        }
        .header-title h3 {
            margin: 0 0 4px 0;
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header-info {
            font-size: 9.5pt;
            font-weight: bold;
            margin-bottom: 10px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #000;
            padding: 4px 3px;
            text-align: center;
            font-size: 8pt;
        }
        table.data-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
        }
        table.data-table td.left {
            text-align: left;
            padding-left: 6px;
        }
        .bg-gray {
            background-color: #e0e0e0;
            font-weight: bold;
        }
        .signature-section {
            margin-top: 25px;
            float: right;
            width: 300px;
            text-align: center;
            font-size: 9pt;
        }
        .signature-section .title {
            font-weight: bold;
            text-transform: uppercase;
        }
        .signature-section .space {
            height: 55px;
        }
        .signature-section .name {
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

    <div class="header-title">
        <h3>KECAMATAN {{ strtoupper($data['config']->nama_kecamatan ?? 'MERANTI') }}</h3>
        <div class="header-info">
            DESA &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ strtoupper($data['config']->nama_desa ?? 'SERDANG') }}<br>
            BULAN &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ strtoupper($data['bulan_nama']) }} {{ $data['tahun'] }}
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 25px;">NO</th>
                <th rowspan="2" style="width: 70px;">DUSUN</th>
                <th colspan="3">PENDUDUK AWAL BULAN INI</th>
                <th colspan="3">LAHIR BULAN INI</th>
                <th colspan="3">MATI BULAN INI</th>
                <th colspan="3">PENDATANG BULAN INI</th>
                <th colspan="3">PINDAH BULAN INI</th>
                <th colspan="3">PENDUDUK AKHIR</th>
                <th rowspan="2" style="width: 50px;">JUMLAH KK</th>
            </tr>
            <tr>
                <!-- Awal -->
                <th style="width: 30px;">LK</th>
                <th style="width: 30px;">PR</th>
                <th class="bg-gray" style="width: 35px;">LK+PR</th>
                <!-- Lahir -->
                <th style="width: 30px;">LK</th>
                <th style="width: 30px;">PR</th>
                <th class="bg-gray" style="width: 35px;">LK+PR</th>
                <!-- Mati -->
                <th style="width: 30px;">LK</th>
                <th style="width: 30px;">PR</th>
                <th class="bg-gray" style="width: 35px;">LK+PR</th>
                <!-- Datang -->
                <th style="width: 30px;">LK</th>
                <th style="width: 30px;">PR</th>
                <th class="bg-gray" style="width: 35px;">LK+PR</th>
                <!-- Pindah -->
                <th style="width: 30px;">LK</th>
                <th style="width: 30px;">PR</th>
                <th class="bg-gray" style="width: 35px;">LK+PR</th>
                <!-- Akhir -->
                <th style="width: 30px;">LK</th>
                <th style="width: 30px;">PR</th>
                <th class="bg-gray" style="width: 35px;">LK+PR</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data['dusun_mutasi_rows'] as $r)
                <tr>
                    <td>{{ $r['no'] }}</td>
                    <td class="left">{{ $r['dusun'] }}</td>
                    <td>{{ $r['awal_l'] }}</td>
                    <td>{{ $r['awal_p'] }}</td>
                    <td class="bg-gray">{{ $r['awal_total'] }}</td>
                    <td>{{ $r['lahir_l'] }}</td>
                    <td>{{ $r['lahir_p'] }}</td>
                    <td class="bg-gray">{{ $r['lahir_total'] }}</td>
                    <td>{{ $r['mati_l'] }}</td>
                    <td>{{ $r['mati_p'] }}</td>
                    <td class="bg-gray">{{ $r['mati_total'] }}</td>
                    <td>{{ $r['datang_l'] }}</td>
                    <td>{{ $r['datang_p'] }}</td>
                    <td class="bg-gray">{{ $r['datang_total'] }}</td>
                    <td>{{ $r['pindah_l'] }}</td>
                    <td>{{ $r['pindah_p'] }}</td>
                    <td class="bg-gray">{{ $r['pindah_total'] }}</td>
                    <td>{{ $r['akhir_l'] }}</td>
                    <td>{{ $r['akhir_p'] }}</td>
                    <td class="bg-gray">{{ $r['akhir_total'] }}</td>
                    <td>{{ number_format($r['jumlah_kk'], 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="20">Tidak ada data wilayah dusun.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="bg-gray">
                <td colspan="2" style="text-align: center; font-weight: bold;">JUMLAH</td>
                <td>{{ number_format($data['dusun_mutasi_totals']['awal_l'], 0, ',', '.') }}</td>
                <td>{{ number_format($data['dusun_mutasi_totals']['awal_p'], 0, ',', '.') }}</td>
                <td>{{ number_format($data['dusun_mutasi_totals']['awal_total'], 0, ',', '.') }}</td>
                <td>{{ number_format($data['dusun_mutasi_totals']['lahir_l'], 0, ',', '.') }}</td>
                <td>{{ number_format($data['dusun_mutasi_totals']['lahir_p'], 0, ',', '.') }}</td>
                <td>{{ number_format($data['dusun_mutasi_totals']['lahir_total'], 0, ',', '.') }}</td>
                <td>{{ number_format($data['dusun_mutasi_totals']['mati_l'], 0, ',', '.') }}</td>
                <td>{{ number_format($data['dusun_mutasi_totals']['mati_p'], 0, ',', '.') }}</td>
                <td>{{ number_format($data['dusun_mutasi_totals']['mati_total'], 0, ',', '.') }}</td>
                <td>{{ number_format($data['dusun_mutasi_totals']['datang_l'], 0, ',', '.') }}</td>
                <td>{{ number_format($data['dusun_mutasi_totals']['datang_p'], 0, ',', '.') }}</td>
                <td>{{ number_format($data['dusun_mutasi_totals']['datang_total'], 0, ',', '.') }}</td>
                <td>{{ number_format($data['dusun_mutasi_totals']['pindah_l'], 0, ',', '.') }}</td>
                <td>{{ number_format($data['dusun_mutasi_totals']['pindah_p'], 0, ',', '.') }}</td>
                <td>{{ number_format($data['dusun_mutasi_totals']['pindah_total'], 0, ',', '.') }}</td>
                <td>{{ number_format($data['dusun_mutasi_totals']['akhir_l'], 0, ',', '.') }}</td>
                <td>{{ number_format($data['dusun_mutasi_totals']['akhir_p'], 0, ',', '.') }}</td>
                <td>{{ number_format($data['dusun_mutasi_totals']['akhir_total'], 0, ',', '.') }}</td>
                <td>{{ number_format($data['dusun_mutasi_totals']['jumlah_kk'], 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- TANDA TANGAN --}}
    @php
        $penandatangan = \App\Models\AparaturDesa::with('jabatan')
            ->where('pamong_status', 1)
            ->where(function ($q) {
                $q->where('jabatan_id', 2)
                  ->orWhereHas('jabatan', fn ($j) => $j->where('nama', 'like', '%Sekretaris%'));
            })->first();

        if (!$penandatangan) {
            $penandatangan = \App\Models\AparaturDesa::with('jabatan')
                ->where('pamong_status', 1)
                ->where(function ($q) {
                    $q->where('jabatan_id', 1)
                      ->orWhereHas('jabatan', fn ($j) => $j->where('nama', 'like', '%Kepala Desa%'));
                })->first();
        }

        $namaPenandatangan = $penandatangan->pamong_nama ?? ($data['config']->nama_kepala_desa ?? 'RODIYAH');
        $namaJabatan = $penandatangan->jabatan->nama ?? 'SEKRETARIS';
    @endphp

    <div class="signature-section">
        <p>
            {{ strtoupper($data['config']->nama_desa ?? 'SERDANG') }}, &nbsp;&nbsp; {{ strtoupper($data['bulan_nama']) }} {{ $data['tahun'] }}<br>
            AN. KEPALA DESA {{ strtoupper($data['config']->nama_desa ?? 'SERDANG') }}<br>
            <span class="title">{{ strtoupper($namaJabatan) }}</span>
        </p>
        <div class="space"></div>
        <p class="name">{{ strtoupper($namaPenandatangan) }}</p>
    </div>

</body>
</html>
