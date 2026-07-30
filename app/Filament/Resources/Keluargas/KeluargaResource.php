<?php

namespace App\Filament\Resources\Keluargas;

use App\Filament\Resources\Keluargas\Pages\CreateKeluarga;
use App\Filament\Resources\Keluargas\Pages\EditKeluarga;
use App\Filament\Resources\Keluargas\Pages\ListKeluargas;
use App\Filament\Resources\Keluargas\Schemas\KeluargaForm;
use App\Filament\Resources\Keluargas\Tables\KeluargasTable;
use App\Models\Keluarga;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KeluargaResource extends Resource
{
    protected static ?string $modelLabel = 'Kepala Keluarga';
    protected static ?string $pluralModelLabel = 'Data Keluarga (KK)';

    protected static ?string $model = Keluarga::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home-modern';

    protected static ?string $navigationLabel = 'Kartu Keluarga';

    protected static string|\UnitEnum|null $navigationGroup = 'Kependudukan';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'no_kk';

    public static function getNavigationBadge(): ?string
    {
        $count = \Illuminate\Support\Facades\Cache::remember('nav_badge_keluarga_count', 60, fn () => static::getModel()::count());
        return number_format($count, 0, ',', '.');
    }

    public static function form(Schema $schema): Schema
    {
        return KeluargaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KeluargasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AnggotaRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKeluargas::route('/'),
            'create' => CreateKeluarga::route('/create'),
            'edit' => EditKeluarga::route('/{record}/edit'),
        ];
    }
}
