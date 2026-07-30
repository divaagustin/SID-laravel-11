<?php

namespace App\Filament\Resources\AparaturDesas\Pages;

use App\Filament\Resources\AparaturDesas\AparaturDesaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAparaturDesa extends CreateRecord
{
    protected static string $resource = AparaturDesaResource::class;
    protected static ?string $title = 'Tambah Aparatur Desa';
}
