<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class GrafikSuratBansosWidget extends ChartWidget
{
    protected static ?int $sort = 4;
    protected static bool $isLazy = true;

    public function getHeading(): ?string
    {
        return '📈 Trend Permohonan Surat Online per Bulan';
    }

    protected function getData(): array
    {
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'];
        $suratData = [12, 19, 25, 32, 28, 45, 50];

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Permohonan Surat',
                    'data' => $suratData,
                    'borderColor' => '#059669',
                    'backgroundColor' => 'rgba(5, 150, 105, 0.15)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
