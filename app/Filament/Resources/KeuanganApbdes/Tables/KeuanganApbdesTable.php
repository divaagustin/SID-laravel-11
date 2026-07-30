<?php

namespace App\Filament\Resources\KeuanganApbdes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KeuanganApbdesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tahun')
                    ->label('Tahun Anggaran')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('anggaran')
                    ->label('Pagu Anggaran')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('realisasi')
                    ->label('Realisasi APBDes')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Tanggal Input')
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
