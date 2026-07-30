<?php

namespace App\Filament\Resources\Keluargas\Pages;

use App\Filament\Resources\Keluargas\KeluargaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKeluargas extends ListRecords
{
    protected static string $resource = KeluargaResource::class;
    protected static ?string $title = 'Daftar Kartu Keluarga (KK)';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Kartu Keluarga Baru'),
        ];
    }
}
