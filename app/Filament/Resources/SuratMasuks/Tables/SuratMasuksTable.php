<?php

namespace App\Filament\Resources\SuratMasuks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SuratMasuksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('config_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('nomor_urut')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tanggal_penerimaan')
                    ->date()
                    ->sortable(),
                TextColumn::make('nomor_surat')
                    ->searchable(),
                TextColumn::make('kode_surat')
                    ->searchable(),
                TextColumn::make('tanggal_surat')
                    ->date()
                    ->sortable(),
                TextColumn::make('pengirim')
                    ->searchable(),
                TextColumn::make('isi_singkat')
                    ->searchable(),
                TextColumn::make('isi_disposisi')
                    ->searchable(),
                TextColumn::make('berkas_scan')
                    ->searchable(),
                TextColumn::make('lokasi_arsip')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
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
