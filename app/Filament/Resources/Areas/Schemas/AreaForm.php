<?php

namespace App\Filament\Resources\Areas\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AreaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Detail Area Batas Wilayah')
                ->description('Input nama area dan koordinat polygon GeoJSON / path')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('nama')
                            ->label('Nama Area / Wilayah')
                            ->placeholder('Misal: Wilayah Dusun I / Hutan Desa')
                            ->required()
                            ->maxLength(100),

                        Toggle::make('enabled')
                            ->label('Tampilkan di Peta')
                            ->default(true),
                    ]),

                    Textarea::make('path')
                        ->label('Koordinat Path Polygon (JSON Array [[lat, lng], ...])')
                        ->rows(5)
                        ->required(),

                    Textarea::make('desk')
                        ->label('Deskripsi / Keterangan Area')
                        ->rows(3),
                ]),
        ]);
    }
}
