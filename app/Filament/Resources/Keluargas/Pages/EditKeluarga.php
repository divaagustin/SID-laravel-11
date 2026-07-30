<?php

namespace App\Filament\Resources\Keluargas\Pages;

use App\Filament\Resources\Keluargas\KeluargaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKeluarga extends EditRecord
{
    protected static string $resource = KeluargaResource::class;
    protected static ?string $title = 'Ubah Data Kartu Keluarga';

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Hapus KK'),
        ];
    }
}
