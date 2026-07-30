<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class LaporanBulananPage extends Page
{
    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return 'heroicon-o-document-chart-bar';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Kependudukan';
    }

    public static function getNavigationLabel(): string
    {
        return 'Laporan Bulanan & Demografi';
    }

    public function getTitle(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return 'Laporan Bulanan & Statistik Demografi Desa';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    protected string $view = 'filament.pages.laporan-bulanan-page';

    public int $bulan;
    public int $tahun;

    public function mount(): void
    {
        $this->bulan = (int) date('m');
        $this->tahun = (int) date('Y');
    }

    public function getReportDataProperty(): array
    {
        $config = DB::table('config')->first();

        // 1. Data Dusun & Kewarganegaraan (Lampiran IV)
        $dusunList = DB::table('tweb_wil_clusterdesa')
            ->whereNotNull('dusun')
            ->where('dusun', '!=', '')
            ->select('dusun')
            ->distinct()
            ->orderBy('dusun')
            ->pluck('dusun');

        if ($dusunList->isEmpty()) {
            $dusunList = collect(['DUSUN I', 'DUSUN II', 'DUSUN III']);
        }

        $rows = [];
        $totalWniL = 0;
        $totalWniP = 0;
        $totalLuarL = 0;
        $totalLuarP = 0;
        $totalWnaL = 0;
        $totalWnaP = 0;

        foreach ($dusunList as $dusunName) {
            $clusterIds = DB::table('tweb_wil_clusterdesa')
                ->where('dusun', $dusunName)
                ->pluck('id');

            $query = DB::table('tweb_penduduk')
                ->whereIn('id_cluster', $clusterIds)
                ->where('status_dasar', 1);

            // 1. WNI Tetap (Warga Asli Desa)
            $wniL = (clone $query)->where(function ($q) {
                $q->where('warganegara_id', 1)->orWhereNull('warganegara_id');
            })->where(function ($q) {
                $q->where('status', 1)->orWhereNull('status');
            })->where('sex', 1)->count();

            $wniP = (clone $query)->where(function ($q) {
                $q->where('warganegara_id', 1)->orWhereNull('warganegara_id');
            })->where(function ($q) {
                $q->where('status', 1)->orWhereNull('status');
            })->where('sex', 2)->count();

            // 2. Warga Luar Desa (Penduduk Tidak Tetap / Pendatang WNI)
            $luarL = (clone $query)->where(function ($q) {
                $q->where('warganegara_id', 1)->orWhereNull('warganegara_id');
            })->where('status', 2)->where('sex', 1)->count();

            $luarP = (clone $query)->where(function ($q) {
                $q->where('warganegara_id', 1)->orWhereNull('warganegara_id');
            })->where('status', 2)->where('sex', 2)->count();

            // 3. WNA (Warga Negara Asing)
            $wnaL = (clone $query)->where('warganegara_id', 2)->where('sex', 1)->count();
            $wnaP = (clone $query)->where('warganegara_id', 2)->where('sex', 2)->count();

            $wniTotal = $wniL + $wniP;
            $luarTotal = $luarL + $luarP;
            $wnaTotal = $wnaL + $wnaP;

            $allL = $wniL + $luarL + $wnaL;
            $allP = $wniP + $luarP + $wnaP;
            $allTotal = $allL + $allP;

            $totalWniL += $wniL;
            $totalWniP += $wniP;
            $totalLuarL += $luarL;
            $totalLuarP += $luarP;
            $totalWnaL += $wnaL;
            $totalWnaP += $wnaP;

            $rows[] = [
                'dusun' => $dusunName,
                'wni_l' => $wniL,
                'wni_p' => $wniP,
                'wni_total' => $wniTotal,
                'luar_l' => $luarL,
                'luar_p' => $luarP,
                'luar_total' => $luarTotal,
                'wna_l' => $wnaL,
                'wna_p' => $wnaP,
                'wna_total' => $wnaTotal,
                'all_l' => $allL,
                'all_p' => $allP,
                'all_total' => $allTotal,
            ];
        }

        $grandWniTotal = $totalWniL + $totalWniP;
        $grandLuarTotal = $totalLuarL + $totalLuarP;
        $grandWnaTotal = $totalWnaL + $totalWnaP;
        $grandAllL = $totalWniL + $totalLuarL + $totalWnaL;
        $grandAllP = $totalWniP + $totalLuarP + $totalWnaP;
        $grandAllTotal = $grandAllL + $grandAllP;

        // 2. Data Rentang Usia
        $validDateQuery = DB::table('tweb_penduduk')
            ->where('status_dasar', 1)
            ->whereNotNull('tanggallahir')
            ->where('tanggallahir', '!=', '0000-00-00');

        $ageRangesData = [
            [
                'label' => '0 - 12 Bulan',
                'l' => (clone $validDateQuery)->where('sex', 1)->whereRaw('TIMESTAMPDIFF(MONTH, tanggallahir, CURDATE()) BETWEEN 0 AND 12')->count(),
                'p' => (clone $validDateQuery)->where('sex', 2)->whereRaw('TIMESTAMPDIFF(MONTH, tanggallahir, CURDATE()) BETWEEN 0 AND 12')->count(),
            ],
            [
                'label' => '1 - 5 Tahun',
                'l' => (clone $validDateQuery)->where('sex', 1)->whereRaw('TIMESTAMPDIFF(MONTH, tanggallahir, CURDATE()) > 12 AND TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) < 5')->count(),
                'p' => (clone $validDateQuery)->where('sex', 2)->whereRaw('TIMESTAMPDIFF(MONTH, tanggallahir, CURDATE()) > 12 AND TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) < 5')->count(),
            ],
            [
                'label' => '5 - 7 Tahun',
                'l' => (clone $validDateQuery)->where('sex', 1)->whereRaw('TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) BETWEEN 5 AND 6')->count(),
                'p' => (clone $validDateQuery)->where('sex', 2)->whereRaw('TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) BETWEEN 5 AND 6')->count(),
            ],
            [
                'label' => '7 - 15 Tahun',
                'l' => (clone $validDateQuery)->where('sex', 1)->whereRaw('TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) BETWEEN 7 AND 14')->count(),
                'p' => (clone $validDateQuery)->where('sex', 2)->whereRaw('TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) BETWEEN 7 AND 14')->count(),
            ],
            [
                'label' => '15 - 56 Tahun',
                'l' => (clone $validDateQuery)->where('sex', 1)->whereRaw('TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) BETWEEN 15 AND 55')->count(),
                'p' => (clone $validDateQuery)->where('sex', 2)->whereRaw('TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) BETWEEN 15 AND 55')->count(),
            ],
            [
                'label' => '56 Tahun Keatas',
                'l' => (clone $validDateQuery)->where('sex', 1)->whereRaw('TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) >= 56')->count(),
                'p' => (clone $validDateQuery)->where('sex', 2)->whereRaw('TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) >= 56')->count(),
            ],
        ];

        // 3. Data Agama (6 Agama Resmi Indonesia)
        $totalPendudukAktif = DB::table('tweb_penduduk')->where('status_dasar', 1)->count();
        $agamaRaw = DB::table('tweb_penduduk_agama')
            ->leftJoin('tweb_penduduk', function ($join) {
                $join->on('tweb_penduduk.agama_id', '=', 'tweb_penduduk_agama.id')
                     ->where('tweb_penduduk.status_dasar', '=', 1);
            })
            ->select('tweb_penduduk_agama.nama', DB::raw('COUNT(tweb_penduduk.id) as total'))
            ->groupBy('tweb_penduduk_agama.id', 'tweb_penduduk_agama.nama')
            ->get();

        $agamaData = [];
        foreach ($agamaRaw as $a) {
            $persen = $totalPendudukAktif > 0 ? round(($a->total / $totalPendudukAktif) * 100, 2) : 0;
            $agamaData[] = [
                'nama' => $a->nama,
                'total' => $a->total,
                'persen' => $persen,
            ];
        }

        // 4. Data Suku / Etnis
        $sukuRaw = DB::table('tweb_penduduk')
            ->where('status_dasar', 1)
            ->whereNotNull('suku')
            ->where('suku', '!=', '')
            ->select('suku as nama', DB::raw('COUNT(id) as total'))
            ->groupBy('suku')
            ->orderByDesc('total')
            ->get();

        $sukuData = [];
        foreach ($sukuRaw as $s) {
            $persen = $totalPendudukAktif > 0 ? round(($s->total / $totalPendudukAktif) * 100, 2) : 0;
            $sukuData[] = [
                'nama' => $s->nama,
                'total' => $s->total,
                'persen' => $persen,
            ];
        }

        // 5. Data Pendidikan Dalam KK
        $pendidikanRaw = DB::table('tweb_penduduk_pendidikan_kk')
            ->leftJoin('tweb_penduduk', function ($join) {
                $join->on('tweb_penduduk.pendidikan_kk_id', '=', 'tweb_penduduk_pendidikan_kk.id')
                     ->where('tweb_penduduk.status_dasar', '=', 1);
            })
            ->select('tweb_penduduk_pendidikan_kk.nama', DB::raw('COUNT(tweb_penduduk.id) as total'))
            ->groupBy('tweb_penduduk_pendidikan_kk.id', 'tweb_penduduk_pendidikan_kk.nama')
            ->get();

        $pendidikanData = [];
        foreach ($pendidikanRaw as $p) {
            $persen = $totalPendudukAktif > 0 ? round(($p->total / $totalPendudukAktif) * 100, 2) : 0;
            $pendidikanData[] = [
                'nama' => $p->nama,
                'total' => $p->total,
                'persen' => $persen,
            ];
        }

        // 6. Data Pekerjaan Utama
        $pekerjaanRaw = DB::table('tweb_penduduk_pekerjaan')
            ->leftJoin('tweb_penduduk', function ($join) {
                $join->on('tweb_penduduk.pekerjaan_id', '=', 'tweb_penduduk_pekerjaan.id')
                     ->where('tweb_penduduk.status_dasar', '=', 1);
            })
            ->select('tweb_penduduk_pekerjaan.nama', DB::raw('COUNT(tweb_penduduk.id) as total'))
            ->groupBy('tweb_penduduk_pekerjaan.id', 'tweb_penduduk_pekerjaan.nama')
            ->having('total', '>', 0)
            ->orderByDesc('total')
            ->get();

        $pekerjaanData = [];
        foreach ($pekerjaanRaw as $pk) {
            $persen = $totalPendudukAktif > 0 ? round(($pk->total / $totalPendudukAktif) * 100, 2) : 0;
            $pekerjaanData[] = [
                'nama' => $pk->nama,
                'total' => $pk->total,
                'persen' => $persen,
            ];
        }

        // 7. Data Status Perkawinan
        $statusKawinMap = [
            1 => 'Belum Kawin',
            2 => 'Kawin',
            3 => 'Cerai Hidup',
            4 => 'Cerai Mati',
        ];
        $statusKawinData = [];
        foreach ($statusKawinMap as $key => $label) {
            $tot = DB::table('tweb_penduduk')->where('status_dasar', 1)->where('status_kawin', $key)->count();
            $persen = $totalPendudukAktif > 0 ? round(($tot / $totalPendudukAktif) * 100, 2) : 0;
            $statusKawinData[] = [
                'nama' => $label,
                'total' => $tot,
                'persen' => $persen,
            ];
        }

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return [
            'config' => $config,
            'bulan_nama' => $namaBulan[$this->bulan] ?? 'Januari',
            'tahun' => $this->tahun,
            'rows' => $rows,
            'total' => [
                'wni_l' => $totalWniL,
                'wni_p' => $totalWniP,
                'wni_total' => $grandWniTotal,
                'luar_l' => $totalLuarL,
                'luar_p' => $totalLuarP,
                'luar_total' => $grandLuarTotal,
                'wna_l' => $totalWnaL,
                'wna_p' => $totalWnaP,
                'wna_total' => $grandWnaTotal,
                'all_l' => $grandAllL,
                'all_p' => $grandAllP,
                'all_total' => $grandAllTotal,
            ],
            'age_ranges' => $ageRangesData,
            'agama_data' => $agamaData,
            'suku_data' => $sukuData,
            'pendidikan_data' => $pendidikanData,
            'pekerjaan_data' => $pekerjaanData,
            'status_kawin_data' => $statusKawinData,
            'total_penduduk_aktif' => $totalPendudukAktif,
        ];
    }
}
