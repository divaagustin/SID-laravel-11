<?php

namespace App\Filament\Resources\Persils\Schemas;

use App\Models\Cdesa;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\DB;

class PersilForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('config_id')->default(1),

                Section::make('Informasi Bidang Persil Tanah')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('nomor')
                                ->label('Nomor Persil')
                                ->placeholder('Contoh: 124')
                                ->required(),
                            TextInput::make('nomor_urut_bidang')
                                ->label('Nomor Urut Bidang (NUB)')
                                ->numeric()
                                ->default(1),
                            Select::make('cdesa_awal')
                                ->label('Terhubung ke C-Desa Awal')
                                ->options(Cdesa::pluck('nomor', 'id'))
                                ->searchable(),
                            Select::make('kelas')
                                ->label('Kelas & Kategori Tanah Persil')
                                ->options(function () {
                                    return DB::table('ref_persil_kelas')->get()->mapWithKeys(function ($item) {
                                        return [$item->id => "{$item->kode} ({$item->tipe} - {$item->ndesc})"];
                                    });
                                })
                                ->searchable()
                                ->default(1)
                                ->required()
                                ->native(false),
                            TextInput::make('luas_persil')
                                ->label('Luas Persil (m²)')
                                ->numeric()
                                ->suffix('m²')
                                ->required(),
                            Select::make('id_wilayah')
                                ->label('Lokasi Wilayah / Dusun')
                                ->options(fn () => DB::table('tweb_wil_clusterdesa')->whereNotNull('dusun')->where('dusun', '!=', '')->pluck('dusun', 'id'))
                                ->searchable(),
                        ]),
                        Textarea::make('lokasi')
                            ->label('Keterangan / Alamat Batas Lokasi Persil')
                            ->rows(2)
                            ->columnSpanFull(),
                        Toggle::make('is_publik')
                            ->label('Tampilkan di Peta GIS Publik')
                            ->default(true),
                    ]),
            ]);
    }
}
