<?php

namespace App\Filament\Resources\LogSurats\Schemas;

use App\Models\Penduduk;
use App\Models\SuratFormat;
use App\Models\Pamong;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\KeyValue;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;

class LogSuratForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        Section::make('Pemohon & Jenis Surat')
                            ->description('Pilih pemohon surat dan format surat yang ingin dibuat.')
                            ->columnSpan(2)
                            ->schema([
                                Select::make('id_pend')
                                    ->label('Pilih Warga Pemohon')
                                    ->relationship('penduduk', 'nama')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $pend = Penduduk::find($state);
                                        if ($pend) {
                                            $set('pemohon', $pend->nama);
                                        }
                                    }),

                                Select::make('id_format_surat')
                                    ->label('Jenis Surat')
                                    ->relationship('formatSurat', 'nama')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $format = SuratFormat::find($state);
                                        if ($format) {
                                            $set('nama_surat', $format->nama);
                                        }
                                    }),

                                Grid::make(2)->schema([
                                    TextInput::make('no_surat')
                                        ->label('Nomor Surat')
                                        ->placeholder('Contoh: 140/024/MR-2026')
                                        ->required(),

                                    DateTimePicker::make('tanggal')
                                        ->label('Tanggal Buat')
                                        ->default(Carbon::now())
                                        ->required(),
                                ]),
                            ]),

                        Section::make('Penandatangan (Pamong)')
                            ->description('Pilih pamong yang akan menandatangani dokumen ini.')
                            ->columnSpan(1)
                            ->schema([
                                Select::make('id_pamong')
                                    ->label('Nama Pamong')
                                    ->options(Pamong::all()->pluck('pamong_nama', 'pamong_id'))
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $pamong = Pamong::find($state);
                                        if ($pamong) {
                                            $set('nama_pamong', $pamong->pamong_nama);
                                            // Asumsikan nama jabatan diisi manual atau nanti diotomatisasi
                                            $set('nama_jabatan', 'Kepala Desa');
                                        }
                                    }),

                                TextInput::make('nama_pamong')
                                    ->label('Nama Pamong (Tersimpan)')
                                    ->disabled()
                                    ->dehydrated(),

                                TextInput::make('nama_jabatan')
                                    ->label('Jabatan Pamong')
                                    ->required()
                                    ->placeholder('Contoh: Kepala Desa / Sekretaris Desa'),
                            ]),

                        Section::make('Formulir Data Dinamis Surat')
                            ->description('Isi variabel kustom pendukung isi surat di bawah ini (Key: Nama Variabel, Value: Isi Variabel).')
                            ->columnSpan(3)
                            ->schema([
                                KeyValue::make('isi_surat')
                                    ->label('Isian Konten Surat')
                                    ->keyLabel('Nama Variabel (Contoh: Jenis Usaha, Alamat Usaha)')
                                    ->valueLabel('Isi Nilai Variabel')
                                    ->addButtonLabel('Tambah Isian Surat')
                                    ->default([
                                        'keperluan' => 'Untuk melengkapi persyaratan administrasi.',
                                    ])
                                    ->required(),
                            ]),
                    ]),
            ]);
    }
}
