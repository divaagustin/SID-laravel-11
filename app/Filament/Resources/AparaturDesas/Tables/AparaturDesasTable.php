<?php

namespace App\Filament\Resources\AparaturDesas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AparaturDesasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl(url('https://ui-avatars.com/api/?name=Pamong&background=1a5c2d&color=ffffff')),

                TextColumn::make('pamong_nama')
                    ->label('Nama Perangkat Desa')
                    ->description(fn ($record) => trim(($record->gelar_depan ?? '') . ' ' . $record->pamong_nama . ' ' . ($record->gelar_belakang ?? '')))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('jabatan.nama')
                    ->label('Jabatan')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('pamong_nik')
                    ->label('NIK')
                    ->searchable(),

                TextColumn::make('pamong_nip')
                    ->label('NIP / NIAP')
                    ->placeholder('-')
                    ->searchable(),

                IconColumn::make('pamong_status')
                    ->label('Status Aktif')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                IconColumn::make('tampilkan_beranda')
                    ->label('Beranda')
                    ->boolean()
                    ->trueIcon('heroicon-o-home')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('success')
                    ->falseColor('gray'),

                IconColumn::make('tampilkan_struktur')
                    ->label('Struktur')
                    ->boolean()
                    ->trueIcon('heroicon-o-rectangle-group')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('info')
                    ->falseColor('gray'),

                IconColumn::make('pamong_ttd')
                    ->label('Hak TTD')
                    ->boolean()
                    ->trueIcon('heroicon-o-pencil-square')
                    ->falseIcon('heroicon-o-minus')
                    ->trueColor('warning')
                    ->falseColor('gray'),
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
