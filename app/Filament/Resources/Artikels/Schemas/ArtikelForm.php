<?php

namespace App\Filament\Resources\Artikels\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ArtikelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Konten Berita & Artikel')
                ->description('Tulis dan kelola artikel publik desa')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('judul')
                            ->label('Judul Berita')
                            ->required()
                            ->maxLength(200)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

                        TextInput::make('slug')
                            ->label('Slug URL')
                            ->required()
                            ->maxLength(200),

                        Select::make('id_kategori')
                            ->label('Kategori')
                            ->relationship('kategori', 'kategori')
                            ->searchable()
                            ->preload()
                            ->required(),

                        FileUpload::make('gambar')
                            ->label('Gambar Utama / Thumbnail')
                            ->image()
                            ->disk('public')
                            ->directory('artikel')
                            ->imageEditor(),
                    ]),

                    RichEditor::make('isi')
                        ->label('Isi Berita')
                        ->required()
                        ->columnSpanFull(),

                    Grid::make(3)->schema([
                        Toggle::make('enabled')
                            ->label('Publikasikan')
                            ->default(true),

                        Toggle::make('headline')
                            ->label('Jadikan Headline Berita')
                            ->default(false),

                        Toggle::make('slider')
                            ->label('Tampilkan di Slider Beranda')
                            ->default(false),
                    ]),
                ]),
        ]);
    }
}
