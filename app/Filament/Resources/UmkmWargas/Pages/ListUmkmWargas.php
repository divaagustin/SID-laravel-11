<?php

namespace App\Filament\Resources\UmkmWargas\Pages;

use App\Filament\Resources\UmkmWargas\UmkmWargaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUmkmWargas extends ListRecords
{
    protected static string $resource = UmkmWargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah UMKM Warga'),
        ];
    }
}
