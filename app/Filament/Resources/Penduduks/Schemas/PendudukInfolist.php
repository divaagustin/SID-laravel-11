<?php

namespace App\Filament\Resources\Penduduks\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\DB;

class PendudukInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Utama Kependudukan')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('nik')
                                ->label('NIK (Nomor Induk Kependudukan)')
                                ->fontFamily('mono')
                                ->weight('bold')
                                ->copyable(),

                            TextEntry::make('nama')
                                ->label('Nama Lengkap')
                                ->weight('bold'),

                            TextEntry::make('keluarga.no_kk')
                                ->label('No. Kartu Keluarga (KK)')
                                ->fontFamily('mono')
                                ->copyable()
                                ->placeholder('Belum Terhubung ke KK'),

                            TextEntry::make('sex')
                                ->label('Jenis Kelamin')
                                ->badge()
                                ->formatStateUsing(fn ($state) => match ((int) $state) {
                                    1 => 'Laki-laki',
                                    2 => 'Perempuan',
                                    default => '-',
                                })
                                ->color(fn ($state) => match ((int) $state) {
                                    1 => 'info',
                                    2 => 'warning',
                                    default => 'gray',
                                }),

                            TextEntry::make('tempatlahir')
                                ->label('Tempat Lahir'),

                            TextEntry::make('tanggallahir')
                                ->label('Tanggal Lahir')
                                ->date('d F Y'),

                            TextEntry::make('usia_formatted')
                                ->label('Usia Saat Ini')
                                ->badge()
                                ->color('info'),

                            TextEntry::make('suku')
                                ->label('Suku / Etnis')
                                ->placeholder('-'),

                            TextEntry::make('status_dasar')
                                ->label('Status Mutasi / Keberadaan')
                                ->badge()
                                ->formatStateUsing(fn ($state) => match ((int) $state) {
                                    1 => 'Hidup (Aktif)',
                                    2 => 'Meninggal Dunia',
                                    3 => 'Pindah Keluar',
                                    4 => 'Hilang',
                                    default => '-',
                                })
                                ->color(fn ($state) => match ((int) $state) {
                                    1 => 'success',
                                    2 => 'danger',
                                    3 => 'warning',
                                    default => 'gray',
                                }),

                            TextEntry::make('status_kawin')
                                ->label('Status Perkawinan')
                                ->formatStateUsing(fn ($state) => match ((int) $state) {
                                    1 => 'Belum Kawin',
                                    2 => 'Kawin',
                                    3 => 'Cerai Hidup',
                                    4 => 'Cerai Mati',
                                    default => '-',
                                }),

                            TextEntry::make('agama_id')
                                ->label('Agama')
                                ->formatStateUsing(function ($state) {
                                    $agama = DB::table('tweb_penduduk_agama')->find($state);
                                    return $agama->nama ?? '-';
                                }),

                            TextEntry::make('pendidikan_kk_id')
                                ->label('Pendidikan Dalam KK')
                                ->formatStateUsing(function ($state) {
                                    $pend = DB::table('tweb_penduduk_pendidikan_kk')->find($state);
                                    return $pend->nama ?? '-';
                                }),

                            TextEntry::make('pekerjaan_id')
                                ->label('Pekerjaan Utama')
                                ->formatStateUsing(function ($state) {
                                    $pek = DB::table('tweb_penduduk_pekerjaan')->find($state);
                                    return $pek->nama ?? '-';
                                }),

                            TextEntry::make('wilayah.dusun')
                                ->label('Dusun / Wilayah Tempat Tinggal')
                                ->placeholder('-'),
                        ]),
                    ]),

                Section::make('Informasi Silsilah Orang Tua & Kontak')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('nama_ayah')
                                ->label('Nama Ayah Kandung')
                                ->placeholder('-'),

                            TextEntry::make('nama_ibu')
                                ->label('Nama Ibu Kandung')
                                ->placeholder('-'),

                            TextEntry::make('telepon')
                                ->label('Nomor Telepon / WA')
                                ->placeholder('-'),

                            TextEntry::make('email')
                                ->label('Alamat Email')
                                ->placeholder('-'),

                            TextEntry::make('alamat_sekarang')
                                ->label('Alamat Rumah Sekarang')
                                ->columnSpan(2)
                                ->placeholder('-'),
                        ]),
                    ]),
            ]);
    }
}
