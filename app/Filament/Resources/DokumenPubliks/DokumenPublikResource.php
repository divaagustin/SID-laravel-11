<?php

namespace App\Filament\Resources\DokumenPubliks;

use App\Filament\Resources\DokumenPubliks\Pages;
use App\Filament\Resources\DokumenPubliks\Schemas\DokumenPublikForm;
use App\Filament\Resources\DokumenPubliks\Tables\DokumenPublikTable;
use App\Models\DokumenPublik;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DokumenPublikResource extends Resource
{
    protected static ?string $modelLabel = 'Dokumen Publik';
    protected static ?string $pluralModelLabel = 'Dokumen Publik';

    protected static ?string $model = DokumenPublik::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationLabel = 'Dokumen Publik';
    protected static string|\UnitEnum|null $navigationGroup = 'Admin Web & Pengaduan';
    protected static ?int $navigationSort = 4;
            protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return DokumenPublikForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DokumenPublikTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListDokumenPubliks::route('/'),
            'create' => Pages\CreateDokumenPublik::route('/create'),
            'edit'   => Pages\EditDokumenPublik::route('/{record}/edit'),
        ];
    }
}
