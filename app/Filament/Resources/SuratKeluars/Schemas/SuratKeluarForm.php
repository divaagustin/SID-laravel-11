<?php

namespace App\Filament\Resources\SuratKeluars\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SuratKeluarForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Data Surat Keluar')
                ->description('Isi informasi surat yang dikirimkan keluar')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('nomor_surat')
                            ->label('Nomor Surat')
                            ->maxLength(35)
                            ->required(),

                        TextInput::make('kode_surat')
                            ->label('Kode Surat')
                            ->maxLength(10),

                        DatePicker::make('tanggal_surat')
                            ->label('Tanggal Surat')
                            ->required()
                            ->default(now()),

                        TextInput::make('tujuan')
                            ->label('Tujuan Surat')
                            ->maxLength(100)
                            ->required(),
                    ]),

                    Textarea::make('isi_singkat')
                        ->label('Isi Singkat / Perihal')
                        ->maxLength(200)
                        ->rows(3),

                    Textarea::make('keterangan')
                        ->label('Keterangan')
                        ->maxLength(500)
                        ->rows(2),

                    TextInput::make('lokasi_arsip')
                        ->label('Lokasi Arsip Fisik')
                        ->maxLength(150),
                ]),

            Section::make('Pengiriman')
                ->schema([
                    Grid::make(2)->schema([
                        Toggle::make('ekspedisi')
                            ->label('Via Ekspedisi / Kurir')
                            ->default(false)
                            ->live(),

                        DatePicker::make('tanggal_pengiriman')
                            ->label('Tanggal Pengiriman')
                            ->visible(fn ($get) => $get('ekspedisi')),
                    ]),

                    TextInput::make('tanda_terima')
                        ->label('Nomor Tanda Terima')
                        ->maxLength(200)
                        ->visible(fn ($get) => $get('ekspedisi')),
                ]),

            Section::make('Berkas')
                ->schema([
                    FileUpload::make('berkas_scan')
                        ->label('File Surat (PDF / Scan)')
                        ->directory('surat-keluar')
                        ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                        ->maxSize(10240)
                        ->downloadable()
                        ->previewable(),
                ]),

            Hidden::make('config_id')->default(1),
        ]);
    }
}
