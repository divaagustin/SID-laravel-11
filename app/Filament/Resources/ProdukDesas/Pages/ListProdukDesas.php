<?php

namespace App\Filament\Resources\ProdukDesas\Pages;

use App\Filament\Resources\ProdukDesas\ProdukDesaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProdukDesas extends ListRecords
{
    protected static string $resource = ProdukDesaResource::class;
    protected static ?string $title = 'Daftar Produk Lapak UMKM Desa';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Produk UMKM Baru'),
        ];
    }
}
