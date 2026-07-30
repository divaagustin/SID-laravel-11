<?php

namespace App\Filament\Resources\SyaratSurats;

use App\Filament\Resources\SyaratSurats\Pages\CreateSyaratSurat;
use App\Filament\Resources\SyaratSurats\Pages\EditSyaratSurat;
use App\Filament\Resources\SyaratSurats\Pages\ListSyaratSurats;
use App\Filament\Resources\SyaratSurats\Schemas\SyaratSuratForm;
use App\Filament\Resources\SyaratSurats\Tables\SyaratSuratsTable;
use App\Models\SyaratSurat;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

use App\Enums\UserRole;

class SyaratSuratResource extends Resource
{
    protected static ?string $modelLabel = 'Syarat Surat';
    protected static ?string $pluralModelLabel = 'Syarat Dokumen Surat';

    protected static ?string $model = SyaratSurat::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-check';

    protected static ?string $navigationLabel = 'Syarat Dokumen Surat';

    protected static string|\UnitEnum|null $navigationGroup = 'Layanan Persuratan';

    protected static ?int $navigationSort = 7;

    protected static ?string $recordTitleAttribute = 'ref_syarat_nama';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyRole(UserRole::Administrator, UserRole::Operator) ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return SyaratSuratForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SyaratSuratsTable::configure($table);
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
            'index' => ListSyaratSurats::route('/'),
            'create' => CreateSyaratSurat::route('/create'),
            'edit' => EditSyaratSurat::route('/{record}/edit'),
        ];
    }
}
