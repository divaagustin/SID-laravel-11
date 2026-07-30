<?php

namespace App\Filament\Resources\Kias\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KiasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_kia')
                    ->label('No. Buku KIA')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('ibu.nama')
                    ->label('Nama Ibu')
                    ->placeholder('-')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('anak.nama')
                    ->label('Nama Anak / Balita')
                    ->placeholder('-')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('hari_perkiraan_lahir')
                    ->label('Perkiraan Lahir (HPL)')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Tgl Terdaftar')
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
