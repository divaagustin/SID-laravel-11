<?php

namespace App\Filament\Resources\Penduduks\Tables;

use App\Models\PendudukMandiri;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class PenduduksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes())
            ->columns([
                TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono')
                    ->sortable(),

                TextColumn::make('keluarga.no_kk')
                    ->label('No. KK')
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono')
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('nama')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('sex')
                    ->label('Jenis Kelamin')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ((int) $state) {
                        1 => 'Laki-laki',
                        2 => 'Perempuan',
                        default => 'Tidak Diketahui',
                    })
                    ->color(fn ($state) => match ((int) $state) {
                        1 => 'info',
                        2 => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('jenis_warga')
                    ->label('Domisili / Kewarganegaraan')
                    ->badge()
                    ->color(fn ($record) => match (true) {
                        (int) $record->warganegara_id === 2 => 'purple',
                        (int) $record->status === 2 => 'warning',
                        default => 'emerald',
                    }),

                TextColumn::make('status_dasar')
                    ->label('Status Mutasi')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ((int) $state) {
                        1 => 'Hidup (Aktif)',
                        2 => 'Meninggal',
                        3 => 'Pindah',
                        4 => 'Hilang',
                        default => 'Lainnya',
                    })
                    ->color(fn ($state) => match ((int) $state) {
                        1 => 'success',
                        2 => 'danger',
                        3 => 'warning',
                        4 => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('id')
                    ->label('Layanan Mandiri')
                    ->formatStateUsing(function ($record) {
                        $exists = PendudukMandiri::where('id_pend', $record->id)->exists();
                        return $exists ? 'Aktif' : 'Belum Aktif';
                    })
                    ->badge()
                    ->color(function ($record) {
                        $exists = PendudukMandiri::where('id_pend', $record->id)->exists();
                        return $exists ? 'success' : 'gray';
                    }),

                TextColumn::make('tanggallahir')
                    ->label('Tgl. Lahir')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('usia_formatted')
                    ->label('Usia')
                    ->badge()
                    ->color('info')
                    ->sortable(query: fn (Builder $query, string $direction) => $query->orderBy('tanggallahir', $direction === 'asc' ? 'desc' : 'asc')),

                TextColumn::make('suku')
                    ->label('Suku / Etnis')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('tempatlahir')
                    ->label('Tempat Lahir')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('wilayah.dusun')
                    ->label('Dusun / Wilayah')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('success'),

                TextColumn::make('status_kawin')
                    ->label('Status Kawin')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ((int) $state) {
                        1 => 'Belum Kawin',
                        2 => 'Kawin',
                        3 => 'Cerai Hidup',
                        4 => 'Cerai Mati',
                        default => '-',
                    })
                    ->color(fn ($state) => match ((int) $state) {
                        1 => 'gray',
                        2 => 'success',
                        3, 4 => 'danger',
                        default => 'gray',
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('telepon')
                    ->label('Telepon')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('nama')
            ->filters([
                SelectFilter::make('status_dasar')
                    ->label('Status Mutasi / Keberadaan')
                    ->options([
                        1 => 'Hidup (Aktif)',
                        2 => 'Meninggal Dunia',
                        3 => 'Pindah Keluar',
                        4 => 'Hilang',
                    ])
                    ->default(1),

                SelectFilter::make('sex')
                    ->label('Jenis Kelamin')
                    ->options([
                        1 => 'Laki-laki',
                        2 => 'Perempuan',
                    ]),

                SelectFilter::make('status_kawin')
                    ->label('Status Perkawinan')
                    ->options([
                        1 => 'Belum Kawin',
                        2 => 'Kawin',
                        3 => 'Cerai Hidup',
                        4 => 'Cerai Mati',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('reset_pin')
                    ->label('🔑 Reset / Buat PIN Mandiri')
                    ->color('warning')
                    ->icon('heroicon-o-key')
                    ->form([
                        TextInput::make('pin')
                            ->label('PIN Baru Layanan Mandiri')
                            ->required()
                            ->numeric()
                            ->minLength(6)
                            ->maxLength(6)
                            ->default(fn () => str_pad((string) rand(100000, 999999), 6, '0', STR_PAD_LEFT))
                            ->helperText('PIN terdiri dari 6 angka. Berikan PIN ini kepada warga untuk login di Portal Layanan Mandiri.'),
                    ])
                    ->action(function ($record, array $data) {
                        $mandiri = PendudukMandiri::where('id_pend', $record->id)->first();
                        if (!$mandiri) {
                            PendudukMandiri::create([
                                'id_pend'      => $record->id,
                                'config_id'    => $record->config_id ?? 1,
                                'pin'          => Hash::make($data['pin']),
                                'tanggal_buat' => now(),
                                'aktif'        => 1,
                                'ganti_pin'    => 0,
                            ]);
                        } else {
                            $mandiri->update([
                                'pin'       => Hash::make($data['pin']),
                                'aktif'     => 1,
                                'ganti_pin' => 0,
                            ]);
                        }

                        Notification::make()
                            ->title('PIN Layanan Mandiri Berhasil Diatur')
                            ->body("PIN untuk {$record->nama} (NIK: {$record->nik}) adalah: {$data['pin']}")
                            ->persistent()
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
