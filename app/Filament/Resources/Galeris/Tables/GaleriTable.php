<?php

namespace App\Filament\Resources\Galeris\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GaleriTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('gambar')
                    ->label('Pratinjau Foto')
                    ->square(),

                TextColumn::make('nama')
                    ->label('Judul Foto / Kegiatan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                IconColumn::make('enabled')
                    ->label('Status Publik')
                    ->boolean(),

                IconColumn::make('slider')
                    ->label('Slider Beranda')
                    ->boolean(),

                TextColumn::make('tgl_upload')
                    ->label('Tgl Upload')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('tgl_upload', 'desc')
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-photo')
            ->emptyStateHeading('Belum Ada Foto Galeri')
            ->emptyStateDescription('Unggah foto kegiatan desa pertama dengan klik tombol di atas.');
    }
}
