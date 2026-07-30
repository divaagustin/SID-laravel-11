<?php

namespace App\Filament\Resources\Galeris;

use App\Filament\Resources\Galeris\Pages;
use App\Filament\Resources\Galeris\Schemas\GaleriForm;
use App\Filament\Resources\Galeris\Tables\GaleriTable;
use App\Models\Galeri;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GaleriResource extends Resource
{
    protected static ?string $modelLabel = 'Galeri Foto';
    protected static ?string $pluralModelLabel = 'Galeri Foto Kegiatan';

    protected static ?string $model = Galeri::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Galeri Foto';
    protected static string|\UnitEnum|null $navigationGroup = 'Admin Web & Pengaduan';
    protected static ?int $navigationSort = 3;
            protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return GaleriForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GaleriTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListGaleris::route('/'),
            'create' => Pages\CreateGaleri::route('/create'),
            'edit'   => Pages\EditGaleri::route('/{record}/edit'),
        ];
    }
}
