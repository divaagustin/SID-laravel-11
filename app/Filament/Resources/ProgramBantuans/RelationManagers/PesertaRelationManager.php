<?php

namespace App\Filament\Resources\ProgramBantuans\RelationManagers;

use App\Models\Penduduk;
use App\Models\PesertaBantuan;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PesertaRelationManager extends RelationManager
{
    protected static string $relationship = 'peserta';
    protected static ?string $title = 'Daftar Peserta / Penerima Bantuan';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('config_id')->default(1),

                Section::make('Cari Data Warga / Penerima')
                    ->schema([
                        Select::make('kartu_id_pend')
                            ->label('Cari Penduduk Desa')
                            ->placeholder('Ketik NIK atau Nama Penduduk...')
                            ->searchable()
                            ->required()
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
                            ->afterStateUpdated(function (Set $set, ?string $state, RelationManager $livewire) {
                                if ($state) {
                                    $programId = $livewire->getOwnerRecord()->id;
                                    $exists = PesertaBantuan::where('program_id', $programId)
                                        ->where('kartu_id_pend', $state)
                                        ->exists();

                                    if ($exists) {
                                        Notification::make()
                                            ->title('Peringatan')
                                            ->body('Warga ini sudah terdaftar sebagai penerima pada program bantuan ini.')
                                            ->danger()
                                            ->send();
                                    }

                                    $penduduk = Penduduk::find($state);
                                    if ($penduduk) {
                                        $set('peserta', $penduduk->nik);
                                        $set('kartu_nik', $penduduk->nik);
                                        $set('kartu_nama', $penduduk->nama);
                                        $set('kartu_tempat_lahir', $penduduk->tempatlahir ?? '-');
                                        $set('kartu_tanggal_lahir', $penduduk->tanggallahir?->format('Y-m-d'));
                                        $set('kartu_alamat', $penduduk->alamat_sekarang ?? 'Desa');
                                    }
                                }
                            }),
                    ]),

                Section::make('Kartu & Identitas Penerima')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('no_id_kartu')
                                ->label('Nomor Kartu Bantuan (KKS/PKH/KIS)')
                                ->placeholder('Contoh: 3201881299901'),
                            TextInput::make('kartu_nik')
                                ->label('NIK Penerima')
                                ->required(),
                            TextInput::make('kartu_nama')
                                ->label('Nama Penerima')
                                ->required(),
                            TextInput::make('kartu_tempat_lahir')
                                ->label('Tempat Lahir')
                                ->required(),
                            DatePicker::make('kartu_tanggal_lahir')
                                ->label('Tanggal Lahir')
                                ->required(),
                            TextInput::make('kartu_alamat')
                                ->label('Alamat / Dusun')
                                ->required(),
                        ]),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('kartu_nama')
            ->columns([
                TextColumn::make('kartu_nik')
                    ->label('NIK')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kartu_nama')
                    ->label('Nama Penerima')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('no_id_kartu')
                    ->label('No. Kartu Bantuan')
                    ->placeholder('-')
                    ->searchable(),

                TextColumn::make('kartu_alamat')
                    ->label('Alamat')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Tgl Didaftarkan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()->label('Tambah Penerima Bantuan'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
