<?php

namespace App\Filament\Pages;

use App\Models\Penduduk;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class DptPage extends Page implements HasTable
{
    use InteractsWithTable;

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return 'heroicon-o-check-badge';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Kependudukan';
    }

    public static function getNavigationLabel(): string
    {
        return 'Daftar Pemilih Tetap (DPT)';
    }

    public function getTitle(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return 'Daftar Pemilih Tetap (DPT Pemilu / Pilkades)';
    }

    public static function getNavigationSort(): ?int
    {
        return 4;
    }

    protected string $view = 'filament.pages.dpt-page';

    public function table(Table $table): Table
    {
        return $table
            ->query(Penduduk::query()->dpt())
            ->columns([
                TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama')
                    ->label('Nama Pemilih')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('jenis_kelamin_label')
                    ->label('L/P')
                    ->badge(),

                TextColumn::make('tanggallahir')
                    ->label('Tanggal Lahir')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('umur')
                    ->label('Usia')
                    ->suffix(' Thn')
                    ->sortable(),

                TextColumn::make('alamat_sekarang')
                    ->label('Alamat / Dusun')
                    ->searchable(),
            ]);
    }
}
