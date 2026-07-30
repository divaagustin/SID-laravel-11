<?php

namespace App\Filament\Resources\Penduduks\Pages;

use App\Filament\Resources\Penduduks\PendudukResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPenduduk extends EditRecord
{
    protected static string $resource = PendudukResource::class;
    protected static ?string $title = 'Ubah Data Penduduk';

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()->label('Lihat Detail'),
            DeleteAction::make()->label('Hapus Penduduk'),
        ];
    }
}
