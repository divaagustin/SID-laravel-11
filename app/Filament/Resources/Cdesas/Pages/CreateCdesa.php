<?php

namespace App\Filament\Resources\Cdesas\Pages;

use App\Filament\Resources\Cdesas\CdesaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCdesa extends CreateRecord
{
    protected static string $resource = CdesaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['config_id'] = $data['config_id'] ?? 1;

        if (empty($data['nama_kepemilikan'])) {
            if (!empty($data['nama_pemilik_luar'])) {
                $data['nama_kepemilikan'] = $data['nama_pemilik_luar'];
            } else {
                $data['nama_kepemilikan'] = 'C-Desa No. ' . ($data['nomor'] ?? '-');
            }
        }

        return $data;
    }
}
