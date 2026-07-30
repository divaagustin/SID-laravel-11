<?php

namespace App\Filament\Resources\Galeris\Pages;

use App\Filament\Resources\Galeris\GaleriResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGaleris extends ListRecords
{
    protected static string $resource = GaleriResource::class;
    protected static ?string $title = 'Daftar Galeri Foto & Kegiatan Desa';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Foto Galeri'),
        ];
    }
}
