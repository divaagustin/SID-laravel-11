<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LaporanPendudukPage extends Page
{
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function getTitle(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return 'Laporan Ringkasan & Statistik Kependudukan Desa';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    protected string $view = 'filament.pages.laporan-penduduk-page';

    public function getStatsProperty(): array
    {
        return Cache::remember('stats_demografi_lengkap_v4', 300, function () {
            // Base query penduduk hidup
            $baseQuery = DB::table('tweb_penduduk')->where('status_dasar', 1);

            $totalPenduduk = (clone $baseQuery)->count();
            $totalLaki     = (clone $baseQuery)->where('sex', 1)->count();
            $totalPerempuan= (clone $baseQuery)->where('sex', 2)->count();
            $totalKeluarga = DB::table('tweb_keluarga')->count();

            // 1. Kelompok Umur dengan Penanganan Safe Date (0000-00-00 & NULL)
            $validDateQuery = (clone $baseQuery)
                ->whereNotNull('tanggallahir')
                ->where('tanggallahir', '!=', '0000-00-00');

            $balita    = (clone $validDateQuery)->whereRaw('TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) <= 5')->count();
            $anak      = (clone $validDateQuery)->whereRaw('TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) BETWEEN 6 AND 17')->count();
            $produktif = (clone $validDateQuery)->whereRaw('TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) BETWEEN 18 AND 59')->count();
            $lansia    = (clone $validDateQuery)->whereRaw('TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) >= 60')->count();
            $tidakDiketahuiUmur = $totalPenduduk - ($balita + $anak + $produktif + $lansia);

            // 2. Breakdown Berdasarkan Agama
            $agama = DB::table('tweb_penduduk_agama')
                ->leftJoin('tweb_penduduk', function ($join) {
                    $join->on('tweb_penduduk.agama_id', '=', 'tweb_penduduk_agama.id')
                         ->where('tweb_penduduk.status_dasar', '=', 1);
                })
                ->select('tweb_penduduk_agama.nama', DB::raw('COUNT(tweb_penduduk.id) as total'))
                ->groupBy('tweb_penduduk_agama.id', 'tweb_penduduk_agama.nama')
                ->get();

            // 3. Breakdown Berdasarkan Pendidikan Dalam KK
            $pendidikan = DB::table('tweb_penduduk_pendidikan_kk')
                ->leftJoin('tweb_penduduk', function ($join) {
                    $join->on('tweb_penduduk.pendidikan_kk_id', '=', 'tweb_penduduk_pendidikan_kk.id')
                         ->where('tweb_penduduk.status_dasar', '=', 1);
                })
                ->select('tweb_penduduk_pendidikan_kk.nama', DB::raw('COUNT(tweb_penduduk.id) as total'))
                ->groupBy('tweb_penduduk_pendidikan_kk.id', 'tweb_penduduk_pendidikan_kk.nama')
                ->get();

            // 4. Breakdown Berdasarkan Pekerjaan Utama
            $pekerjaan = DB::table('tweb_penduduk_pekerjaan')
                ->leftJoin('tweb_penduduk', function ($join) {
                    $join->on('tweb_penduduk.pekerjaan_id', '=', 'tweb_penduduk_pekerjaan.id')
                         ->where('tweb_penduduk.status_dasar', '=', 1);
                })
                ->select('tweb_penduduk_pekerjaan.nama', DB::raw('COUNT(tweb_penduduk.id) as total'))
                ->groupBy('tweb_penduduk_pekerjaan.id', 'tweb_penduduk_pekerjaan.nama')
                ->having('total', '>', 0)
                ->orderByDesc('total')
                ->get();

            // 5. Breakdown Berdasarkan Status Perkawinan
            $statusKawinMap = [
                1 => 'Belum Kawin',
                2 => 'Kawin',
                3 => 'Cerai Hidup',
                4 => 'Cerai Mati',
            ];
            $statusKawinData = [];
            foreach ($statusKawinMap as $key => $label) {
                $statusKawinData[] = (object) [
                    'nama'  => $label,
                    'total' => (clone $baseQuery)->where('status_kawin', $key)->count(),
                ];
            }

            // 6. Breakdown Berdasarkan Dusun / Wilayah
            $wilayah = DB::table('tweb_wil_clusterdesa')
                ->whereNotNull('dusun')
                ->where('dusun', '!=', '')
                ->leftJoin('tweb_penduduk', function ($join) {
                    $join->on('tweb_penduduk.id_cluster', '=', 'tweb_wil_clusterdesa.id')
                         ->where('tweb_penduduk.status_dasar', '=', 1);
                })
                ->select('tweb_wil_clusterdesa.dusun', DB::raw('COUNT(tweb_penduduk.id) as total'))
                ->groupBy('tweb_wil_clusterdesa.dusun')
                ->get();

            // 7. Breakdown Berdasarkan Suku / Etnis
            $suku = DB::table('tweb_penduduk')
                ->where('status_dasar', 1)
                ->whereNotNull('suku')
                ->where('suku', '!=', '')
                ->select('suku as nama', DB::raw('COUNT(id) as total'))
                ->groupBy('suku')
                ->orderByDesc('total')
                ->get();

            return [
                'total'         => $totalPenduduk,
                'laki'          => $totalLaki,
                'perempuan'     => $totalPerempuan,
                'kk'            => $totalKeluarga,
                'balita'        => $balita,
                'anak'          => $anak,
                'produktif'     => $produktif,
                'lansia'        => $lansia,
                'unknown_umur'  => max(0, $tidakDiketahuiUmur),
                'agama'         => $agama,
                'pendidikan'    => $pendidikan,
                'pekerjaan'     => $pekerjaan,
                'status_kawin'  => $statusKawinData,
                'wilayah'       => $wilayah,
                'suku'          => $suku,
            ];
        });
    }
}
