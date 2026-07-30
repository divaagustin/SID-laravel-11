<?php

namespace App\Filament\Resources\PermohonanSurats\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PermohonanSuratForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Detail Permohonan Surat')
                ->description('Informasi permohonan surat online yang diajukan warga')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('no_antrian')
                            ->label('Nomor Resi / Antrean')
                            ->disabled(),

                        Select::make('status')
                            ->label('Status Permohonan')
                            ->options([
                                0 => '⏳ Menunggu Verifikasi',
                                1 => '📝 Diproses Operator',
                                2 => '❌ Ditolak / Perlu Revisi',
                                3 => '✅ Selesai (TTE / Siap Diunduh)',
                            ])
                            ->required(),

                        TextInput::make('no_hp_aktif')
                            ->label('No. WhatsApp Warga')
                            ->required(),

                        TextInput::make('alasan')
                            ->label('Catatan Alasan Penolakan / Revisi')
                            ->placeholder('Isi jika permohonan ditolak'),
                    ]),

                    Textarea::make('keterangan')
                        ->label('Keperluan / Tujuan Pembuatan Surat')
                        ->rows(3),
                ]),
        ]);
    }
}
