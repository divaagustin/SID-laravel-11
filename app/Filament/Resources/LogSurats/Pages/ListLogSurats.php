<?php

namespace App\Filament\Resources\LogSurats\Pages;

use App\Filament\Resources\LogSurats\LogSuratResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLogSurats extends ListRecords
{
    protected static string $resource = LogSuratResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
