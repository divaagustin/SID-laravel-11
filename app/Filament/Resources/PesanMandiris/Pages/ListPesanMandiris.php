<?php

namespace App\Filament\Resources\PesanMandiris\Pages;

use App\Filament\Resources\PesanMandiris\PesanMandiriResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPesanMandiris extends ListRecords
{
    protected static string $resource = PesanMandiriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
