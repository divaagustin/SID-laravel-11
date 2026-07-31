<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Surat {{ $log->no_surat ?? 'Resmi' }}</title>
    <style>
        @page {
            margin: 1.5cm 2cm 1.5cm 2cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #000;
        }
        .header-table {
            width: 100%;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 25px;
        }
        .header-logo {
            width: 75px;
            height: auto;
            text-align: center;
        }
        .header-text {
            text-align: center;
        }
        .header-text h3 {
            margin: 0;
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header-text h2 {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header-text h1 {
            margin: 0;
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header-text p {
            margin: 2px 0 0 0;
            font-size: 9.5pt;
            font-style: italic;
        }
        .surat-title {
            text-align: center;
            margin-bottom: 25px;
        }
        .surat-title h3 {
            margin: 0;
            font-size: 12pt;
            font-decoration: underline;
            text-transform: uppercase;
            font-weight: bold;
        }
        .surat-title p {
            margin: 3px 0 0 0;
            font-family: 'Courier New', Courier, monospace;
            font-weight: bold;
            font-size: 11pt;
        }
        .content-table {
            width: 100%;
            margin-left: 20px;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .content-table td {
            vertical-align: top;
            padding: 3px 0;
        }
        .content-table td.label {
            width: 170px;
        }
        .content-table td.separator {
            width: 15px;
        }
        .signature-table {
            width: 100%;
            margin-top: 40px;
            page-break-inside: avoid;
        }
        .signature-box {
            width: 45%;
            float: right;
            text-align: center;
        }
        .tte-qr-box {
            display: inline-block;
            border: 1px solid #ccc;
            padding: 6px;
            border-radius: 8px;
            margin-top: 10px;
            margin-bottom: 10px;
            background: #fafafa;
        }
    </style>
</head>
<body>

    {{-- KOP SURAT RESMI PEMERINTAH DESA --}}
    <table class="header-table">
        <tr>
            <td class="header-logo">
                @if(isset($config->logo) && $config->logo)
                    <img src="{{ public_path('storage/' . $config->logo) }}" style="width: 70px; height: auto;" alt="Logo Desa">
                @else
                    <div style="font-size: 28pt; font-weight: bold;">🏡</div>
                @endif
            </td>
            <td class="header-text">
                <h3>PEMERINTAH KABUPATEN {{ strtoupper($config->nama_kabupaten ?? 'ASAHAN') }}</h3>
                <h2>KECAMATAN {{ strtoupper($config->nama_kecamatan ?? 'MERANTI') }}</h2>
                <h1>DESA {{ strtoupper($config->nama_desa ?? 'SERDANG') }}</h1>
                <p>{{ $config->alamat_kantor ?? ('Kantor Desa ' . ($config->nama_desa ?? '') . ', Kode Pos ' . ($config->kode_pos ?? '')) }} | Telp: {{ $config->telepon ?? '-' }}</p>
            </td>
        </tr>
    </table>

    {{-- JUDUL SURAT & NOMOR SURAT --}}
    <div class="surat-title">
        <h3><u>{{ strtoupper($log->formatSurat->nama ?? 'SURAT KETERANGAN') }}</u></h3>
        <p>Nomor: {{ $log->no_surat ?? '470 / ' . date('Y') }}</p>
    </div>

    {{-- KALIMAT PEMBUKA --}}
    <p>Yang bertanda tangan di bawah ini Kepala Desa {{ $config->nama_desa ?? '' }}, Kecamatan {{ $config->nama_kecamatan ?? '' }}, Kabupaten {{ $config->nama_kabupaten ?? '' }}, Provinsi {{ $config->nama_propinsi ?? '' }}, menerangkan dengan sebenarnya bahwa:</p>

    {{-- RINCIAN IDENTITAS PEMOHON --}}
    <table class="content-table">
        <tr>
            <td class="label">Nama Lengkap</td>
            <td class="separator">:</td>
            <td><strong>{{ strtoupper($log->penduduk->nama ?? '-') }}</strong></td>
        </tr>
        <tr>
            <td class="label">NIK / No. KTP</td>
            <td class="separator">:</td>
            <td>{{ $log->penduduk->nik ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tempat / Tgl Lahir</td>
            <td class="separator">:</td>
            <td>{{ $log->penduduk->tempatlahir ?? ($config->nama_desa ?? '-') }}, {{ isset($log->penduduk->tanggallahir) ? \Carbon\Carbon::parse($log->penduduk->tanggallahir)->format('d F Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Jenis Kelamin</td>
            <td class="separator">:</td>
            <td>{{ isset($log->penduduk->sex) && $log->penduduk->sex == 1 ? 'Laki-laki' : 'Perempuan' }}</td>
        </tr>
        <tr>
            <td class="label">Agama</td>
            <td class="separator">:</td>
            <td>{{ $log->penduduk->agama->nama ?? 'Islam' }}</td>
        </tr>
        <tr>
            <td class="label">Pekerjaan</td>
            <td class="separator">:</td>
            <td>{{ $log->penduduk->pekerjaan->nama ?? 'Wiraswasta' }}</td>
        </tr>
        <tr>
            <td class="label">Alamat / Tempat Tinggal</td>
            <td class="separator">:</td>
            <td>{{ $log->penduduk->alamat_sekarang ?? ('Desa ' . ($config->nama_desa ?? '')) }}</td>
        </tr>
    </table>

    {{-- ISI / KETERANGAN KEBUTUHAN SURAT --}}
    <div style="margin-top: 15px; margin-bottom: 20px;">
        <p>Orang tersebut di atas adalah benar-benar warga penduduk Desa {{ $config->nama_desa ?? 'Serdang' }} yang bertempat tinggal di alamat tersebut di atas. Keterangan ini diberikan untuk keperluan: <strong>{{ $log->keterangan ?? 'Pengurusan Kelengkapan Administrasi' }}</strong>.</p>

        <p>Demikian Surat Keterangan ini dibuat dengan sebenarnya untuk dipergunakan sebagaimana mestinya.</p>
    </div>

    {{-- TANDA TANGAN & TTE QR CODE CONTAINER --}}
    @php
        $penandatangan = null;

        if (! empty($log->id_pamong)) {
            $penandatangan = \App\Models\AparaturDesa::with('jabatan')->find($log->id_pamong);
        } elseif (! empty($log->pamong_id)) {
            $penandatangan = \App\Models\AparaturDesa::with('jabatan')->find($log->pamong_id);
        }

        if (! $penandatangan) {
            $penandatangan = \App\Models\AparaturDesa::with('jabatan')
                ->where('pamong_status', 1)
                ->where(function ($q) {
                    $q->where('jabatan_id', 1)
                      ->orWhereHas('jabatan', fn ($j) => $j->where('nama', 'like', '%Kepala Desa%'));
                })->first();
        }

        if (! $penandatangan) {
            $penandatangan = \App\Models\AparaturDesa::with('jabatan')
                ->where('pamong_status', 1)
                ->where(function ($q) {
                    $q->where('jabatan_id', 2)
                      ->orWhereHas('jabatan', fn ($j) => $j->where('nama', 'like', '%Sekretaris%'));
                })->first();
        }

        if (! $penandatangan) {
            $penandatangan = \App\Models\AparaturDesa::with('jabatan')
                ->where('pamong_status', 1)
                ->first();
        }

        $namaJabatan = $penandatangan->jabatan->nama ?? 'Kepala Desa';
        $namaPenandatangan = $penandatangan->pamong_nama ?? ($config->nama_kepala_desa ?? 'KEPALA DESA');
        $nipPenandatangan = $penandatangan->pamong_nip ?? ($config->nip_kepala_desa ?? null);
    @endphp

    <div class="signature-table">
        <div class="signature-box">
            <p>{{ $config->nama_desa ?? 'Serdang' }}, {{ \Carbon\Carbon::parse($log->created_at ?? now())->translatedFormat('d F Y') }}<br>
            <strong>{{ $namaJabatan }} {{ $config->nama_desa ?? 'Serdang' }}</strong></p>

            @if(isset($log->is_tte) && $log->is_tte)
                {{-- TTE QR Code Stamp Container --}}
                <div class="tte-qr-box">
                    {!! QrCode::size(80)->generate(url('/verifikasi-tte/' . $log->id)) !!}
                    <div style="font-size: 7.5pt; font-family: monospace; margin-top: 3px; color: #555;">
                        Ditinjau &amp; Ditandatangani Secara Elektronik (TTE BSRE)
                    </div>
                </div>
            @else
                <div style="height: 65px;"></div>
            @endif

            <p style="margin-top: 5px;">
                <strong><u>{{ strtoupper($namaPenandatangan) }}</u></strong><br>
                @if($nipPenandatangan)
                    NIP. {{ $nipPenandatangan }}
                @endif
            </p>
        </div>
    </div>

</body>
</html>
