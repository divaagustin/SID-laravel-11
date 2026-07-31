<?php

namespace App\Filament\Resources\InventarisAssets\Pages;

use App\Filament\Resources\InventarisAssets\InventarisAssetResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInventarisAsset extends CreateRecord
{
    protected static string $resource = InventarisAssetResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['config_id'] = $data['config_id'] ?? 1;
        $data['keterangan'] = $data['keterangan'] ?? '-';

        return $data;
    }
}
