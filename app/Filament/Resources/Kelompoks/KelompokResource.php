<?php

namespace App\Filament\Resources\Kelompoks;

use App\Filament\Resources\Kelompoks\Pages\CreateKelompok;
use App\Filament\Resources\Kelompoks\Pages\EditKelompok;
use App\Filament\Resources\Kelompoks\Pages\ListKelompoks;
use App\Filament\Resources\Kelompoks\Schemas\KelompokForm;
use App\Filament\Resources\Kelompoks\Tables\KelompoksTable;
use App\Models\Kelompok;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KelompokResource extends Resource
{
    protected static ?string $modelLabel = 'Kelompok Warga';
    protected static ?string $pluralModelLabel = 'Kelompok & Lembaga Warga';

    protected static ?string $model = Kelompok::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';
    protected static string|\UnitEnum|null $navigationGroup = 'Kependudukan';
    protected static ?string $navigationLabel = 'Kelompok & Lembaga';
    protected static ?string $title = 'Kelompok Masyarakat & Lembaga Desa';
    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return KelompokForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KelompoksTable::configure($table);
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
            'index' => ListKelompoks::route('/'),
            'create' => CreateKelompok::route('/create'),
            'edit' => EditKelompok::route('/{record}/edit'),
        ];
    }
}
