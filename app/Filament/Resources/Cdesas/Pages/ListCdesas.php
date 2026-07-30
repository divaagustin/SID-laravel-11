<?php

namespace App\Filament\Resources\Cdesas\Pages;

use App\Filament\Resources\Cdesas\CdesaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCdesas extends ListRecords
{
    protected static string $resource = CdesaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
