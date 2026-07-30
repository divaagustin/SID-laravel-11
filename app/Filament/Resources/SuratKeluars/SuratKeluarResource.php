<?php

namespace App\Filament\Resources\SuratKeluars;

use App\Filament\Resources\SuratKeluars\Pages;
use App\Filament\Resources\SuratKeluars\Schemas\SuratKeluarForm;
use App\Filament\Resources\SuratKeluars\Tables\SuratKeluarTable;
use App\Models\SuratKeluar;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SuratKeluarResource extends Resource
{
    protected static ?string $modelLabel = 'Surat Keluar';
    protected static ?string $pluralModelLabel = 'Surat Keluar';

    protected static ?string $model = SuratKeluar::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-up-right';
    protected static ?string $navigationLabel = 'Surat Keluar';
    protected static string|\UnitEnum|null $navigationGroup = 'Layanan Persuratan';
    protected static ?int $navigationSort = 5;
            protected static ?string $recordTitleAttribute = 'nomor_surat';

    public static function form(Schema $schema): Schema
    {
        return SuratKeluarForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SuratKeluarTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSuratKeluars::route('/'),
            'create' => Pages\CreateSuratKeluar::route('/create'),
            'edit'   => Pages\EditSuratKeluar::route('/{record}/edit'),
        ];
    }
}
