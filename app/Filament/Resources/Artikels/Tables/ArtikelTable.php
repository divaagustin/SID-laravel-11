<?php

namespace App\Filament\Resources\Artikels\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ArtikelTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('gambar')
                    ->label('Thumbnail')
                    ->square()
                    ->defaultImageUrl('https://images.unsplash.com/photo-1577495508048-b635879837f1?auto=format&fit=crop&q=80&w=200'),

                TextColumn::make('judul')
                    ->label('Judul Berita')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(45),

                TextColumn::make('kategori.kategori')
                    ->label('Kategori')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                IconColumn::make('enabled')
                    ->label('Terbit')
                    ->boolean(),

                IconColumn::make('slider')
                    ->label('Slider')
                    ->boolean()
                    ->trueIcon('heroicon-o-photo')
                    ->trueColor('warning'),

                TextColumn::make('hit')
                    ->label('Dibaca')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('tgl_upload')
                    ->label('Tgl Rilis')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('tgl_upload', 'desc')
            ->filters([
                SelectFilter::make('id_kategori')
                    ->label('Kategori')
                    ->relationship('kategori', 'kategori'),

                TernaryFilter::make('enabled')
                    ->label('Status Terbit')
                    ->trueLabel('Terbit')
                    ->falseLabel('Draf'),

                TernaryFilter::make('slider')
                    ->label('Tampil di Slider')
                    ->trueLabel('Slider On')
                    ->falseLabel('Slider Off'),
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
            ->emptyStateIcon('heroicon-o-newspaper')
            ->emptyStateHeading('Belum Ada Berita')
            ->emptyStateDescription('Tulis berita pertama desa dengan klik tombol di atas.');
    }
}
