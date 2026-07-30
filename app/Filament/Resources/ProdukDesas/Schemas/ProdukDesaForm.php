<?php

namespace App\Filament\Resources\ProdukDesas\Schemas;

use App\Models\Penduduk;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProdukDesaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('config_id')->default(1),

                Section::make('Informasi Produk Usaha / BUMDes')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('nama')
                                ->label('Nama Produk / Komoditas')
                                ->placeholder('Contoh: Keripik Pisang Serdang, Beras Organik')
                                ->required(),
                            TextInput::make('harga')
                                ->label('Harga Jual (Rp)')
                                ->numeric()
                                ->prefix('Rp')
                                ->required(),
                            TextInput::make('satuan')
                                ->label('Satuan Unit (Kg, Pcs, Bungkus)')
                                ->default('Pcs')
                                ->required(),
                            Select::make('id_pelapak')
                                ->label('Warga Pelapak / Pemilik')
                                ->placeholder('Cari nama pelapak...')
                                ->searchable()
                                ->getSearchResultsUsing(fn (string $search): array => 
                                    Penduduk::withoutGlobalScopes()
                                        ->where(fn ($q) => $q->where('nama', 'like', "%{$search}%")->orWhere('nik', 'like', "%{$search}%"))
                                        ->limit(20)
                                        ->pluck('nama', 'id')
                                        ->mapWithKeys(fn ($nama, $id) => [$id => $nama . ' (' . Penduduk::find($id)?->nik . ')'])
                                        ->toArray()
                                )
                                ->getOptionLabelUsing(fn ($value): ?string => 
                                    Penduduk::find($value) ? Penduduk::find($value)->nama . ' (' . Penduduk::find($value)->nik . ')' : null
                                ),
                        ]),
                        FileUpload::make('foto')
                            ->label('Foto Produk Usaha')
                            ->image()
                            ->directory('produk')
                            ->columnSpanFull(),
                        Textarea::make('deskripsi')
                            ->label('Deskripsi Keunggulan Produk')
                            ->rows(3)
                            ->columnSpanFull(),
                        Toggle::make('status')
                            ->label('Tampilkan di Lapak Desa (Publik)')
                            ->default(true),
                    ]),
            ]);
    }
}
