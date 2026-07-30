<?php

namespace App\Filament\Resources\Kelompoks\Pages;

use App\Filament\Resources\Kelompoks\KelompokResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKelompok extends EditRecord
{
    protected static string $resource = KelompokResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
