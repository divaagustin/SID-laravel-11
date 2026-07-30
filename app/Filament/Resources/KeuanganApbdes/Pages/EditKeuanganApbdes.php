<?php

namespace App\Filament\Resources\KeuanganApbdes\Pages;

use App\Filament\Resources\KeuanganApbdes\KeuanganApbdesResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKeuanganApbdes extends EditRecord
{
    protected static string $resource = KeuanganApbdesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
