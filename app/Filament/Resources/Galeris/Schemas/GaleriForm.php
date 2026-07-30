<?php

namespace App\Filament\Resources\Galeris\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GaleriForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Foto Kegiatan Desa')
                ->description('Unggah dokumentasi foto kegiatan masyarakat dan pemerintah desa')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('nama')
                            ->label('Judul Foto / Kegiatan')
                            ->placeholder('Misal: Gotong Royong Dusun II / Musrenbangdes')
                            ->required()
                            ->maxLength(100),

                        FileUpload::make('gambar')
                            ->label('Berkas Foto')
                            ->image()
                            ->directory('galeri')
                            ->imageEditor()
                            ->required(),

                        Toggle::make('enabled')
                            ->label('Tampilkan di Galeri Publik')
                            ->default(true),

                        Toggle::make('slider')
                            ->label('Tampilkan di Slider Beranda')
                            ->default(false),
                    ]),
                ]),
        ]);
    }
}
