<?php

namespace App\Filament\Resources\Lokasis\Pages;

use App\Filament\Resources\Lokasis\LokasiResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLokasi extends CreateRecord
{
    protected static string $resource = LokasiResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['config_id'] = $data['config_id'] ?? 1;
        $data['desk'] = $data['desk'] ?? '-';

        return $data;
    }
}
