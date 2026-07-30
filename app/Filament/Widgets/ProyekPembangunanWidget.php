<?php

namespace App\Filament\Widgets;

use App\Models\Pembangunan;
use Filament\Widgets\Widget;

class ProyekPembangunanWidget extends Widget
{
    protected string $view = 'filament.widgets.proyek-pembangunan-widget';

    protected static ?int $sort = 7;

    protected int | string | array $columnSpan = 1;

    public function getViewData(): array
    {
        $proyeks = Pembangunan::latest()->take(3)->get();

        return [
            'proyeks' => $proyeks,
        ];
    }
}
