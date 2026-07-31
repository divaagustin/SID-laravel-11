<?php

namespace App\Filament\Resources\SyaratSurats\Pages;

use App\Filament\Resources\SyaratSurats\SyaratSuratResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSyaratSurat extends CreateRecord
{
    protected static string $resource = SyaratSuratResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['config_id'] = $data['config_id'] ?? 1;

        return $data;
    }
}
