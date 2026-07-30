<?php

namespace App\Filament\Resources\SuratMasuks;

use App\Filament\Resources\SuratMasuks\Pages;
use App\Filament\Resources\SuratMasuks\Schemas\SuratMasukForm;
use App\Filament\Resources\SuratMasuks\Tables\SuratMasukTable;
use App\Models\SuratMasuk;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SuratMasukResource extends Resource
{
    protected static ?string $modelLabel = 'Surat Masuk';
    protected static ?string $pluralModelLabel = 'Surat Masuk';

    protected static ?string $model = SuratMasuk::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-down-left';
    protected static ?string $navigationLabel = 'Surat Masuk';
    protected static string|\UnitEnum|null $navigationGroup = 'Layanan Persuratan';
    protected static ?int $navigationSort = 4;
            protected static ?string $recordTitleAttribute = 'nomor_surat';

    public static function form(Schema $schema): Schema
    {
        return SuratMasukForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SuratMasukTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSuratMasuks::route('/'),
            'create' => Pages\CreateSuratMasuk::route('/create'),
            'view'   => Pages\ViewSuratMasuk::route('/{record}'),
            'edit'   => Pages\EditSuratMasuk::route('/{record}/edit'),
        ];
    }
}
