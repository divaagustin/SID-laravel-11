<?php

namespace App\Filament\Resources\Keluargas\Schemas;

use App\Models\Penduduk;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\DB;

class KeluargaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('config_id')
                    ->default(1),

                Section::make('Identitas Kartu Keluarga')
                    ->schema([
                        TextInput::make('no_kk')
                            ->label('No. Kartu Keluarga (KK)')
                            ->required()
                            ->length(16)
                            ->unique('tweb_keluarga', 'no_kk', ignoreRecord: true)
                            ->extraInputAttributes(['type' => 'text', 'inputmode' => 'numeric', 'pattern' => '[0-9]*'])
                            ->placeholder('Contoh: 1209181203940000'),

                        Select::make('nik_kepala')
                            ->label('Kepala Keluarga (Cari NIK / Nama Warga)')
                            ->options(fn () => Penduduk::withoutGlobalScopes()->pluck('nama', 'nik'))
                            ->searchable()
                            ->placeholder('Pilih Warga Kepala Keluarga')
                            ->native(false),

                        Select::make('id_cluster')
                            ->label('Dusun / Wilayah RT-RW')
                            ->options(fn () => DB::table('tweb_wil_clusterdesa')->whereNotNull('dusun')->where('dusun', '!=', '')->pluck('dusun', 'id'))
                            ->searchable()
                            ->native(false),

                        Select::make('kelas_sosial')
                            ->label('Tingkat Kesejahteraan / Kelas Sosial')
                            ->options([
                                1 => 'Sangat Miskin (Desil 1)',
                                2 => 'Miskin (Desil 2)',
                                3 => 'Hampir Miskin (Desil 3)',
                                4 => 'Sejahtera / Menengah',
                            ])
                            ->default(4)
                            ->native(false),

                        TextInput::make('alamat')
                            ->label('Alamat Rumah KK')
                            ->placeholder('Contoh: Dusun I RT 001/RW 002'),

                        DatePicker::make('tgl_daftar')
                            ->label('Tanggal Terdaftar')
                            ->default(now())
                            ->native(false),
                    ])->columns(2),
            ]);
    }
}
