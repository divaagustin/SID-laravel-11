<?php

namespace App\Filament\Resources\DokumenPubliks\Pages;

use App\Filament\Resources\DokumenPubliks\DokumenPublikResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDokumenPubliks extends ListRecords
{
    protected static string $resource = DokumenPublikResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
