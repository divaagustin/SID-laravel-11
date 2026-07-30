<?php

namespace App\Filament\Resources\Wilayahs\Schemas;

use App\Models\Penduduk;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class WilayahForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('config_id')
                    ->default(1),

                Section::make('Informasi Wilayah Administrasi Desa')
                    ->description('Masukkan data nama Dusun, nomor RW, nomor RT, dan pejabat Kepala Dusun/RT/RW.')
                    ->schema([
                        TextInput::make('dusun')
                            ->label('Nama Dusun / Lingkungan')
                            ->placeholder('Contoh: Dusun I, Dusun II, Dusun Krajan')
                            ->required()
                            ->maxLength(100),

                        TextInput::make('rw')
                            ->label('Nomor RW')
                            ->placeholder('Contoh: 01, 02')
                            ->default('01')
                            ->required()
                            ->maxLength(10),

                        TextInput::make('rt')
                            ->label('Nomor RT')
                            ->placeholder('Contoh: 01, 02, 001')
                            ->default('01')
                            ->required()
                            ->maxLength(10),

                        Select::make('id_kepala')
                            ->label('Kepala Dusun / Ketua RT / Ketua RW')
                            ->placeholder('Cari nama atau NIK warga...')
                            ->options(fn () => Penduduk::withoutGlobalScopes()
                                ->select('id', 'nama', 'nik')
                                ->orderBy('nama')
                                ->get()
                                ->pluck('nama_nik', 'id')
                            )
                            ->searchable()
                            ->native(false)
                            ->helperText('Pilih nama warga yang menjabat sebagai Kepala Dusun / Ketua RT/RW.'),
                    ])->columns(2),

                Section::make('Koordinat Lokasi Peta (Opsional)')
                    ->description('Titik lokasi geografis pusat wilayah untuk pemetaan Peta Desa.')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        TextInput::make('lat')
                            ->label('Latitude (Lintang)')
                            ->placeholder('Contoh: 3.5952'),

                        TextInput::make('lng')
                            ->label('Longitude (Bujur)')
                            ->placeholder('Contoh: 98.6722'),
                    ])->columns(2),
            ]);
    }
}
