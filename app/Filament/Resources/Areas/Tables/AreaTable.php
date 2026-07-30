<?php

namespace App\Filament\Resources\Areas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AreaTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Area')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('desk')
                    ->label('Keterangan')
                    ->limit(50),

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
            ->emptyStateIcon('heroicon-o-globe-alt')
            ->emptyStateHeading('Belum Ada Area Batas')
            ->emptyStateDescription('Tambah area polygon batas desa pertama dengan klik tombol di atas.');
    }
}
