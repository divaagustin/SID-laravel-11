<?php

namespace App\Filament\Resources\PermohonanSurats;

use App\Filament\Resources\PermohonanSurats\Pages;
use App\Filament\Resources\PermohonanSurats\Schemas\PermohonanSuratForm;
use App\Filament\Resources\PermohonanSurats\Tables\PermohonanSuratTable;
use App\Models\PermohonanSurat;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PermohonanSuratResource extends Resource
{
    protected static ?string $modelLabel = 'Permohonan Surat';
    protected static ?string $pluralModelLabel = 'Permohonan Surat Online';

    protected static ?string $model = PermohonanSurat::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInbox;
    protected static ?string $navigationLabel = 'Permohonan Surat Warga';
    protected static string|\UnitEnum|null $navigationGroup = 'Layanan Persuratan';
    protected static ?int $navigationSort = 4;
            protected static ?string $recordTitleAttribute = 'no_antrian';

    public static function getNavigationBadge(): ?string
    {
        $count = \Illuminate\Support\Facades\Cache::remember('nav_badge_permohonan_surat_count', 30, fn () => PermohonanSurat::where('status', 0)->count());

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return PermohonanSuratForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PermohonanSuratTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPermohonanSurats::route('/'),
            'create' => Pages\CreatePermohonanSurat::route('/create'),
            'edit'   => Pages\EditPermohonanSurat::route('/{record}/edit'),
        ];
    }
}
