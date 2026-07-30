<?php

namespace App\Filament\Resources\DokumenPubliks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DokumenPublikTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Dokumen')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(55),

                TextColumn::make('kategori_label')
                    ->label('Kategori')
                    ->badge()
                    ->color('success'),

                TextColumn::make('tahun')
                    ->label('Tahun')
                    ->sortable()
                    ->alignCenter(),

                IconColumn::make('enabled')
                    ->label('Terbit')
                    ->boolean(),

                TextColumn::make('tgl_upload')
                    ->label('Tgl Upload')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('tgl_upload', 'desc')
            ->filters([
                SelectFilter::make('kategori_info_publik')
                    ->label('Kategori Info Publik')
                    ->options([
                        1 => 'Informasi Berkala',
                        2 => 'Informasi Serta-Merta',
                        3 => 'Informasi Setiap Saat',
                    ]),
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
            ->emptyStateIcon('heroicon-o-document-text')
            ->emptyStateHeading('Belum Ada Dokumen Publik')
            ->emptyStateDescription('Unggah dokumen transparansi desa pertama dengan klik tombol di atas.');
    }
}
