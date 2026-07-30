<?php

namespace App\Filament\Resources\SuratKeluars\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;

class SuratKeluarTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor_surat')
                    ->label('No. Surat')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold')
                    ->copyable(),

                TextColumn::make('tujuan')
                    ->label('Tujuan')
                    ->searchable()
                    ->limit(40),

                TextColumn::make('isi_singkat')
                    ->label('Perihal')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->isi_singkat),

                TextColumn::make('tanggal_surat')
                    ->label('Tanggal Surat')
                    ->date('d/m/Y')
                    ->sortable(),

                IconColumn::make('ekspedisi')
                    ->label('Ekspedisi')
                    ->boolean()
                    ->trueIcon('heroicon-o-truck')
                    ->falseIcon('heroicon-o-minus-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),

                TextColumn::make('tanggal_pengiriman')
                    ->label('Tgl Kirim')
                    ->date('d/m/Y')
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('berkas_scan')
                    ->label('Berkas')
                    ->formatStateUsing(fn ($state) => $state ? '📎 Ada' : '—')
                    ->color(fn ($state) => $state ? 'success' : 'gray'),
            ])
            ->defaultSort('tanggal_surat', 'desc')
            ->filters([
                TernaryFilter::make('ekspedisi')
                    ->label('Pengiriman')
                    ->trueLabel('Via Ekspedisi')
                    ->falseLabel('Langsung'),

                Filter::make('tanggal_range')
                    ->form([
                        DatePicker::make('dari')->label('Dari Tanggal'),
                        DatePicker::make('sampai')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['dari'], fn ($q, $v) => $q->whereDate('tanggal_surat', '>=', $v))
                            ->when($data['sampai'], fn ($q, $v) => $q->whereDate('tanggal_surat', '<=', $v));
                    }),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-paper-airplane')
            ->emptyStateHeading('Belum ada surat keluar')
            ->emptyStateDescription('Tambah surat keluar pertama dengan klik tombol di atas.');
    }
}
