<?php

namespace App\Filament\Resources\Penduduks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class PendudukForm
{
    public static function configure($schema)
    {
        return $schema
            ->components([
                Hidden::make('config_id')
                    ->default(1),

                Section::make('Identitas Utama & NIK')
                    ->schema([
                        TextInput::make('nik')
                            ->label('NIK (Nomor Induk Kependudukan)')
                            ->required()
                            ->length(16)
                            ->unique('tweb_penduduk', 'nik', ignoreRecord: true)
                            ->validationMessages([
                                'unique' => 'NIK ini sudah terdaftar dalam master kependudukan desa.',
                                'length' => 'NIK harus tepat 16 digit angka.',
                            ])
                            ->extraInputAttributes(['type' => 'text', 'inputmode' => 'numeric', 'pattern' => '[0-9]*'])
                            ->placeholder('Contoh: 1209181203940001'),

                        Select::make('id_kk')
                            ->label('No. Kartu Keluarga (KK)')
                            ->options(fn () => \App\Models\Keluarga::pluck('no_kk', 'id'))
                            ->searchable()
                            ->placeholder('Pilih / Cari No. KK (Biarkan kosong jika belum ber-KK)')
                            ->native(false),

                        TextInput::make('nama')
                            ->label('Nama Lengkap (Sesuai KTP)')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Contoh: Ahmad Subagyo'),

                        Select::make('sex')
                            ->label('Jenis Kelamin')
                            ->options([
                                1 => 'Laki-laki',
                                2 => 'Perempuan',
                            ])
                            ->required()
                            ->native(false),

                        TextInput::make('tempatlahir')
                            ->label('Tempat Lahir')
                            ->required()
                            ->placeholder('Contoh: Medan'),

                        DatePicker::make('tanggallahir')
                            ->label('Tanggal Lahir')
                            ->required()
                            ->native(false),

                        TextInput::make('suku')
                            ->label('Suku / Etnis Penduduk')
                            ->placeholder('Contoh: Batak, Jawa, Melayu, Sunda, Minang, Nias, Karo, Dll.')
                            ->maxLength(150),

                        Select::make('kk_level')
                            ->label('Hubungan Dalam Keluarga (SHDK)')
                            ->options([
                                1 => 'Kepala Keluarga',
                                2 => 'Suami',
                                3 => 'Istri',
                                4 => 'Anak',
                                5 => 'Menantu',
                                6 => 'Cucu',
                                7 => 'Orangtua',
                                8 => 'Mertua',
                                9 => 'Famili Lain',
                                10 => 'Pembantu',
                                11 => 'Lainnya',
                            ])
                            ->default(4)
                            ->required()
                            ->native(false),
                    ])->columns(2),

                Section::make('Biodata Status Demografi')
                    ->schema([
                        Select::make('agama_id')
                            ->label('Agama & Kepercayaan')
                            ->options(fn () => \Illuminate\Support\Facades\DB::table('tweb_penduduk_agama')->pluck('nama', 'id'))
                            ->default(1)
                            ->required()
                            ->native(false),

                        Select::make('pendidikan_kk_id')
                            ->label('Pendidikan Terakhir (Dalam KK)')
                            ->options(fn () => \Illuminate\Support\Facades\DB::table('tweb_penduduk_pendidikan_kk')->pluck('nama', 'id'))
                            ->default(1)
                            ->required()
                            ->native(false),

                        Select::make('pekerjaan_id')
                            ->label('Pekerjaan Utama')
                            ->options(fn () => \Illuminate\Support\Facades\DB::table('tweb_penduduk_pekerjaan')->pluck('nama', 'id'))
                            ->searchable()
                            ->default(1)
                            ->required()
                            ->native(false),

                        Select::make('status_kawin')
                            ->label('Status Perkawinan')
                            ->options([
                                1 => 'Belum Kawin',
                                2 => 'Kawin',
                                3 => 'Cerai Hidup',
                                4 => 'Cerai Mati',
                            ])
                            ->default(1)
                            ->required()
                            ->native(false),

                        Select::make('golongan_darah_id')
                            ->label('Golongan Darah')
                            ->options([
                                1 => 'A',
                                2 => 'B',
                                3 => 'AB',
                                4 => 'O',
                                5 => 'A+',
                                6 => 'A-',
                                7 => 'B+',
                                8 => 'B-',
                                9 => 'AB+',
                                10 => 'AB-',
                                11 => 'O+',
                                12 => 'O-',
                                13 => 'Tidak Tahu',
                            ])
                            ->default(13)
                            ->required()
                            ->native(false),

                        Select::make('id_cluster')
                            ->label('Dusun / Wilayah RT-RW')
                            ->options(fn () => \Illuminate\Support\Facades\DB::table('tweb_wil_clusterdesa')->whereNotNull('dusun')->where('dusun', '!=', '')->pluck('dusun', 'id'))
                            ->searchable()
                            ->native(false),

                        Select::make('status_dasar')
                            ->label('Status Keberadaan (Mutasi)')
                            ->options([
                                1 => 'Hidup (Aktif)',
                                2 => 'Meninggal Dunia',
                                3 => 'Pindah Keluar',
                                4 => 'Hilang',
                            ])
                            ->default(1)
                            ->required()
                            ->native(false),

                        Select::make('status')
                            ->label('Status Domisili Desa')
                            ->options([
                                1 => 'Warga Tetap (WNI Desa)',
                                2 => 'Warga Luar Desa (Penduduk Tidak Tetap / Domisili Sementara)',
                            ])
                            ->default(1)
                            ->required()
                            ->native(false)
                            ->helperText('Pilih Warga Luar Desa jika KTP/KK penduduk berasal dari luar desa tapi berdomisili sementara.'),

                        Select::make('warganegara_id')
                            ->label('Status Kewarganegaraan')
                            ->options([
                                1 => 'WNI (Warga Negara Indonesia)',
                                2 => 'WNA (Warga Negara Asing)',
                            ])
                            ->default(1)
                            ->required()
                            ->native(false),

                        FileUpload::make('foto')
                            ->label('Foto Paspor/KTP Penduduk')
                            ->image()
                            ->directory('penduduk')
                            ->visibility('public'),
                    ])->columns(2),

                Section::make('Kontak & Orang Tua')
                    ->schema([
                        TextInput::make('alamat_sekarang')
                            ->label('Alamat Tempat Tinggal Sekarang')
                            ->placeholder('Contoh: Dusun I Desa Serdang, RT 001 / RW 002'),

                        TextInput::make('telepon')
                            ->label('Nomor Telepon / WhatsApp')
                            ->tel()
                            ->placeholder('081234567890'),

                        TextInput::make('nama_ayah')
                            ->label('Nama Ayah Kandung')
                            ->default('-'),

                        TextInput::make('nama_ibu')
                            ->label('Nama Ibu Kandung')
                            ->default('-'),
                    ])->columns(2),
            ]);
    }
}
