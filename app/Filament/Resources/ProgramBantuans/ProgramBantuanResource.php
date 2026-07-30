<?php

namespace App\Filament\Resources\ProgramBantuans;

use App\Filament\Resources\ProgramBantuans\Pages\CreateProgramBantuan;
use App\Filament\Resources\ProgramBantuans\Pages\EditProgramBantuan;
use App\Filament\Resources\ProgramBantuans\Pages\ListProgramBantuans;
use App\Filament\Resources\ProgramBantuans\Schemas\ProgramBantuanForm;
use App\Filament\Resources\ProgramBantuans\Tables\ProgramBantuansTable;
use App\Models\ProgramBantuan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProgramBantuanResource extends Resource
{
    protected static ?string $modelLabel = 'Program Bantuan';
    protected static ?string $pluralModelLabel = 'Program Bantuan Sosial';

    protected static ?string $model = ProgramBantuan::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-hand-raised';
    protected static string|\UnitEnum|null $navigationGroup = 'Bantuan Sosial & Kesehatan';
    protected static ?string $navigationLabel = 'Bantuan Sosial (DTKS)';
    protected static ?string $title = 'Program Bantuan Sosial & DTKS';
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return ProgramBantuanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProgramBantuansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PesertaRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProgramBantuans::route('/'),
            'create' => CreateProgramBantuan::route('/create'),
            'edit' => EditProgramBantuan::route('/{record}/edit'),
        ];
    }
}
