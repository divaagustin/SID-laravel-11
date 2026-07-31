<?php

namespace App\Filament\Resources\Kelompoks\Pages;

use App\Filament\Resources\Kelompoks\KelompokResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKelompok extends CreateRecord
{
    protected static string $resource = KelompokResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['config_id'] = $data['config_id'] ?? 1;
        $data['id_master'] = $data['id_master'] ?? 1;
        $data['keterangan'] = $data['keterangan'] ?? '-';

        return $data;
    }
}
