<?php

namespace App\Filament\Resources\Pembangunans;

use App\Filament\Resources\Pembangunans\Pages\CreatePembangunan;
use App\Filament\Resources\Pembangunans\Pages\EditPembangunan;
use App\Filament\Resources\Pembangunans\Pages\ListPembangunans;
use App\Filament\Resources\Pembangunans\Schemas\PembangunanForm;
use App\Filament\Resources\Pembangunans\Tables\PembangunansTable;
use App\Models\Pembangunan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PembangunanResource extends Resource
{
    protected static ?string $modelLabel = 'Pembangunan';
    protected static ?string $pluralModelLabel = 'Proyek Pembangunan';

    protected static ?string $model = Pembangunan::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static string|\UnitEnum|null $navigationGroup = 'Pembangunan, Aset & BUMDes';
    protected static ?string $navigationLabel = 'Proyek Pembangunan';
    protected static ?string $title = 'Administrasi Pembangunan Desa';
    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'judul';

    public static function form(Schema $schema): Schema
    {
        return PembangunanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PembangunansTable::configure($table);
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
            'index' => ListPembangunans::route('/'),
            'create' => CreatePembangunan::route('/create'),
            'edit' => EditPembangunan::route('/{record}/edit'),
        ];
    }
}
