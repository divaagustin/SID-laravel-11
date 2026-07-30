<?php

namespace App\Filament\Resources\Kias;

use App\Filament\Resources\Kias\Pages\CreateKia;
use App\Filament\Resources\Kias\Pages\EditKia;
use App\Filament\Resources\Kias\Pages\ListKias;
use App\Filament\Resources\Kias\Schemas\KiaForm;
use App\Filament\Resources\Kias\Tables\KiasTable;
use App\Models\Kia;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KiaResource extends Resource
{
    protected static ?string $modelLabel = 'Kartu Ibu & Anak';
    protected static ?string $pluralModelLabel = 'Layanan KIA & Kesehatan';

    protected static ?string $model = Kia::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-heart';
    protected static string|\UnitEnum|null $navigationGroup = 'Bantuan Sosial & Kesehatan';
    protected static ?string $navigationLabel = 'Kesehatan Ibu & Anak (KIA)';
    protected static ?string $title = 'Kesehatan Ibu & Anak (KIA / Stunting)';
    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'no_kia';

    public static function form(Schema $schema): Schema
    {
        return KiaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KiasTable::configure($table);
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
            'index' => ListKias::route('/'),
            'create' => CreateKia::route('/create'),
            'edit' => EditKia::route('/{record}/edit'),
        ];
    }
}
