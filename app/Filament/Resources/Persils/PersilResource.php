<?php

namespace App\Filament\Resources\Persils;

use App\Filament\Resources\Persils\Pages\CreatePersil;
use App\Filament\Resources\Persils\Pages\EditPersil;
use App\Filament\Resources\Persils\Pages\ListPersils;
use App\Filament\Resources\Persils\Schemas\PersilForm;
use App\Filament\Resources\Persils\Tables\PersilsTable;
use App\Models\Persil;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PersilResource extends Resource
{
    protected static ?string $modelLabel = 'Persil';
    protected static ?string $pluralModelLabel = 'Data Persil Tanah';

    protected static ?string $model = Persil::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';
    protected static string|\UnitEnum|null $navigationGroup = 'Pertanahan & GIS';
    protected static ?string $navigationLabel = 'Data Persil Tanah';
    protected static ?string $title = 'Data Persil Pertanahan Desa';
    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'nomor';

    public static function form(Schema $schema): Schema
    {
        return PersilForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PersilsTable::configure($table);
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
            'index' => ListPersils::route('/'),
            'create' => CreatePersil::route('/create'),
            'edit' => EditPersil::route('/{record}/edit'),
        ];
    }
}
