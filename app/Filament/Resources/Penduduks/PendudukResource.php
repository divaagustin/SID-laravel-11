<?php

namespace App\Filament\Resources\Penduduks;

use App\Filament\Resources\Penduduks\Pages\CreatePenduduk;
use App\Filament\Resources\Penduduks\Pages\EditPenduduk;
use App\Filament\Resources\Penduduks\Pages\ListPenduduks;
use App\Filament\Resources\Penduduks\Pages\ViewPenduduk;
use App\Filament\Resources\Penduduks\Schemas\PendudukForm;
use App\Filament\Resources\Penduduks\Schemas\PendudukInfolist;
use App\Filament\Resources\Penduduks\Tables\PenduduksTable;
use App\Models\Penduduk;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PendudukResource extends Resource
{
    protected static ?string $modelLabel = 'Penduduk';
    protected static ?string $pluralModelLabel = 'Data Penduduk';

    protected static ?string $model = Penduduk::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Data Penduduk';

    protected static string|\UnitEnum|null $navigationGroup = 'Kependudukan';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'nama';

    public static function getNavigationBadge(): ?string
    {
        $count = \Illuminate\Support\Facades\Cache::remember('nav_badge_penduduk_count', 60, fn () => static::getModel()::count());
        return number_format($count, 0, ',', '.');
    }

    public static function form(Schema $schema): Schema
    {
        return PendudukForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PendudukInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PenduduksTable::configure($table);
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
            'index' => ListPenduduks::route('/'),
            'create' => CreatePenduduk::route('/create'),
            'view' => ViewPenduduk::route('/{record}'),
            'edit' => EditPenduduk::route('/{record}/edit'),
        ];
    }
}
