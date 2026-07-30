<?php

namespace App\Filament\Resources\Penduduks\Pages;

use App\Filament\Resources\Penduduks\PendudukResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePenduduk extends CreateRecord
{
    protected static string $resource = PendudukResource::class;
    protected static ?string $title = 'Tambah Penduduk Baru';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['config_id'] = $data['config_id'] ?? 1;
        $data['dokumen_pasport'] = $data['dokumen_pasport'] ?? '-';
        $data['dokumen_kitas'] = $data['dokumen_kitas'] ?? '-';
        $data['warganegara_id'] = $data['warganegara_id'] ?? 1;
        $data['nama_ayah'] = $data['nama_ayah'] ?? '-';
        $data['nama_ibu'] = $data['nama_ibu'] ?? '-';

        return $data;
    }
}
