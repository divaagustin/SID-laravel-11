<?php

namespace App\Filament\Resources\DokumenPubliks\Pages;

use App\Filament\Resources\DokumenPubliks\DokumenPublikResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDokumenPublik extends EditRecord
{
    protected static string $resource = DokumenPublikResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
