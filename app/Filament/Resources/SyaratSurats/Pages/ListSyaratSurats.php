<?php

namespace App\Filament\Resources\SyaratSurats\Pages;

use App\Filament\Resources\SyaratSurats\SyaratSuratResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSyaratSurats extends ListRecords
{
    protected static string $resource = SyaratSuratResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
