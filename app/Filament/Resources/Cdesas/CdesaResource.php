<?php

namespace App\Filament\Resources\Cdesas;

use App\Filament\Resources\Cdesas\Pages\CreateCdesa;
use App\Filament\Resources\Cdesas\Pages\EditCdesa;
use App\Filament\Resources\Cdesas\Pages\ListCdesas;
use App\Filament\Resources\Cdesas\Schemas\CdesaForm;
use App\Filament\Resources\Cdesas\Tables\CdesasTable;
use App\Models\Cdesa;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CdesaResource extends Resource
{
    protected static ?string $modelLabel = 'C-Desa';
    protected static ?string $pluralModelLabel = 'Buku C-Desa';

    protected static ?string $model = Cdesa::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';
    protected static string|\UnitEnum|null $navigationGroup = 'Pertanahan & GIS';
    protected static ?string $navigationLabel = 'Buku C-Desa';
    protected static ?string $title = 'Buku Register C-Desa Pertanahan';
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'nomor';

    public static function form(Schema $schema): Schema
    {
        return CdesaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CdesasTable::configure($table);
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
            'index' => ListCdesas::route('/'),
            'create' => CreateCdesa::route('/create'),
            'edit' => EditCdesa::route('/{record}/edit'),
        ];
    }
}
