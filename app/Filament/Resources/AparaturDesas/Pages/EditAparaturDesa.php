<?php

namespace App\Filament\Resources\AparaturDesas\Pages;

use App\Filament\Resources\AparaturDesas\AparaturDesaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAparaturDesa extends EditRecord
{
    protected static string $resource = AparaturDesaResource::class;
    protected static ?string $title = 'Ubah Data Aparatur Desa';

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Hapus Data'),
        ];
    }
}
