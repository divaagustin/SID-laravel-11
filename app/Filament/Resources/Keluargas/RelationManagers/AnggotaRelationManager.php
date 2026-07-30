<?php

namespace App\Filament\Resources\Keluargas\RelationManagers;

use App\Models\Penduduk;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AnggotaRelationManager extends RelationManager
{
    protected static string $relationship = 'anggota';

    protected static ?string $recordTitleAttribute = 'nama';

    protected static ?string $title = '👨‍👩‍👧‍👦 Daftar Anggota Keluarga (Dalam KK)';

    public function form(Schema $schema): Schema
    {
        return $schema;
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable()
                    ->fontFamily('mono')
                    ->copyable(),

                TextColumn::make('nama')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('sex')
                    ->label('Jenis Kelamin')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ((int) $state) {
                        1 => 'Laki-laki',
                        2 => 'Perempuan',
                        default => '-',
                    })
                    ->color(fn ($state) => match ((int) $state) {
                        1 => 'info',
                        2 => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('kk_level')
                    ->label('Hubungan (SHDK)')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ((int) $state) {
                        1 => 'Kepala Keluarga',
                        2 => 'Suami',
                        3 => 'Istri',
                        4 => 'Anak',
                        5 => 'Menantu',
                        6 => 'Cucu',
                        7 => 'Orangtua',
                        8 => 'Mertua',
                        9 => 'Famili Lain',
                        10 => 'Pembantu',
                        11 => 'Lainnya',
                        default => '-',
                    })
                    ->color(fn ($state) => match ((int) $state) {
                        1 => 'success',
                        2, 3 => 'info',
                        4 => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('tanggallahir')
                    ->label('Tgl. Lahir')
                    ->date('d M Y'),

                TextColumn::make('umur')
                    ->label('Umur')
                    ->suffix(' Thn'),
            ])
            ->defaultSort('kk_level');
    }
}
