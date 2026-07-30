<?php

namespace App\Filament\Resources\ProdukDesas;

use App\Filament\Resources\ProdukDesas\Pages\CreateProdukDesa;
use App\Filament\Resources\ProdukDesas\Pages\EditProdukDesa;
use App\Filament\Resources\ProdukDesas\Pages\ListProdukDesas;
use App\Filament\Resources\ProdukDesas\Schemas\ProdukDesaForm;
use App\Filament\Resources\ProdukDesas\Tables\ProdukDesasTable;
use App\Models\ProdukDesa;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProdukDesaResource extends Resource
{
    protected static ?string $modelLabel = 'Produk UMKM';
    protected static ?string $pluralModelLabel = 'Lapak BUMDes & UMKM';

    protected static ?string $model = ProdukDesa::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shopping-bag';
    protected static string|\UnitEnum|null $navigationGroup = 'Pembangunan, Aset & BUMDes';
    protected static ?string $navigationLabel = 'Lapak & BUMDes';
    protected static ?string $title = 'Produk Usaha Warga & BUMDes';
    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return ProdukDesaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProdukDesasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProdukDesas::route('/'),
            'create' => CreateProdukDesa::route('/create'),
            'edit' => EditProdukDesa::route('/{record}/edit'),
        ];
    }
}
