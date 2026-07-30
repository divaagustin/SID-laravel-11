<?php

namespace App\Filament\Resources\SuratMasuks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SuratMasukForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Surat Masuk')
                ->description('Isi data surat yang diterima')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('nomor_surat')
                            ->label('Nomor Surat')
                            ->maxLength(35)
                            ->required(),

                        TextInput::make('kode_surat')
                            ->label('Kode Surat')
                            ->maxLength(10),

                        DatePicker::make('tanggal_penerimaan')
                            ->label('Tanggal Penerimaan')
                            ->required()
                            ->default(now()),

                        DatePicker::make('tanggal_surat')
                            ->label('Tanggal Surat')
                            ->required(),
                    ]),

                    TextInput::make('pengirim')
                        ->label('Pengirim')
                        ->maxLength(100)
                        ->required(),

                    Textarea::make('isi_singkat')
                        ->label('Isi Singkat / Perihal')
                        ->maxLength(200)
                        ->rows(3),

                    Textarea::make('isi_disposisi')
                        ->label('Catatan Disposisi Awal')
                        ->maxLength(200)
                        ->rows(2),

                    TextInput::make('lokasi_arsip')
                        ->label('Lokasi Arsip Fisik')
                        ->maxLength(150),
                ]),

            Section::make('Berkas Scan')
                ->description('Upload file scan surat dalam format PDF, JPG, atau PNG (maks. 10MB)')
                ->schema([
                    FileUpload::make('berkas_scan')
                        ->label('File Scan Surat')
                        ->directory('surat-masuk')
                        ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                        ->maxSize(10240)
                        ->downloadable()
                        ->previewable(),
                ]),

            Hidden::make('config_id')->default(1),
        ]);
    }
}
