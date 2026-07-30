<?php

namespace App\Filament\Widgets;

use App\Models\ProdukDesa;
use Filament\Widgets\Widget;

class PotensiBumdesWidget extends Widget
{
    protected string $view = 'filament.widgets.potensi-bumdes-widget';

    protected static ?int $sort = 6;

    protected int | string | array $columnSpan = 1;

    public function getViewData(): array
    {
        $produks = ProdukDesa::where('status', 1)->latest()->take(3)->get();

        return [
            'produks' => $produks,
        ];
    }
}
