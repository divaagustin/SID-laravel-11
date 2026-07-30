<?php

namespace App\Filament\Resources\Pembangunans\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PembangunanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('config_id')->default(1),

                Section::make('Informasi Proyek Pembangunan')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('judul')
                                ->label('Nama / Judul Kegiatan Pembangunan')
                                ->placeholder('Contoh: Pembangunan Jalan Rabat Beton Dusun I')
                                ->required(),
                            Select::make('sumber_dana')
                                ->label('Sumber Dana')
                                ->options([
                                    'Dana Desa (DD)' => 'Dana Desa (DD)',
                                    'Alokasi Dana Desa (ADD)' => 'Alokasi Dana Desa (ADD)',
                                    'Bantuan Keuangan Provinsi' => 'Bantuan Keuangan Provinsi',
                                    'Swadaya Masyarakat' => 'Swadaya Masyarakat',
                                ])
                                ->required(),
                            TextInput::make('volume')
                                ->label('Volume Proyek')
                                ->placeholder('Contoh: 500 m x 3 m')
                                ->required(),
                            TextInput::make('tahun_anggaran')
                                ->label('Tahun Anggaran')
                                ->numeric()
                                ->default(date('Y'))
                                ->required(),
                            TextInput::make('anggaran')
                                ->label('Pagu Anggaran (Rp)')
                                ->numeric()
                                ->prefix('Rp')
                                ->required(),
                            TextInput::make('realisasi_anggaran')
                                ->label('Realisasi Anggaran (Rp)')
                                ->numeric()
                                ->prefix('Rp')
                                ->default(0),
                            TextInput::make('pelaksana_kegiatan')
                                ->label('Pelaksana Kegiatan (TPK)')
                                ->placeholder('Contoh: Tim Pelaksana Kegiatan Dusun I'),
                            TextInput::make('lokasi')
                                ->label('Lokasi Dusun / RT / RW'),
                        ]),
                        FileUpload::make('foto')
                            ->label('Foto Dokumentasi Proyek')
                            ->image()
                            ->directory('pembangunan')
                            ->columnSpanFull(),
                        Textarea::make('keterangan')
                            ->label('Keterangan Manfaat & Catatan Kegiatan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
