<?php

namespace App\Filament\Resources\Pembangunans\Pages;

use App\Filament\Resources\Pembangunans\PembangunanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPembangunan extends EditRecord
{
    protected static string $resource = PembangunanResource::class;
    protected static ?string $title = 'Ubah Data Proyek Pembangunan';

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Hapus Proyek'),
        ];
    }
}
