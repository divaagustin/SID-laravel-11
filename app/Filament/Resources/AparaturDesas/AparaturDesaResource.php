<?php

namespace App\Filament\Resources\AparaturDesas;

use App\Filament\Resources\AparaturDesas\Pages\CreateAparaturDesa;
use App\Filament\Resources\AparaturDesas\Pages\EditAparaturDesa;
use App\Filament\Resources\AparaturDesas\Pages\ListAparaturDesas;
use App\Filament\Resources\AparaturDesas\Schemas\AparaturDesaForm;
use App\Filament\Resources\AparaturDesas\Tables\AparaturDesasTable;
use App\Models\AparaturDesa;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AparaturDesaResource extends Resource
{
    protected static ?string $modelLabel = 'Aparatur Desa';
    protected static ?string $pluralModelLabel = 'Aparatur Desa';

    protected static ?string $model = AparaturDesa::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';
    protected static string|\UnitEnum|null $navigationGroup = 'Info Desa';
    protected static ?string $navigationLabel = 'Aparatur Desa';
    protected static ?string $title = 'Aparatur / Perangkat Desa';
    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'pamong_nama';

    public static function form(Schema $schema): Schema
    {
        return AparaturDesaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AparaturDesasTable::configure($table);
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
            'index' => ListAparaturDesas::route('/'),
            'create' => CreateAparaturDesa::route('/create'),
            'edit' => EditAparaturDesa::route('/{record}/edit'),
        ];
    }
}
