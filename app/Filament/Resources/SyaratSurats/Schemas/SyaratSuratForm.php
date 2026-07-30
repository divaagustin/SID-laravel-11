<?php

namespace App\Filament\Resources\SyaratSurats\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SyaratSuratForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('ref_syarat_nama')
                    ->label('Nama Syarat Dokumen')
                    ->placeholder('Contoh: Fotokopi KTP, Surat Pengantar RT')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
