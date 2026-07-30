<?php

namespace App\Filament\Resources\Wilayahs\Pages;

use App\Filament\Resources\Wilayahs\WilayahResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWilayahs extends ListRecords
{
    protected static string $resource = WilayahResource::class;
    protected static ?string $title = 'Daftar Wilayah Administrasi (Dusun/RW/RT)';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Wilayah Baru'),
        ];
    }
}
