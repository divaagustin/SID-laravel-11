<?php

namespace App\Filament\Resources\Lokasis\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LokasiTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto')
                    ->label('Foto')
                    ->square(),

                TextColumn::make('nama')
                    ->label('Nama Lokasi')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('kategoriPoint.nama')
                    ->label('Kategori')
                    ->badge()
                    ->color('info')
                    ->placeholder('Fasilitas Umum'),

                TextColumn::make('lat')
                    ->label('Latitude')
                    ->fontFamily('mono')
                    ->copyable(),

                TextColumn::make('lng')
                    ->label('Longitude')
                    ->fontFamily('mono')
                    ->copyable(),

                IconColumn::make('enabled')
                    ->label('Status')
                    ->boolean(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-map-pin')
            ->emptyStateHeading('Belum Ada Penanda Lokasi')
            ->emptyStateDescription('Tambah koordinat lokasi fasilitas desa pertama dengan klik tombol di atas.');
    }
}
