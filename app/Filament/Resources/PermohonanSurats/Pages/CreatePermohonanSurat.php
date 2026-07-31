<?php

namespace App\Filament\Resources\PermohonanSurats\Pages;

use App\Filament\Resources\PermohonanSurats\PermohonanSuratResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePermohonanSurat extends CreateRecord
{
    protected static string $resource = PermohonanSuratResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['config_id'] = $data['config_id'] ?? 1;
        $data['alasan'] = $data['alasan'] ?? '-';
        $data['keterangan'] = $data['keterangan'] ?? '-';

        return $data;
    }
}
