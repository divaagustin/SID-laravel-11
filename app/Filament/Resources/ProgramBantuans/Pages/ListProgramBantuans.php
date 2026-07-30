<?php

namespace App\Filament\Resources\ProgramBantuans\Pages;

use App\Filament\Resources\ProgramBantuans\ProgramBantuanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProgramBantuans extends ListRecords
{
    protected static string $resource = ProgramBantuanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
