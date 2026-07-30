<?php

namespace App\Filament\Resources\DokumenPubliks\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DokumenPublikForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Dokumen Transparansi Publik & Peraturan Desa')
                ->description('Unggah dokumen publik seperti APBDDes, Peraturan Desa, dan Laporan Kinerja')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('nama')
                            ->label('Nama / Judul Dokumen')
                            ->placeholder('Misal: Peraturan Desa No. 01 Tahun 2026 tentang APBDDes')
                            ->required()
                            ->maxLength(200),

                        Select::make('kategori_info_publik')
                            ->label('Kategori Informasi Publik')
                            ->options([
                                1 => 'Informasi Berkala (APBDDes, Laporan Kinerja)',
                                2 => 'Informasi Serta-Merta (Tanggap Bencana, Kebencanaan)',
                                3 => 'Informasi Setiap Saat (Perdes, SK Kades, Standar Pelayanan)',
                            ])
                            ->default(1)
                            ->required(),

                        TextInput::make('tahun')
                            ->label('Tahun Anggaran / Terbit')
                            ->numeric()
                            ->default((int) date('Y'))
                            ->required(),

                        FileUpload::make('satuan')
                            ->label('Berkas Dokumen (PDF, Word, Excel, ZIP)')
                            ->directory('dokumen_publik')
                            ->preserveFilenames()
                            ->required(),

                        Toggle::make('enabled')
                            ->label('Publikasikan Dokumen')
                            ->default(true),
                    ]),

                    Textarea::make('keterangan')
                        ->label('Catatan / Keterangan Dokumen')
                        ->rows(3),
                ]),
        ]);
    }
}
