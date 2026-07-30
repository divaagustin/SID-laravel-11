<?php

namespace App\Filament\Resources\Artikels;

use App\Filament\Resources\Artikels\Pages;
use App\Filament\Resources\Artikels\Schemas\ArtikelForm;
use App\Filament\Resources\Artikels\Tables\ArtikelTable;
use App\Models\Artikel;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ArtikelResource extends Resource
{
    protected static ?string $modelLabel = 'Artikel Berita';
    protected static ?string $pluralModelLabel = 'Artikel & Berita';

    protected static ?string $model = Artikel::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationLabel = 'Berita & Artikel';
    protected static string|\UnitEnum|null $navigationGroup = 'Admin Web & Pengaduan';
    protected static ?int $navigationSort = 1;
            protected static ?string $recordTitleAttribute = 'judul';

    public static function form(Schema $schema): Schema
    {
        return ArtikelForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ArtikelTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListArtikels::route('/'),
            'create' => Pages\CreateArtikel::route('/create'),
            'edit'   => Pages\EditArtikel::route('/{record}/edit'),
        ];
    }
}
