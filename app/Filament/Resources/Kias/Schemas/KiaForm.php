<?php

namespace App\Filament\Resources\Kias\Schemas;

use App\Models\Penduduk;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class KiaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('config_id')->default(1),

                Section::make('Informasi Kartu Ibu & Anak (KIA)')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('no_kia')
                                ->label('Nomor Buku KIA')
                                ->placeholder('Contoh: KIA-2026-0012')
                                ->required(),
                            DatePicker::make('hari_perkiraan_lahir')
                                ->label('Hari Perkiraan Lahir (HPL)'),
                        ]),
                    ]),

                Section::make('Data Ibu & Anak (Terhubung ke Kependudukan)')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('ibu_id')
                                ->label('Data Ibu (NIK / Nama)')
                                ->placeholder('Cari nama ibu...')
                                ->searchable()
                                ->getSearchResultsUsing(fn (string $search): array => 
                                    Penduduk::withoutGlobalScopes()
                                        ->where('sex', 2)
                                        ->where(fn ($q) => $q->where('nama', 'like', "%{$search}%")->orWhere('nik', 'like', "%{$search}%"))
                                        ->limit(20)
                                        ->pluck('nama', 'id')
                                        ->mapWithKeys(fn ($nama, $id) => [$id => $nama . ' (' . Penduduk::find($id)?->nik . ')'])
                                        ->toArray()
                                )
                                ->getOptionLabelUsing(fn ($value): ?string => 
                                    Penduduk::find($value) ? Penduduk::find($value)->nama . ' (' . Penduduk::find($value)->nik . ')' : null
                                )
                                ->required(),

                            Select::make('anak_id')
                                ->label('Data Balita / Anak (NIK / Nama)')
                                ->placeholder('Cari nama anak/balita...')
                                ->searchable()
                                ->getSearchResultsUsing(fn (string $search): array => 
                                    Penduduk::withoutGlobalScopes()
                                        ->where(fn ($q) => $q->where('nama', 'like', "%{$search}%")->orWhere('nik', 'like', "%{$search}%"))
                                        ->limit(20)
                                        ->pluck('nama', 'id')
                                        ->mapWithKeys(fn ($nama, $id) => [$id => $nama . ' (' . Penduduk::find($id)?->nik . ')'])
                                        ->toArray()
                                )
                                ->getOptionLabelUsing(fn ($value): ?string => 
                                    Penduduk::find($value) ? Penduduk::find($value)->nama . ' (' . Penduduk::find($value)->nik . ')' : null
                                ),
                        ]),
                    ]),
            ]);
    }
}
