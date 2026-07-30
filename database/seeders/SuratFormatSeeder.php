<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuratFormat;

class SuratFormatSeeder extends Seeder
{
    /**
     * Run the database seeds for 15 Permendagri Standard Village Certificate Formats.
     */
    public function run(): void
    {
        $formats = [
            [
                'kode_surat' => 'S-01',
                'url_surat' => 'surat_ket_usaha',
                'nama' => 'Surat Keterangan Usaha (SKU)',
                'jenis' => 1,
                'mandiri' => 1,
                'syarat_surat' => 'KTP, KK, Foto Tempat Usaha',
            ],
            [
                'kode_surat' => 'S-02',
                'url_surat' => 'surat_ket_tidak_mampu',
                'nama' => 'Surat Keterangan Tidak Mampu (SKTM)',
                'jenis' => 1,
                'mandiri' => 1,
                'syarat_surat' => 'KTP, KK, Keterangan RT/RW',
            ],
            [
                'kode_surat' => 'S-03',
                'url_surat' => 'surat_ket_domisili',
                'nama' => 'Surat Keterangan Domisili Warga (SKD)',
                'jenis' => 1,
                'mandiri' => 1,
                'syarat_surat' => 'KTP, KK',
            ],
            [
                'kode_surat' => 'S-04',
                'url_surat' => 'surat_ket_kematian',
                'nama' => 'Surat Keterangan Kematian (N6)',
                'jenis' => 1,
                'mandiri' => 1,
                'syarat_surat' => 'KTP Almarhum, KK, Keterangan RS/Dokter',
            ],
            [
                'kode_surat' => 'S-05',
                'url_surat' => 'surat_ket_penghasilan',
                'nama' => 'Surat Keterangan Penghasilan Orang Tua',
                'jenis' => 1,
                'mandiri' => 1,
                'syarat_surat' => 'KTP Orang Tua, KK',
            ],
            [
                'kode_surat' => 'S-06',
                'url_surat' => 'surat_pengantar_skck',
                'nama' => 'Surat Pengantar Catatan Kepolisian (SKCK)',
                'jenis' => 1,
                'mandiri' => 1,
                'syarat_surat' => 'KTP, KK, Pas Foto 4x6',
            ],
            [
                'kode_surat' => 'S-07',
                'url_surat' => 'surat_ket_beda_nama',
                'nama' => 'Surat Keterangan Beda Nama',
                'jenis' => 1,
                'mandiri' => 1,
                'syarat_surat' => 'KTP, KK, Ijazah/Sertifikat Pembanding',
            ],
            [
                'kode_surat' => 'S-08',
                'url_surat' => 'surat_ket_belum_menikah',
                'nama' => 'Surat Keterangan Belum Menikah',
                'jenis' => 1,
                'mandiri' => 1,
                'syarat_surat' => 'KTP, KK, Pernyataan Belum Menikah Bermaterai',
            ],
            [
                'kode_surat' => 'S-09',
                'url_surat' => 'surat_ket_pindah',
                'nama' => 'Surat Keterangan Pindah Penduduk',
                'jenis' => 1,
                'mandiri' => 1,
                'syarat_surat' => 'KTP, KK Asli, Alamat Tujuan Pindah',
            ],
            [
                'kode_surat' => 'S-10',
                'url_surat' => 'surat_izin_keramaian',
                'nama' => 'Surat Keterangan Izin Keramaian / Acara',
                'jenis' => 1,
                'mandiri' => 1,
                'syarat_surat' => 'KTP Penanggung Jawab, Jadwal Acara',
            ],
            [
                'kode_surat' => 'S-11',
                'url_surat' => 'surat_ket_kelahiran',
                'nama' => 'Surat Keterangan Kelahiran Warga',
                'jenis' => 1,
                'mandiri' => 1,
                'syarat_surat' => 'KTP Orang Tua, KK, Keterangan Bidan/Klinik',
            ],
            [
                'kode_surat' => 'S-12',
                'url_surat' => 'surat_ket_beda_nik',
                'nama' => 'Surat Keterangan Beda NIK KTP/KK',
                'jenis' => 1,
                'mandiri' => 1,
                'syarat_surat' => 'KTP, KK, Dokumen Pembanding',
            ],
            [
                'kode_surat' => 'S-13',
                'url_surat' => 'surat_ket_beasiswa',
                'nama' => 'Surat Keterangan Permohonan Beasiswa',
                'jenis' => 1,
                'mandiri' => 1,
                'syarat_surat' => 'KTP, KK, Kartu Pelajar/Mahasiswa',
            ],
            [
                'kode_surat' => 'S-14',
                'url_surat' => 'surat_ket_domisili_usaha',
                'nama' => 'Surat Keterangan Domisili Tempat Usaha',
                'jenis' => 1,
                'mandiri' => 1,
                'syarat_surat' => 'KTP Pemilik, Akta Pendirian, Foto Tempat Usaha',
            ],
            [
                'kode_surat' => 'S-15',
                'url_surat' => 'surat_ket_ahli_waris',
                'nama' => 'Surat Keterangan Ahli Waris',
                'jenis' => 1,
                'mandiri' => 1,
                'syarat_surat' => 'KTP Ahli Waris, Surat Kematian, Pernyataan Waris',
            ],
        ];

        foreach ($formats as $fmt) {
            SuratFormat::updateOrCreate(
                ['url_surat' => $fmt['url_surat']],
                [
                    'kode_surat' => $fmt['kode_surat'],
                    'nama' => $fmt['nama'],
                    'jenis' => $fmt['jenis'],
                    'mandiri' => $fmt['mandiri'],
                    'syarat_surat' => $fmt['syarat_surat'],
                    'config_id' => 1,
                ]
            );
        }
    }
}
