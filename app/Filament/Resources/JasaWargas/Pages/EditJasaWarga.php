<?php

namespace App\Filament\Resources\JasaWargas\Pages;

use App\Filament\Resources\JasaWargas\JasaWargaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJasaWarga extends EditRecord
{
    protected static string $resource = JasaWargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
