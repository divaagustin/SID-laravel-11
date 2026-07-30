<?php

namespace App\Filament\Resources\KeuanganApbdes;

use App\Filament\Resources\KeuanganApbdes\Pages\CreateKeuanganApbdes;
use App\Filament\Resources\KeuanganApbdes\Pages\EditKeuanganApbdes;
use App\Filament\Resources\KeuanganApbdes\Pages\ListKeuanganApbdes;
use App\Filament\Resources\KeuanganApbdes\Schemas\KeuanganApbdesForm;
use App\Filament\Resources\KeuanganApbdes\Tables\KeuanganApbdesTable;
use App\Models\KeuanganApbdes;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KeuanganApbdesResource extends Resource
{
    protected static ?string $modelLabel = 'Keuangan APBDes';
    protected static ?string $pluralModelLabel = 'Transparansi APBDes';

    protected static ?string $model = KeuanganApbdes::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';
    protected static string|\UnitEnum|null $navigationGroup = 'Pembangunan, Aset & BUMDes';
    protected static ?string $navigationLabel = 'Keuangan & APBDes';
    protected static ?string $title = 'Laporan Transparansi APBDes';
    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'tahun';

    public static function form(Schema $schema): Schema
    {
        return KeuanganApbdesForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KeuanganApbdesTable::configure($table);
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
            'index' => ListKeuanganApbdes::route('/'),
            'create' => CreateKeuanganApbdes::route('/create'),
            'edit' => EditKeuanganApbdes::route('/{record}/edit'),
        ];
    }
}
