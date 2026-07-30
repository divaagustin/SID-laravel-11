<?php

namespace App\Filament\Resources\ProgramBantuans\Pages;

use App\Filament\Resources\ProgramBantuans\ProgramBantuanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProgramBantuan extends EditRecord
{
    protected static string $resource = ProgramBantuanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
