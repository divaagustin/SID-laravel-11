<?php

namespace App\Filament\Resources\SuratFormats\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class SuratFormatsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_surat')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->fontFamily('mono')
                    ->weight('bold'),

                TextColumn::make('nama')
                    ->label('Nama Layanan Surat')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                IconColumn::make('mandiri')
                    ->label('Layanan Mandiri')
                    ->boolean()
                    ->alignCenter()
                    ->sortable(),

                IconColumn::make('qr_code_tte')
                    ->label('TTE')
                    ->boolean()
                    ->alignCenter()
                    ->sortable(),

                IconColumn::make('logo_garuda')
                    ->label('Kop Garuda')
                    ->boolean()
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('masa_berlaku')
                    ->label('Masa Berlaku')
                    ->formatStateUsing(fn ($record) => $record->masa_berlaku . ' ' . match($record->satuan_masa_berlaku) {
                        'H' => 'Hari',
                        'W' => 'Minggu',
                        'M' => 'Bulan',
                        'Y' => 'Tahun',
                        default => $record->satuan_masa_berlaku
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('favorit')
                    ->label('Favorit')
                    ->boolean()
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('mandiri')
                    ->label('Layanan Mandiri Warga'),
                TernaryFilter::make('qr_code_tte')
                    ->label('Mendukung TTE'),
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
