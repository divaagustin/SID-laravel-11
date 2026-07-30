<?php

namespace App\Filament\Resources\ProdukDesas\Pages;

use App\Filament\Resources\ProdukDesas\ProdukDesaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProdukDesa extends CreateRecord
{
    protected static string $resource = ProdukDesaResource::class;
    protected static ?string $title = 'Tambah Produk UMKM Desa Baru';
}
