<?php

namespace App\Filament\Resources\KeuanganApbdes\Pages;

use App\Filament\Resources\KeuanganApbdes\KeuanganApbdesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKeuanganApbdes extends ListRecords
{
    protected static string $resource = KeuanganApbdesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
