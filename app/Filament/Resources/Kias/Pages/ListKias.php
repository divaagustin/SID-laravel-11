<?php

namespace App\Filament\Resources\Kias\Pages;

use App\Filament\Resources\Kias\KiaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKias extends ListRecords
{
    protected static string $resource = KiaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
