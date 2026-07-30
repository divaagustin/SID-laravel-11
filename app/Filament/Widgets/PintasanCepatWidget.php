<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class PintasanCepatWidget extends Widget
{
    protected string $view = 'filament.widgets.pintasan-cepat-widget';

    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = 1;
}
