<?php

namespace App\Filament\Resources\Kelompoks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KelompoksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('nama')
                    ->label('Nama Kelompok')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('ketua.nama')
                    ->label('Ketua Kelompok')
                    ->placeholder('-')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('no_sk_pendirian')
                    ->label('SK Pendirian')
                    ->placeholder('-')
                    ->searchable(),

                TextColumn::make('anggota_count')
                    ->label('Jumlah Anggota')
                    ->counts('anggota')
                    ->suffix(' Orang')
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
