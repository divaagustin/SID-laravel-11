<?php

namespace App\Filament\Resources\ProgramBantuans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProgramBantuansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Program Bantuan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('sasaran_label')
                    ->label('Sasaran')
                    ->badge()
                    ->color(fn ($record) => match((int)$record->sasaran) {
                        1 => 'info',
                        2 => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('asaldana')
                    ->label('Sumber Dana')
                    ->badge()
                    ->color('success'),

                TextColumn::make('peserta_count')
                    ->label('Jumlah Penerima')
                    ->counts('peserta')
                    ->suffix(' Penerima')
                    ->sortable(),

                TextColumn::make('sdate')
                    ->label('Mulai')
                    ->date()
                    ->sortable(),

                TextColumn::make('edate')
                    ->label('Selesai')
                    ->date()
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
