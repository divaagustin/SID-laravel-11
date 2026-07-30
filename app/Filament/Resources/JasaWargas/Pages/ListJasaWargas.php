<?php

namespace App\Filament\Resources\JasaWargas\Pages;

use App\Filament\Resources\JasaWargas\JasaWargaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJasaWargas extends ListRecords
{
    protected static string $resource = JasaWargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Lowongan Jasa'),
        ];
    }
}
