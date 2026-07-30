<?php

namespace App\Filament\Resources\SuratMasuks\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SuratMasukInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('config_id')
                    ->numeric(),
                TextEntry::make('nomor_urut')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('tanggal_penerimaan')
                    ->date(),
                TextEntry::make('nomor_surat')
                    ->placeholder('-'),
                TextEntry::make('kode_surat')
                    ->placeholder('-'),
                TextEntry::make('tanggal_surat')
                    ->date(),
                TextEntry::make('pengirim')
                    ->placeholder('-'),
                TextEntry::make('isi_singkat')
                    ->placeholder('-'),
                TextEntry::make('isi_disposisi')
                    ->placeholder('-'),
                TextEntry::make('berkas_scan')
                    ->placeholder('-'),
                TextEntry::make('lokasi_arsip')
                    ->placeholder('-'),
            ]);
    }
}
