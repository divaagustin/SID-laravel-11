<?php

namespace App\Filament\Resources\SuratFormats;

use App\Filament\Resources\SuratFormats\Pages\CreateSuratFormat;
use App\Filament\Resources\SuratFormats\Pages\EditSuratFormat;
use App\Filament\Resources\SuratFormats\Pages\ListSuratFormats;
use App\Filament\Resources\SuratFormats\Schemas\SuratFormatForm;
use App\Filament\Resources\SuratFormats\Tables\SuratFormatsTable;
use App\Models\SuratFormat;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use App\Enums\UserRole;
use Filament\Tables\Table;

class SuratFormatResource extends Resource
{
    protected static ?string $modelLabel = 'Format Surat';
    protected static ?string $pluralModelLabel = 'Master Format Surat';

    protected static ?string $model = SuratFormat::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Format Surat Desa';

    protected static string|\UnitEnum|null $navigationGroup = 'Layanan Persuratan';

    protected static ?int $navigationSort = 6;

    protected static ?string $recordTitleAttribute = 'nama';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyRole(UserRole::Administrator, UserRole::Operator) ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return SuratFormatForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SuratFormatsTable::configure($table);
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
            'index' => ListSuratFormats::route('/'),
            'create' => CreateSuratFormat::route('/create'),
            'edit' => EditSuratFormat::route('/{record}/edit'),
        ];
    }
}
