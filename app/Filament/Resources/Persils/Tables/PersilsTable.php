<?php

namespace App\Filament\Resources\Persils\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class PersilsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor')
                    ->label('No. Persil')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('nomor_urut_bidang')
                    ->label('NUB')
                    ->sortable(),

                TextColumn::make('kelas')
                    ->label('Kelas Tanah')
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        $ref = DB::table('ref_persil_kelas')->find($state);
                        return $ref ? "{$ref->kode} ({$ref->tipe})" : "Kelas {$state}";
                    })
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('luas_persil')
                    ->label('Luas Persil')
                    ->suffix(' m²')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('cdesa.nomor')
                    ->label('C-Desa')
                    ->placeholder('-')
                    ->badge()
                    ->color('info'),

                IconColumn::make('is_publik')
                    ->label('GIS Publik')
                    ->boolean(),
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
