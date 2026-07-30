<?php

namespace App\Filament\Resources\PesanMandiris;

use App\Filament\Resources\PesanMandiris\Pages;
use App\Filament\Resources\PesanMandiris\Schemas\PesanMandiriForm;
use App\Filament\Resources\PesanMandiris\Tables\PesanMandiriTable;
use App\Models\PesanMandiri;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PesanMandiriResource extends Resource
{
    protected static ?string $modelLabel = 'Pengaduan Warga';
    protected static ?string $pluralModelLabel = 'Pengaduan & Aspirasi Warga';

    protected static ?string $model = PesanMandiri::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Pengaduan & Aspirasi';
    protected static string|\UnitEnum|null $navigationGroup = 'Admin Web & Pengaduan';
    protected static ?int $navigationSort = 5;
            protected static ?string $recordTitleAttribute = 'subjek';

    public static function getNavigationBadge(): ?string
    {
        $count = \Illuminate\Support\Facades\Cache::remember('nav_badge_pesan_mandiri_count', 30, fn () => PesanMandiri::where('status', 1)->count());

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'danger';
    }

    public static function form(Schema $schema): Schema
    {
        return PesanMandiriForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PesanMandiriTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPesanMandiris::route('/'),
            'create' => Pages\CreatePesanMandiri::route('/create'),
            'edit'   => Pages\EditPesanMandiri::route('/{record}/edit'),
        ];
    }
}
