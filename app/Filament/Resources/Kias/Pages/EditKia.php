<?php

namespace App\Filament\Resources\Kias\Pages;

use App\Filament\Resources\Kias\KiaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKia extends EditRecord
{
    protected static string $resource = KiaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
