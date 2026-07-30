<?php

namespace App\Filament\Resources\Wilayahs\Pages;

use App\Filament\Resources\Wilayahs\WilayahResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWilayah extends CreateRecord
{
    protected static string $resource = WilayahResource::class;
    protected static ?string $title = 'Tambah Wilayah Dusun Baru';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['config_id'] = $data['config_id'] ?? 1;

        return $data;
    }
}
