<?php

namespace App\Filament\Resources\InventarisAssets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InventarisAssetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_barang')
                    ->label('Kode Aset')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('nama_barang')
                    ->label('Nama Barang / Aset')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('jenis')
                    ->label('Kategori')
                    ->badge()
                    ->color('info')
                    ->searchable(),

                TextColumn::make('jumlah')
                    ->label('Jumlah')
                    ->suffix(' Unit')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('harga')
                    ->label('Harga Perolehan')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('tahun_pengadaan')
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
