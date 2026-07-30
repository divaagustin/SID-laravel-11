<?php

namespace App\Filament\Resources\LogSurats;

use App\Filament\Resources\LogSurats\Pages\CreateLogSurat;
use App\Filament\Resources\LogSurats\Pages\EditLogSurat;
use App\Filament\Resources\LogSurats\Pages\ListLogSurats;
use App\Filament\Resources\LogSurats\Schemas\LogSuratForm;
use App\Filament\Resources\LogSurats\Tables\LogSuratsTable;
use App\Models\LogSurat;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LogSuratResource extends Resource
{
    protected static ?string $modelLabel = 'Log Surat';
    protected static ?string $pluralModelLabel = 'Log Transaksi Surat';

    protected static ?string $model = LogSurat::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;

    protected static ?string $navigationLabel = 'Arsip & Cetak Surat';

    protected static string|\UnitEnum|null $navigationGroup = 'Layanan Persuratan';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'no_surat';

    public static function form(Schema $schema): Schema
    {
        return LogSuratForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LogSuratsTable::configure($table);
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
            'index' => ListLogSurats::route('/'),
            'create' => CreateLogSurat::route('/create'),
            'edit' => EditLogSurat::route('/{record}/edit'),
        ];
    }
}
