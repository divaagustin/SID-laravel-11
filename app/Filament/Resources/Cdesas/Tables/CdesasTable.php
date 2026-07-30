<?php

namespace App\Filament\Resources\Cdesas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CdesasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor')
                    ->label('No. C-Desa')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('nama_kepemilikan')
                    ->label('Nama Hak / Pemilik')
                    ->placeholder(fn ($record) => $record->nama_pemilik_luar ?? 'Penduduk Desa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('jenis_pemilik')
                    ->label('Jenis Pemilik')
                    ->badge()
                    ->formatStateUsing(fn ($state) => (int)$state === 0 ? 'Warga Lokal' : 'Luar Desa / Badan')
                    ->color(fn ($state) => (int)$state === 0 ? 'success' : 'warning'),

                TextColumn::make('penduduk.nama')
                    ->label('Pemilik Terdaftar (NIK)')
                    ->badge()
                    ->color('info')
                    ->placeholder('-'),

                TextColumn::make('created_at')
                    ->label('Tgl Dicatat')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
