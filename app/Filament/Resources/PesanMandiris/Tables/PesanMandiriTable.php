<?php

namespace App\Filament\Resources\PesanMandiris\Tables;

use App\Models\PesanMandiri;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PesanMandiriTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('penduduk.nama')
                    ->label('Nama Warga')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('subjek')
                    ->label('Subjek Pengaduan')
                    ->searchable()
                    ->limit(40),

                TextColumn::make('komentar')
                    ->label('Isi Pesan')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->komentar),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ((int) $state) {
                        1 => '📩 Baru',
                        2 => '💬 Dibalas',
                        3 => '✅ Selesai',
                        default => 'Pending',
                    })
                    ->color(fn ($state) => match ((int) $state) {
                        1 => 'danger',
                        2 => 'info',
                        3 => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        '1' => 'Baru / Belum Dibaca',
                        '2' => 'Sudah Dibalas',
                        '3' => 'Selesai',
                    ]),
            ])
            ->actions([
                // Balas Pengaduan Modal
                Action::make('balas_pengaduan')
                    ->label('Balas Pesan')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('info')
                    ->form([
                        Textarea::make('permohonan')
                            ->label('Pesan Balasan dari Desa')
                            ->placeholder('Tulis pesan tanggapan / tindak lanjut untuk warga...')
                            ->rows(4)
                            ->required(),
                    ])
                    ->action(function (PesanMandiri $record, array $data) {
                        $record->update([
                            'permohonan' => $data['permohonan'],
                            'status'     => 2, // Sudah dibalas
                        ]);

                        $namaWarga = $record->penduduk->nama ?? 'Warga';

                        Notification::make()
                            ->title('Balasan Dikirim')
                            ->body("Pengaduan dari {$namaWarga} telah dibalas.")
                            ->success()
                            ->send();
                    }),

                // Tandai Selesai
                Action::make('tandai_selesai')
                    ->label('Tandai Selesai')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (PesanMandiri $record) => $record->status != 3)
                    ->action(function (PesanMandiri $record) {
                        $record->update(['status' => 3]);

                        Notification::make()
                            ->title('Pengaduan Selesai')
                            ->body('Status pengaduan ditandai selesai.')
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
            ->emptyStateIcon('heroicon-o-chat-bubble-left-right')
            ->emptyStateHeading('Belum Ada Pengaduan Warga')
            ->emptyStateDescription('Pengaduan & aspirasi yang dikirim warga via Layanan Mandiri akan tampil di sini.');
    }
}
