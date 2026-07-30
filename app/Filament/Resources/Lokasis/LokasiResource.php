<?php

namespace App\Filament\Resources\Lokasis;

use App\Filament\Resources\Lokasis\Pages;
use App\Filament\Resources\Lokasis\Schemas\LokasiForm;
use App\Filament\Resources\Lokasis\Tables\LokasiTable;
use App\Models\Lokasi;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LokasiResource extends Resource
{
    protected static ?string $modelLabel = 'Lokasi Fasilitas';
    protected static ?string $pluralModelLabel = 'Lokasi & Fasilitas GIS';

    protected static ?string $model = Lokasi::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationLabel = 'Lokasi & Fasilitas (GIS)';
    protected static string|\UnitEnum|null $navigationGroup = 'Pertanahan & GIS';
    protected static ?int $navigationSort = 3;
            protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return LokasiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LokasiTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListLokasis::route('/'),
            'create' => Pages\CreateLokasi::route('/create'),
            'edit'   => Pages\EditLokasi::route('/{record}/edit'),
        ];
    }
}
