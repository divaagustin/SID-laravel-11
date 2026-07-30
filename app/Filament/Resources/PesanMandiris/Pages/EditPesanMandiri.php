<?php

namespace App\Filament\Resources\PesanMandiris\Pages;

use App\Filament\Resources\PesanMandiris\PesanMandiriResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPesanMandiri extends EditRecord
{
    protected static string $resource = PesanMandiriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
