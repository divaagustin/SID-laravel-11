<?php

namespace App\Filament\Resources\InventarisAssets\Pages;

use App\Filament\Resources\InventarisAssets\InventarisAssetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInventarisAssets extends ListRecords
{
    protected static string $resource = InventarisAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
