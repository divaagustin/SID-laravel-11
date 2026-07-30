<?php

namespace App\Filament\Resources\Kelompoks\Pages;

use App\Filament\Resources\Kelompoks\KelompokResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKelompoks extends ListRecords
{
    protected static string $resource = KelompokResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
