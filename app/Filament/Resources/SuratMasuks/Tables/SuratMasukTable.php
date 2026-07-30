<?php

namespace App\Filament\Resources\SuratMasuks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;

class SuratMasukTable
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

                TextColumn::make('pengirim')
                    ->label('Pengirim')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                TextColumn::make('isi_singkat')
                    ->label('Perihal')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->isi_singkat),

                TextColumn::make('tanggal_penerimaan')
                    ->label('Tgl Terima')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('tanggal_surat')
                    ->label('Tgl Surat')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('disposisi_count')
                    ->label('Disposisi')
                    ->counts('disposisi')
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'gray'),

                TextColumn::make('berkas_scan')
                    ->label('Berkas')
                    ->formatStateUsing(fn ($state) => $state ? '📎 Ada' : '—')
                    ->color(fn ($state) => $state ? 'success' : 'gray'),
            ])
            ->defaultSort('tanggal_penerimaan', 'desc')
            ->filters([
                Filter::make('bulan_ini')
                    ->label('Bulan Ini')
                    ->query(fn (Builder $query) => $query->whereMonth('tanggal_penerimaan', now()->month)),

                Filter::make('tanggal_range')
                    ->form([
                        DatePicker::make('dari')->label('Dari Tanggal'),
                        DatePicker::make('sampai')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['dari'], fn ($q, $v) => $q->whereDate('tanggal_penerimaan', '>=', $v))
                            ->when($data['sampai'], fn ($q, $v) => $q->whereDate('tanggal_penerimaan', '<=', $v));
                    }),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-inbox')
            ->emptyStateHeading('Belum ada surat masuk')
            ->emptyStateDescription('Tambah surat masuk pertama dengan klik tombol di atas.');
    }
}
