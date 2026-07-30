<?php

namespace App\Filament\Resources\Persils\Pages;

use App\Filament\Resources\Persils\PersilResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPersil extends EditRecord
{
    protected static string $resource = PersilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
