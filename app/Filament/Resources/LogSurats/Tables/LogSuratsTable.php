<?php

namespace App\Filament\Resources\LogSurats\Tables;

use App\Models\LogSurat;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class LogSuratsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_surat')
                    ->label('Nomor Surat')
                    ->searchable()
                    ->sortable()
                    ->fontFamily('mono')
                    ->weight('bold'),

                TextColumn::make('nama_surat')
                    ->label('Jenis Surat')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('penduduk.nama')
                    ->label('Pemohon')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama_pamong')
                    ->label('Penandatangan')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('verifikasi_sekdes')
                    ->label('Paraf Sekdes')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ((int) $state) {
                        1 => '✅ Paraf',
                        2 => '❌ Ditolak',
                        default => '⏳ Pending',
                    })
                    ->color(fn ($state) => match ((int) $state) {
                        1 => 'success',
                        2 => 'danger',
                        default => 'warning',
                    }),

                TextColumn::make('verifikasi_kades')
                    ->label('Paraf Kades')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ((int) $state) {
                        1 => '✅ Disetujui',
                        2 => '❌ Ditolak',
                        default => '⏳ Pending',
                    })
                    ->color(fn ($state) => match ((int) $state) {
                        1 => 'success',
                        2 => 'danger',
                        default => 'warning',
                    }),

                TextColumn::make('tanggal')
                    ->label('Tanggal Cetak')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                IconColumn::make('tte')
                    ->label('TTE')
                    ->boolean()
                    ->alignCenter()
                    ->sortable(),
            ])
            ->defaultSort('tanggal', 'desc')
            ->filters([
                TernaryFilter::make('tte')
                    ->label('Status TTE')
                    ->trueLabel('Sudah TTE')
                    ->falseLabel('Belum TTE'),

                SelectFilter::make('verifikasi_sekdes')
                    ->label('Paraf Sekdes')
                    ->options([
                        '1' => 'Disetujui Sekdes',
                        '2' => 'Ditolak Sekdes',
                        '0' => 'Pending Sekdes',
                    ]),

                SelectFilter::make('verifikasi_kades')
                    ->label('Persetujuan Kades')
                    ->options([
                        '1' => 'Disetujui Kades',
                        '2' => 'Ditolak Kades',
                        '0' => 'Pending Kades',
                    ]),
            ])
            ->actions([
                // Paraf Sekretaris Desa
                Action::make('paraf_sekdes')
                    ->label('Paraf Sekdes')
                    ->icon('heroicon-o-check-badge')
                    ->color('info')
                    ->visible(fn (LogSurat $record) => auth()->user()?->can('verifySekdes', $record) ?? false)
                    ->action(function (LogSurat $record) {
                        $record->update([
                            'verifikasi_sekdes' => 1,
                        ]);

                        Notification::make()
                            ->title('Paraf Sekdes Berhasil')
                            ->body("Surat No. {$record->no_surat} telah diparaf oleh Sekretaris Desa.")
                            ->success()
                            ->send();
                    }),

                // Persetujuan Kepala Desa
                Action::make('setujui_kades')
                    ->label('Setujui Kades')
                    ->icon('heroicon-o-check-circle')
                    ->color('warning')
                    ->visible(fn (LogSurat $record) => auth()->user()?->can('verifyKades', $record) ?? false)
                    ->action(function (LogSurat $record) {
                        $record->update([
                            'verifikasi_kades' => 1,
                        ]);

                        Notification::make()
                            ->title('Persetujuan Kades Berhasil')
                            ->body("Surat No. {$record->no_surat} telah disetujui oleh Kepala Desa dan siap untuk TTE.")
                            ->success()
                            ->send();
                    }),

                // Cetak PDF
                Action::make('cetak')
                    ->label('Cetak PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->url(fn ($record) => route('admin.surat.cetak', $record))
                    ->openUrlInNewTab(),

                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
