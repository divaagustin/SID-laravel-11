<?php

namespace App\Filament\Resources\SyaratSurats\Pages;

use App\Filament\Resources\SyaratSurats\SyaratSuratResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSyaratSurat extends EditRecord
{
    protected static string $resource = SyaratSuratResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
