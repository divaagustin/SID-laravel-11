<?php

namespace App\Filament\Resources\Cdesas\Schemas;

use App\Models\Penduduk;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CdesaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('config_id')->default(1),

                Section::make('Informasi Nomor & Kepemilikan C-Desa')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('nomor')
                                ->label('Nomor C-Desa')
                                ->placeholder('Contoh: 1045')
                                ->required(),
                            Select::make('jenis_pemilik')
                                ->label('Jenis Pemilik')
                                ->options([
                                    0 => 'Warga Desa (Terdaftar Kependudukan)',
                                    1 => 'Warga Luar Desa / Badan Usaha',
                                ])
                                ->default(0)
                                ->reactive()
                                ->required(),
                            TextInput::make('nama_kepemilikan')
                                ->label('Nama Kepemilikan Hak')
                                ->placeholder('Contoh: Hak Milik Sapto')
                                ->required(),
                        ]),
                    ]),

                Section::make('Data Warga Desa (Pemilik)')
                    ->visible(fn ($get) => (int) $get('jenis_pemilik') === 0)
                    ->schema([
                        Select::make('penduduk')
                            ->label('Pilih Penduduk Pemilik C-Desa')
                            ->multiple()
                            ->relationship('penduduk', 'nama')
                            ->searchable()
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama} (NIK: {$record->nik})"),
                    ]),

                Section::make('Data Pemilik Luar Desa / Badan Hukum')
                    ->visible(fn ($get) => (int) $get('jenis_pemilik') === 1)
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('nama_pemilik_luar')->label('Nama Pemilik / Badan Hukum'),
                            TextInput::make('nik_pemilik_luar')->label('NIK / NPWP Pemilik'),
                            TextInput::make('alamat_pemilik_luar')->label('Alamat Lengkap')->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }
}
