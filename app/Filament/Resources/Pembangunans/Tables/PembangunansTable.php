<?php

namespace App\Filament\Resources\Pembangunans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PembangunansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto')
                    ->label('Foto Progres')
                    ->rounded(),

                TextColumn::make('judul')
                    ->label('Nama Kegiatan Pembangunan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('sumber_dana')
                    ->label('Sumber Dana')
                    ->badge()
                    ->color('success')
                    ->searchable(),

                TextColumn::make('volume')
                    ->label('Volume')
                    ->searchable(),

                TextColumn::make('anggaran')
                    ->label('Pagu Anggaran')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('tahun_anggaran')
                    ->label('Tahun')
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
