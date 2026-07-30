<?php

namespace App\Filament\Widgets;

use App\Models\KeuanganApbdes;
use Filament\Widgets\ChartWidget;

class ApbdesTransparansiWidget extends ChartWidget
{
    protected static ?int $sort = 3;

    public function getHeading(): ?string
    {
        return '💰 Transparansi Anggaran APBDes (Tahun Berjalan)';
    }

    protected function getData(): array
    {
        $apbdes = KeuanganApbdes::orderByDesc('tahun')->first();

        $anggaran = $apbdes->anggaran ?? 1500000000;
        $realisasi = $apbdes->realisasi ?? 1150000000;
        $sisa = max(0, $anggaran - $realisasi);

        return [
            'datasets' => [
                [
                    'label' => 'Anggaran APBDes (Rp)',
                    'data' => [$realisasi, $sisa],
                    'backgroundColor' => [
                        '#10b981',
                        '#f59e0b',
                    ],
                    'hoverOffset' => 4,
                ],
            ],
            'labels' => [
                'Realisasi Penyerapan (Rp ' . number_format($realisasi, 0, ',', '.') . ')',
                'Sisa Pagu Anggaran (Rp ' . number_format($sisa, 0, ',', '.') . ')',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
