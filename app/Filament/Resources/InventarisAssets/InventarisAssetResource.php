<?php

namespace App\Filament\Resources\InventarisAssets;

use App\Filament\Resources\InventarisAssets\Pages\CreateInventarisAsset;
use App\Filament\Resources\InventarisAssets\Pages\EditInventarisAsset;
use App\Filament\Resources\InventarisAssets\Pages\ListInventarisAssets;
use App\Filament\Resources\InventarisAssets\Schemas\InventarisAssetForm;
use App\Filament\Resources\InventarisAssets\Tables\InventarisAssetsTable;
use App\Models\InventarisAsset;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InventarisAssetResource extends Resource
{
    protected static ?string $modelLabel = 'Aset Desa';
    protected static ?string $pluralModelLabel = 'Inventaris & Aset Desa';

    protected static ?string $model = InventarisAsset::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-archive-box';
    protected static string|\UnitEnum|null $navigationGroup = 'Pembangunan, Aset & BUMDes';
    protected static ?string $navigationLabel = 'Inventaris & Aset Desa';
    protected static ?string $title = 'Inventaris & Aset Milik Desa';
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'nama_barang';

    public static function form(Schema $schema): Schema
    {
        return InventarisAssetForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InventarisAssetsTable::configure($table);
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
            'index' => ListInventarisAssets::route('/'),
            'create' => CreateInventarisAsset::route('/create'),
            'edit' => EditInventarisAsset::route('/{record}/edit'),
        ];
    }
}
