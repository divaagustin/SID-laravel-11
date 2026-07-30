<?php

namespace App\Filament\Resources\Keluargas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KeluargasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_kk')
                    ->label('No. KK')
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono')
                    ->weight('bold')
                    ->sortable(),

                TextColumn::make('kepala.nama')
                    ->label('Nama Kepala Keluarga')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Belum Ditentukan')
                    ->weight('semibold'),

                TextColumn::make('nik_kepala')
                    ->label('NIK Kepala')
                    ->searchable()
                    ->fontFamily('mono')
                    ->sortable(),

                TextColumn::make('anggota_count')
                    ->label('Jumlah Anggota')
                    ->counts('anggota')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                TextColumn::make('wilayah.dusun')
                    ->label('Dusun / Wilayah')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('alamat')
                    ->label('Alamat')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('tgl_daftar')
                    ->label('Tgl. Terdaftar')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('no_kk')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
