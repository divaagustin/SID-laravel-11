<?php

namespace App\Filament\Resources\LogSurats\Pages;

use App\Filament\Resources\LogSurats\LogSuratResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLogSurat extends EditRecord
{
    protected static string $resource = LogSuratResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
