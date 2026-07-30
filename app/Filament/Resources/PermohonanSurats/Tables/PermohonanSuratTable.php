<?php

namespace App\Filament\Resources\PermohonanSurats\Tables;

use App\Models\LogSurat;
use App\Models\PermohonanSurat;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PermohonanSuratTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_antrian')
                    ->label('No. Resi')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->fontFamily('mono'),

                TextColumn::make('pemohon.nama')
                    ->label('Pemohon')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('formatSurat.nama')
                    ->label('Jenis Surat')
                    ->searchable()
                    ->sortable()
                    ->limit(35),

                TextColumn::make('no_hp_aktif')
                    ->label('No. WA')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ((int) $state) {
                        0 => '⏳ Menunggu',
                        1 => '📝 Diproses',
                        2 => '❌ Ditolak',
                        3 => '✅ Selesai',
                        default => 'Pending',
                    })
                    ->color(fn ($state) => match ((int) $state) {
                        0 => 'warning',
                        1 => 'info',
                        2 => 'danger',
                        3 => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Tgl Pengajuan')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Permohonan')
                    ->options([
                        '0' => 'Menunggu Verifikasi',
                        '1' => 'Diproses Operator',
                        '2' => 'Ditolak / Perlu Revisi',
                        '3' => 'Selesai',
                    ]),
            ])
            ->actions([
                // 1-Klik Konversi ke Log Surat / Draf Cetak
                Action::make('proses_ke_log')
                    ->label('Proses ke Draf Surat')
                    ->icon('heroicon-o-document-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Konversi Permohonan Warga ke Draf Surat')
                    ->modalDescription('Aksi ini akan membuat entri Draf Log Surat baru dan memindahkan status permohonan menjadi "Diproses Operator".')
                    ->visible(fn (PermohonanSurat $record) => $record->status == 0)
                    ->action(function (PermohonanSurat $record) {
                        // Create Draf LogSurat
                        $kodeSurat  = $record->formatSurat->kode_surat ?? '140';
                        $noSuratAuto = $kodeSurat . '/' . sprintf('%03d', rand(1, 999)) . '/DS/' . date('Y');

                        LogSurat::create([
                            'config_id'       => 1,
                            'id_format_surat' => $record->id_surat,
                            'id_pend'         => $record->id_pemohon,
                            'nama_surat'      => $record->formatSurat->nama ?? 'Surat Keterangan',
                            'no_surat'        => $noSuratAuto,
                            'tanggal'         => now(),
                            'bulan'           => date('m'),
                            'tahun'           => date('Y'),
                            'status'          => 1,
                            'isi_surat'       => $record->isian_form,
                            'keterangan'      => $record->keterangan,
                        ]);

                        // Update status permohonan ke Diproses (1)
                        $record->update(['status' => 1]);

                        // Trigger WA Notification
                        app(\App\Services\WhatsappNotificationService::class)->sendSuratNotification($record, 'proses');

                        Notification::make()
                            ->title('✅ Berhasil Dikonversi!')
                            ->body("Permohonan {$record->no_antrian} berhasil dibuatkan Draf Log Surat dengan No. Surat {$noSuratAuto}.")
                            ->success()
                            ->send();
                    }),

                // Tolak / Perlu Revisi
                Action::make('tolak')
                    ->label('Tolak / Revisi')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->form([
                        Textarea::make('alasan')
                            ->label('Alasan Penolakan / Catatan Revisi')
                            ->placeholder('Jelaskan bagian berkas/persyaratan yang perlu diperbaiki warga...')
                            ->required(),
                    ])
                    ->visible(fn (PermohonanSurat $record) => in_array($record->status, [0, 1]))
                    ->action(function (PermohonanSurat $record, array $data) {
                        $record->update([
                            'status' => 2,
                            'alasan' => $data['alasan'],
                        ]);

                        // Trigger WA Notification
                        app(\App\Services\WhatsappNotificationService::class)->sendSuratNotification($record, 'revisi');

                        Notification::make()
                            ->title('Permohonan Ditolak')
                            ->body("Permohonan {$record->no_antrian} ditandai perlu revisi.")
                            ->warning()
                            ->send();
                    }),

                // Tandai Selesai
                Action::make('tandai_selesai')
                    ->label('Tandai Selesai')
                    ->icon('heroicon-o-check-badge')
                    ->color('info')
                    ->visible(fn (PermohonanSurat $record) => $record->status == 1)
                    ->action(function (PermohonanSurat $record) {
                        $record->update(['status' => 3]);

                        // Trigger WA Notification
                        app(\App\Services\WhatsappNotificationService::class)->sendSuratNotification($record, 'selesai');

                        Notification::make()
                            ->title('Permohonan Selesai')
                            ->body("Permohonan {$record->no_antrian} ditandai selesai dan siap diunduh warga.")
                            ->success()
                            ->send();
                    }),

                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-inbox')
            ->emptyStateHeading('Belum Ada Permohonan Surat Online')
            ->emptyStateDescription('Permohonan surat dari warga via Layanan Mandiri akan muncul di sini.');
    }
}
