<?php

namespace App\Http\Controllers;

use App\Models\Keluarga;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PendudukExportImportController extends Controller
{
    /**
     * Unduh Template Excel / CSV Kependudukan Resmi (20 Kolom Termasuk No. KK & Suku)
     */
    public function downloadTemplate(): StreamedResponse
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="Template_Impor_Penduduk_OpenSID.csv"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header 20 Kolom Termasuk No. KK & Suku
            fputcsv($file, [
                'nik',
                'no_kk',
                'nama',
                'sex',
                'tempatlahir',
                'tanggallahir',
                'suku',
                'kk_level',
                'agama_id',
                'pendidikan_kk_id',
                'pekerjaan_id',
                'status_kawin',
                'golongan_darah_id',
                'id_cluster',
                'status_dasar',
                'alamat_sekarang',
                'telepon',
                'nama_ayah',
                'nama_ibu',
                'foto',
            ]);

            // Sample Baris Contoh 1 (Kepala Keluarga)
            fputcsv($file, [
                '1209181203940001',
                '1209181203940000',
                'Ahmad Subagyo',
                '1', // 1: Laki-laki, 2: Perempuan
                'Medan',
                '1994-03-12',
                'Jawa',
                '1', // 1: Kepala Keluarga, 2: Suami, 3: Istri, 4: Anak
                '1', // 1: Islam, 2: Kristen, 3: Katholik, 4: Hindu, 5: Buddha
                '5', // 5: SLTA / Sederajat
                '12', // 12: Wiraswasta
                '2', // 1: Belum Kawin, 2: Kawin, 3: Cerai Hidup, 4: Cerai Mati
                '13', // 13: Tidak Tahu
                '1', // ID Dusun / Wilayah
                '1', // 1: Hidup (Aktif), 2: Meninggal, 3: Pindah, 4: Hilang
                'Dusun I Desa Serdang RT 001/RW 001',
                '081234567890',
                'Bambang S',
                'Siti Aminah',
                '',
            ]);

            // Sample Baris Contoh 2 (Istri - No KK Sama)
            fputcsv($file, [
                '1209185507980002',
                '1209181203940000',
                'Siti Rahmah',
                '2',
                'Asahan',
                '1998-07-15',
                'Batak',
                '3',
                '1',
                '8', // 8: Sarjana / S1
                '11', // 11: Karyawan Swasta
                '2',
                '4', // 4: O
                '1',
                '1',
                'Dusun II Desa Serdang RT 002/RW 001',
                '082198765432',
                'Abdul Kadir',
                'Nurhayati',
                '',
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Ekspor Data Penduduk Aktif ke File CSV / Excel (Termasuk No. KK & Suku)
     */
    public function exportData(): StreamedResponse
    {
        $fileName = 'Data_Penduduk_Desa_' . date('Y_m_d_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'NIK',
                'No. KK',
                'Nama Lengkap',
                'Jenis Kelamin',
                'Tempat Lahir',
                'Tanggal Lahir',
                'Usia (Tahun)',
                'Suku / Etnis',
                'Hubungan KK (SHDK)',
                'Agama ID',
                'Pendidikan ID',
                'Pekerjaan ID',
                'Status Perkawinan',
                'Golongan Darah ID',
                'ID Cluster (Dusun)',
                'Status Mutasi',
                'Alamat Tempat Tinggal',
                'Nomor Telepon',
                'Nama Ayah',
                'Nama Ibu',
                'Foto Path',
            ]);

            Penduduk::withoutGlobalScopes()->with('keluarga')->chunk(500, function ($penduduks) use ($file) {
                foreach ($penduduks as $p) {
                    fputcsv($file, [
                        $p->nik,
                        $p->keluarga->no_kk ?? '-',
                        $p->nama,
                        $p->sex == 1 ? '1' : '2',
                        $p->tempatlahir ?? '-',
                        $p->tanggallahir ? $p->tanggallahir->format('Y-m-d') : '-',
                        $p->usia ?? '-',
                        $p->suku ?? '-',
                        $p->kk_level ?? 4,
                        $p->agama_id ?? 1,
                        $p->pendidikan_kk_id ?? 1,
                        $p->pekerjaan_id ?? 1,
                        $p->status_kawin ?? 1,
                        $p->golongan_darah_id ?? 13,
                        $p->id_cluster ?? '',
                        $p->status_dasar ?? 1,
                        $p->alamat_sekarang ?? '-',
                        $p->telepon ?? '-',
                        $p->nama_ayah ?? '-',
                        $p->nama_ibu ?? '-',
                        $p->foto ?? '',
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
