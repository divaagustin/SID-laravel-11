<?php

namespace App\Filament\Resources\ProgramBantuans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProgramBantuanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('config_id')->default(1),

                Section::make('Informasi Program Bantuan')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('nama')
                                ->label('Nama Program Bantuan')
                                ->placeholder('Contoh: BLT Dana Desa 2026, PKH, BPNT')
                                ->required(),
                            Select::make('sasaran')
                                ->label('Sasaran Penerima')
                                ->options([
                                    1 => 'Penduduk / Perorangan',
                                    2 => 'Keluarga / Rumah Tangga',
                                ])
                                ->required(),
                            Select::make('asaldana')
                                ->label('Asal Sumber Dana')
                                ->options([
                                    'Pusat' => 'Pemerintah Pusat (Kemensos)',
                                    'Provinsi' => 'Pemerintah Provinsi',
                                    'Kabupaten' => 'Pemerintah Kabupaten',
                                    'Dana Desa' => 'Dana Desa (APBDes)',
                                ])
                                ->required(),
                            DatePicker::make('sdate')
                                ->label('Tanggal Mulai Program')
                                ->required(),
                            DatePicker::make('edate')
                                ->label('Tanggal Selesai Program')
                                ->required(),
                        ]),
                        Textarea::make('ndesc')
                            ->label('Deskripsi & Kriteria Penerima')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
