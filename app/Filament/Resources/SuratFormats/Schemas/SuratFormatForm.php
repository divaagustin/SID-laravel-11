<?php

namespace App\Filament\Resources\SuratFormats\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\KeyValue;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SuratFormatForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Dasar Format Surat')
                    ->description('Atur judul surat, kode arsip, URL slug, dan masa berlaku dokumen.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nama')
                            ->label('Nama Format Surat')
                            ->required()
                            ->placeholder('Contoh: Surat Keterangan Usaha')
                            ->columnSpanFull(),

                        TextInput::make('kode_surat')
                            ->label('Kode Surat / Klasifikasi')
                            ->placeholder('Contoh: S-01'),

                        TextInput::make('url_surat')
                            ->label('URL Slug / Identifikasi')
                            ->required()
                            ->placeholder('Contoh: keterangan_usaha'),

                        TextInput::make('masa_berlaku')
                            ->label('Masa Berlaku')
                            ->numeric()
                            ->default(1),

                        Select::make('satuan_masa_berlaku')
                            ->label('Satuan Masa Berlaku')
                            ->options([
                                'H' => 'Hari',
                                'W' => 'Minggu',
                                'M' => 'Bulan',
                                'Y' => 'Tahun',
                            ])
                            ->default('M'),

                        TextInput::make('format_nomor')
                            ->label('Format Nomor Kustom (Opsional)')
                            ->placeholder('Kosongkan untuk penomoran standar global')
                            ->columnSpanFull(),
                    ]),

                Section::make('Pengaturan Fitur & TTE')
                    ->description('Pengaturan akses layanan mandiri warga dan sertifikasi TTE Kades.')
                    ->columns(2)
                    ->schema([
                        Toggle::make('mandiri')
                            ->label('Tampilkan di Layanan Mandiri (Warga)')
                            ->helperText('Warga dapat mengajukan surat ini via portal.')
                            ->default(true),

                        Toggle::make('qr_code_tte')
                            ->label('Gunakan TTE Kepala Desa')
                            ->helperText('Sertifikasi Tanda Tangan Elektronik.')
                            ->default(false),

                        Toggle::make('qr_code')
                            ->label('Cetak QR Code Validasi')
                            ->default(true),

                        Toggle::make('logo_garuda')
                            ->label('Gunakan Logo Garuda')
                            ->helperText('Gunakan Garuda di kop surat.')
                            ->default(false),

                        Toggle::make('favorit')
                            ->label('Tandai sebagai Favorit')
                            ->default(false),
                    ]),

                Section::make('Draf Isi Paragraf Surat & Tata Letak')
                    ->description('Kop Surat (Header) dan Tempat Tanda Tangan Kades (Footer) otomatis dipasang sistem. Cukup edit paragraf isi surat di bawah ini.')
                    ->schema([
                        RichEditor::make('template_desa')
                            ->label('Teks Isi Surat (Dilengkapi Tombol Rata Tengah, Kanan, Kiri, Justify & Penomoran)')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'strike',
                                'h2',
                                'h3',
                                'bulletList',
                                'orderedList',
                                'blockquote',
                                'undo',
                                'redo',
                            ])
                            ->helperText('💡 PETUNJUK: Gunakan toolbar di atas untuk mengatur perataan paragraf (Rata Tengah, Rata Kiri-Kanan/Justify, Rata Kanan, Penomoran, & Cetak Tebal). Kata [NAMA_PEMOHON], [NIK_PEMOHON], [ALAMAT], [NOMOR_SURAT], dan [KEPERLUAN] otomatis diisi data warga.')
                            ->placeholder('Yang bertanda tangan di bawah ini Kepala Desa Serdang, Kecamatan Meranti, Kabupaten Asahan menerangkan dengan sebenarnya bahwa...'),

                        KeyValue::make('form_isian_visual')
                            ->label('Pertanyaan / Isian Tambahan untuk Warga (Visual — Tanpa Kode JSON)')
                            ->keyLabel('Nama Pertanyaan / Isian (Contoh: Nama Usaha)')
                            ->valueLabel('Keterangan / Petunjuk (Contoh: Masukkan nama toko/usaha warga)')
                            ->addActionLabel('+ Tambah Pertanyaan Isian Warga')
                            ->helperText('💡 Cukup klik tombol "+ Tambah Pertanyaan Isian Warga" untuk menambah kolom pertanyaan yang harus diisi warga saat mengajukan surat ini. Tidak perlu mengetik kode JSON manual.'),
                    ]),
            ]);
    }
}
