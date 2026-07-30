<?php

namespace App\Filament\Resources\KeuanganApbdes\Schemas;

use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class KeuanganApbdesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('config_id')->default(1),

                Section::make('Laporan Keuangan APBDes Tahun Anggaran')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('tahun')
                                ->label('Tahun Anggaran APBDes')
                                ->numeric()
                                ->default(date('Y'))
                                ->required(),
                            TextInput::make('template_uuid')
                                ->label('Kode UUID Siskeudes / Ref')
                                ->default(\Illuminate\Support\Str::uuid()->toString())
                                ->required(),
                            TextInput::make('anggaran')
                                ->label('Pagu Anggaran Pendapatan / Belanja (Rp)')
                                ->numeric()
                                ->prefix('Rp')
                                ->required(),
                            TextInput::make('realisasi')
                                ->label('Realisasi Anggaran (Rp)')
                                ->numeric()
                                ->prefix('Rp')
                                ->default(0)
                                ->required(),
                        ]),
                    ]),
            ]);
    }
}
