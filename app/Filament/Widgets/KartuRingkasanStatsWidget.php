<?php

namespace App\Filament\Widgets;

use App\Models\LogSurat;
use App\Models\PesanMandiri;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class KartuRingkasanStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // 1. Total Penduduk & KK
        $totalPenduduk = DB::table('tweb_penduduk')->where('status_dasar', 1)->count();
        $totalKk       = DB::table('tweb_keluarga')->count();

        // 2. Surat Pending Approval / TTE
        $pendingSurat = LogSurat::whereIn('status', ['pending', 'proses'])->count();

        // 3. Pengaduan Warga Baru (Belum ditanggapi)
        $pengaduanBaru = PesanMandiri::whereIn('status', ['unread', 'baru'])->count();

        // 4. Status Desa IDM & Config
        $config = DB::table('config')->first();
        $statusDesa = $config->status_desa ?? 'Desa Mandiri';
        $luasWilayah = number_format($config->luas_desa ?? 450, 0, ',', '.') . ' Ha';

        return [
            Stat::make('Penduduk & KK', number_format($totalPenduduk) . ' Jiwa')
                ->description($totalKk . ' Kepala Keluarga (KK)')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success')
                ->chart([$totalPenduduk - 50, $totalPenduduk - 30, $totalPenduduk - 10, $totalPenduduk]),

            Stat::make('Permohonan Surat Pending', $pendingSurat . ' Surat')
                ->description($pendingSurat > 0 ? 'Menunggu verifikasi Sekdes/Kades' : 'Semua surat telah diproses')
                ->descriptionIcon($pendingSurat > 0 ? 'heroicon-m-clock' : 'heroicon-m-check-badge')
                ->color($pendingSurat > 0 ? 'warning' : 'success'),

            Stat::make('Pengaduan Warga', $pengaduanBaru . ' Laporan')
                ->description($pengaduanBaru > 0 ? 'Perlu tanggapan admin' : 'Tidak ada laporan baru')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color($pengaduanBaru > 0 ? 'danger' : 'info'),

            Stat::make('Status IDM Desa', $statusDesa)
                ->description('Luas Wilayah: ' . $luasWilayah)
                ->descriptionIcon('heroicon-m-map')
                ->color('amber'),
        ];
    }
}
