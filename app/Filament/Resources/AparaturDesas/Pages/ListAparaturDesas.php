<?php

namespace App\Filament\Resources\AparaturDesas\Pages;

use App\Filament\Resources\AparaturDesas\AparaturDesaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAparaturDesas extends ListRecords
{
    protected static string $resource = AparaturDesaResource::class;
    protected static ?string $title = 'Daftar Aparatur / Perangkat Desa';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Aparatur Desa'),
        ];
    }
}
