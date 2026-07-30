<?php

namespace App\Filament\Resources\ProdukDesas\Pages;

use App\Filament\Resources\ProdukDesas\ProdukDesaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProdukDesa extends EditRecord
{
    protected static string $resource = ProdukDesaResource::class;
    protected static ?string $title = 'Ubah Data Produk UMKM';

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Hapus Produk'),
        ];
    }
}
