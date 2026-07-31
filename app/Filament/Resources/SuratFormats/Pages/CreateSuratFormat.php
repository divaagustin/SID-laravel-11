<?php

namespace App\Filament\Resources\SuratFormats\Pages;

use App\Filament\Resources\SuratFormats\SuratFormatResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSuratFormat extends CreateRecord
{
    protected static string $resource = SuratFormatResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['config_id'] = $data['config_id'] ?? 1;

        if (array_key_exists('form_isian_visual', $data)) {
            $data['form_isian'] = ! empty($data['form_isian_visual']) ? json_encode($data['form_isian_visual']) : null;
            unset($data['form_isian_visual']);
        }

        return $data;
    }
}
