<?php

namespace App\Filament\Resources\InventarisAssets\Schemas;

use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InventarisAssetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('config_id')->default(1),

                Section::make('Identitas Barang / Aset Desa')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('nama_barang')
                                ->label('Nama Barang / Aset')
                                ->placeholder('Contoh: Laptop HP Pavillion, Meja Kantor Kades')
                                ->required(),
                            TextInput::make('kode_barang')
                                ->label('Kode Barang (Kode Aset)')
                                ->placeholder('Contoh: 02.06.01.01')
                                ->required(),
                            TextInput::make('register')
                                ->label('Nomor Register')
                                ->default('0001')
                                ->required(),
                            Select::make('jenis')
                                ->label('Kategori / Jenis Aset')
                                ->options([
                                    'Peralatan Mesin' => 'Peralatan & Mesin (KIP B)',
                                    'Gedung Bangunan' => 'Gedung & Bangunan (KIP C)',
                                    'Jalan Irigasi'   => 'Jalan, Irigasi & Jaringan (KIP D)',
                                    'Aset Lainnya'    => 'Buku / Kesenian / Hewan (KIP E)',
                                ])
                                ->required(),
                            TextInput::make('jumlah')
                                ->label('Jumlah Unit')
                                ->numeric()
                                ->default(1)
                                ->required(),
                            TextInput::make('harga')
                                ->label('Harga Perolehan (Rp)')
                                ->numeric()
                                ->prefix('Rp')
                                ->required(),
                            TextInput::make('tahun_pengadaan')
                                ->label('Tahun Pengadaan')
                                ->numeric()
                                ->default(date('Y'))
                                ->required(),
                            Select::make('asal')
                                ->label('Asal Usul Perolehan')
                                ->options([
                                    'Pembelian APBDes' => 'Pembelian APBDes',
                                    'Hibah / Sumbangan' => 'Hibah / Sumbangan',
                                    'Bantuan Pemerintah' => 'Bantuan Pemerintah',
                                ])
                                ->required(),
                        ]),
                        Textarea::make('keterangan')
                            ->label('Keterangan / Kondisi Barang (Baik / Rusak)')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
