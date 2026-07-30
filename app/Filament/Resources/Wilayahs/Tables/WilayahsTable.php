<?php

namespace App\Filament\Resources\Wilayahs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WilayahsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('dusun')
                    ->label('Nama Dusun / Lingkungan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->placeholder('-'),

                TextColumn::make('rw')
                    ->label('RW')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('rt')
                    ->label('RT')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kepalaWilayah.nama')
                    ->label('Kepala Dusun / Ketua RT/RW')
                    ->searchable()
                    ->placeholder('Belum Ditentukan')
                    ->weight('semibold')
                    ->color(fn ($state) => $state ? 'emerald' : 'gray'),

                TextColumn::make('penduduk_count')
                    ->label('Jumlah Penduduk')
                    ->counts('penduduk')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                TextColumn::make('keluarga_count')
                    ->label('Jumlah KK')
                    ->counts('keluarga')
                    ->badge()
                    ->color('warning')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Ubah'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Hapus Terpilih'),
                ]),
            ]);
    }
}
