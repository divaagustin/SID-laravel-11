<?php

namespace App\Filament\Resources\AparaturDesas\Schemas;

use App\Models\Jabatan;
use App\Models\Penduduk;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Set;
use Filament\Schemas\Schema;

class AparaturDesaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('config_id')->default(1),

                Section::make('Pilih dari Data Penduduk (Opsional / Terhubung)')
                    ->description('Cari warga desa berdasarkan NIK atau Nama untuk mengisi identitas dasar secara otomatis.')
                    ->schema([
                        Select::make('id_pend')
                            ->label('Cari Penduduk Desa')
                            ->placeholder('Ketik NIK atau Nama Penduduk...')
                            ->searchable()
                            ->getSearchResultsUsing(fn (string $search): array => 
                                Penduduk::where('nama', 'like', "%{$search}%")
                                    ->orWhere('nik', 'like', "%{$search}%")
                                    ->limit(20)
                                    ->pluck('nama', 'id')
                                    ->mapWithKeys(fn ($nama, $id) => [
                                        $id => $nama . ' (' . Penduduk::find($id)?->nik . ')'
                                    ])
                                    ->toArray()
                            )
                            ->getOptionLabelUsing(fn ($value): ?string => 
                                Penduduk::find($value) ? Penduduk::find($value)->nama . ' (' . Penduduk::find($value)->nik . ')' : null
                            )
                            ->reactive()
                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                if ($state) {
                                    $penduduk = Penduduk::find($state);
                                    if ($penduduk) {
                                        $set('pamong_nama', $penduduk->nama);
                                        $set('pamong_nik', $penduduk->nik);
                                        $set('pamong_tempatlahir', $penduduk->tempatlahir);
                                        $set('pamong_tanggallahir', $penduduk->tanggallahir?->format('Y-m-d'));
                                        $set('pamong_sex', $penduduk->sex);
                                    }
                                }
                            }),
                    ]),

                Section::make('Identitas Staf / Perangkat Desa')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('pamong_nama')
                                ->label('Nama Lengkap')
                                ->required(),
                            TextInput::make('pamong_nik')
                                ->label('NIK')
                                ->length(16),
                            TextInput::make('gelar_depan')->label('Gelar Depan'),
                            TextInput::make('gelar_belakang')->label('Gelar Belakang'),
                            TextInput::make('pamong_tempatlahir')->label('Tempat Lahir'),
                            DatePicker::make('pamong_tanggallahir')->label('Tanggal Lahir'),
                        ]),
                    ]),

                Section::make('Jabatan & Status Kepegawaian')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('jabatan_id')
                                ->label('Jabatan Desa')
                                ->options(fn () => Jabatan::orderBy('id')->pluck('nama', 'id'))
                                ->searchable()
                                ->required()
                                ->createOptionForm([
                                    TextInput::make('nama')
                                        ->label('Nama Jabatan Baru')
                                        ->required()
                                        ->placeholder('Contoh: Kepala Sub Bagian Umum / Pengelola Keuangan'),
                                ])
                                ->createOptionUsing(function (array $data) {
                                    $jabatan = Jabatan::create([
                                        'config_id' => 1,
                                        'nama' => $data['nama'],
                                        'jenis' => 3,
                                    ]);
                                    return $jabatan->id;
                                }),
                            TextInput::make('pamong_nip')->label('NIP / NIPD'),
                            TextInput::make('pamong_niap')->label('NIAP')->default('0'),
                            TextInput::make('pamong_nosk')->label('Nomor SK Pengangkatan'),
                            DatePicker::make('pamong_tglsk')->label('Tanggal SK'),
                            TextInput::make('pamong_masajab')->label('Masa Jabatan'),
                        ]),
                        Grid::make(3)->schema([
                            Toggle::make('pamong_status')
                                ->label('Status Aktif')
                                ->default(true),
                            Toggle::make('tampilkan_beranda')
                                ->label('Tampilkan di Beranda')
                                ->helperText('Tampilkan foto & nama aparatur ini di Beranda.')
                                ->default(true),
                            Toggle::make('tampilkan_struktur')
                                ->label('Tampilkan di Struktur Organisasi')
                                ->helperText('Tampilkan di bagan Struktur Organisasi (Tentang Desa).')
                                ->default(true),
                        ]),
                        Toggle::make('pamong_ttd')
                            ->label('Memiliki Hak Tanda Tangan (TTE/Manual)')
                            ->helperText('Aktifkan jika perangkat desa ini berwenang mengesahkan/menandatangani surat.'),
                        FileUpload::make('foto')
                            ->label('Foto Resmi Perangkat Desa')
                            ->image()
                            ->directory('pamong')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
