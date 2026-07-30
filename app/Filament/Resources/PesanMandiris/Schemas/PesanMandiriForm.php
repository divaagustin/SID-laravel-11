<?php

namespace App\Filament\Resources\PesanMandiris\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PesanMandiriForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Detail Pengaduan / Aspirasi')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('subjek')
                            ->label('Subjek Pengaduan')
                            ->required(),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                1 => '📩 Baru / Belum Dibaca',
                                2 => '💬 Sudah Dibalas',
                                3 => '✅ Selesai / Ditindaklanjuti',
                            ])
                            ->required(),
                    ]),

                    Textarea::make('komentar')
                        ->label('Isi Pesan / Pengaduan')
                        ->rows(4)
                        ->required(),

                    Textarea::make('permohonan')
                        ->label('Tanggapan / Balasan Perangkat Desa')
                        ->placeholder('Tulis pesan balasan untuk warga di sini...')
                        ->rows(4),
                ]),
        ]);
    }
}
