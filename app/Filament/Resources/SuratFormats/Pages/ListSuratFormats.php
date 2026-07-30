<?php

namespace App\Filament\Resources\SuratFormats\Pages;

use App\Filament\Resources\SuratFormats\SuratFormatResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSuratFormats extends ListRecords
{
    protected static string $resource = SuratFormatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
