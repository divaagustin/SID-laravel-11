<?php

namespace App\Filament\Resources\Cdesas\Pages;

use App\Filament\Resources\Cdesas\CdesaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCdesa extends EditRecord
{
    protected static string $resource = CdesaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
