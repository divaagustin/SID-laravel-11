<?php

namespace App\Filament\Resources\Areas;

use App\Filament\Resources\Areas\Pages;
use App\Filament\Resources\Areas\Schemas\AreaForm;
use App\Filament\Resources\Areas\Tables\AreaTable;
use App\Models\Area;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AreaResource extends Resource
{
    protected static ?string $modelLabel = 'Area GIS';
    protected static ?string $pluralModelLabel = 'Area GIS Wilayah';

    protected static ?string $model = Area::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-square-3-stack-3d';
    protected static ?string $navigationLabel = 'Batas Area & Polygon (GIS)';
    protected static string|\UnitEnum|null $navigationGroup = 'Pertanahan & GIS';
    protected static ?int $navigationSort = 4;
            protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return AreaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AreaTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAreas::route('/'),
            'create' => Pages\CreateArea::route('/create'),
            'edit'   => Pages\EditArea::route('/{record}/edit'),
        ];
    }
}
