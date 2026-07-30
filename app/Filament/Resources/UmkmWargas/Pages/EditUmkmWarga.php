<?php

namespace App\Filament\Resources\UmkmWargas\Pages;

use App\Filament\Resources\UmkmWargas\UmkmWargaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUmkmWarga extends EditRecord
{
    protected static string $resource = UmkmWargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
