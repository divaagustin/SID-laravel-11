<?php

namespace App\Filament\Resources\Persils\Pages;

use App\Filament\Resources\Persils\PersilResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPersils extends ListRecords
{
    protected static string $resource = PersilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
