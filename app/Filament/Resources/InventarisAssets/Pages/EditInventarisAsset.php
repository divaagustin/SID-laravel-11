<?php

namespace App\Filament\Resources\InventarisAssets\Pages;

use App\Filament\Resources\InventarisAssets\InventarisAssetResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInventarisAsset extends EditRecord
{
    protected static string $resource = InventarisAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
