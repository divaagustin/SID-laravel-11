<?php

namespace App\Filament\Resources\Keluargas\Pages;

use App\Filament\Resources\Keluargas\KeluargaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKeluarga extends CreateRecord
{
    protected static string $resource = KeluargaResource::class;
    protected static ?string $title = 'Tambah Kartu Keluarga Baru';
}
