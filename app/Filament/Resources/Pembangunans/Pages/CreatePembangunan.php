<?php

namespace App\Filament\Resources\Pembangunans\Pages;

use App\Filament\Resources\Pembangunans\PembangunanResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePembangunan extends CreateRecord
{
    protected static string $resource = PembangunanResource::class;
    protected static ?string $title = 'Tambah Proyek Pembangunan Baru';
}
