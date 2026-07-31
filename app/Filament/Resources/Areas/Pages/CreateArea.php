<?php

namespace App\Filament\Resources\Areas\Pages;

use App\Filament\Resources\Areas\AreaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateArea extends CreateRecord
{
    protected static string $resource = AreaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['config_id'] = $data['config_id'] ?? 1;
        $data['desk'] = $data['desk'] ?? '-';

        return $data;
    }
}
