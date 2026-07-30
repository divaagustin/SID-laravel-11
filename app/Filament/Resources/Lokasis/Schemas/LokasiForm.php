<?php

namespace App\Filament\Resources\Lokasis\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LokasiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Detail Penanda Lokasi GIS')
                ->description('Input koordinat titik dan informasi fasilitas umum desa')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('nama')
                            ->label('Nama Lokasi / Fasilitas')
                            ->placeholder('Misal: Kantor Desa / Puskesmas Pembantu')
                            ->required()
                            ->maxLength(100),

                        Select::make('ref_point')
                            ->label('Kategori Penanda')
                            ->relationship('kategoriPoint', 'nama')
                            ->searchable()
                            ->preload(),

                        TextInput::make('lat')
                            ->label('Latitude (Garis Lintang)')
                            ->placeholder('-3.023456')
                            ->required(),

                        TextInput::make('lng')
                            ->label('Longitude (Garis Bujur)')
                            ->placeholder('99.612345')
                            ->required(),

                        FileUpload::make('foto')
                            ->label('Foto Lokasi')
                            ->image()
                            ->directory('gis/lokasi')
                            ->imageEditor(),

                        Toggle::make('enabled')
                            ->label('Tampilkan di Peta')
                            ->default(true),
                    ]),

                    Textarea::make('desk')
                        ->label('Deskripsi / Keterangan Lokasi')
                        ->rows(3),
                ]),
        ]);
    }
}
