<?php

namespace App\Filament\Resources\Kelompoks\Schemas;

use App\Models\Penduduk;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class KelompokForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('config_id')->default(1),

                Section::make('Informasi Kelompok / Lembaga Warga')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('nama')
                                ->label('Nama Kelompok / Organisasi')
                                ->placeholder('Contoh: Karang Taruna Bina Remaja, Kelompok Tani Subur')
                                ->required(),
                            TextInput::make('kode')
                                ->label('Kode Kelompok')
                                ->placeholder('Contoh: KLT-01')
                                ->required(),
                            TextInput::make('no_sk_pendirian')
                                ->label('Nomor SK Pendirian'),
                            Select::make('id_ketua')
                                ->label('Ketua Kelompok (Dari Penduduk)')
                                ->placeholder('Cari Ketua Kelompok...')
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
                        Textarea::make('keterangan')
                            ->label('Keterangan / Deskripsi Kegiatan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
