<?php

namespace App\Filament\Resources\JasaWargas;

use App\Models\JasaWarga;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class JasaWargaResource extends Resource
{
    protected static ?string $modelLabel = 'Jasa Warga';

    protected static ?string $pluralModelLabel = 'Jasa & Lowongan Warga';

    protected static ?string $model = JasaWarga::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Jasa & Lowongan Warga';

    protected static string|\UnitEnum|null $navigationGroup = 'Pembangunan, Aset & BUMDes';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Lowongan Jasa / Pekerjaan Harian')
                    ->schema([
                        TextInput::make('judul_pekerjaan')
                            ->label('Judul Lowongan Pekerjaan')
                            ->required()
                            ->maxLength(150),

                        Select::make('kategori')
                            ->label('Kategori Pekerjaan')
                            ->options([
                                'Pertukangan' => 'Pertukangan & Bangunan',
                                'Pertanian' => 'Pertanian & Perkebunan',
                                'Kebersihan' => 'Kebersihan & Taman',
                                'Transportasi' => 'Transportasi & Angkut Barang',
                                'Jasa_Harian' => 'Jasa Harian (Cuci, Masak, Asuh)',
                                'Lainnya' => 'Lainnya',
                            ])
                            ->required(),

                        TextInput::make('fee_insentif')
                            ->label('Fee Insentif / Upah (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),

                        TextInput::make('nik_pembuat')
                            ->label('NIK Pembuat Posting')
                            ->required()
                            ->maxLength(20),

                        TextInput::make('lokasi_dusun_rt')
                            ->label('Lokasi Dusun / RT')
                            ->required()
                            ->maxLength(100),

                        DateTimePicker::make('tenggat_waktu')
                            ->label('Tenggat Waktu Pekerjaan'),

                        Select::make('status_job')
                            ->label('Status Job')
                            ->options([
                                'open' => 'Open (Tersedia)',
                                'in_progress' => 'In Progress (Dalam Pengerjaan)',
                                'completed' => 'Completed (Selesai)',
                                'cancelled' => 'Cancelled (Dibatalkan)',
                            ])
                            ->default('open')
                            ->required(),

                        Select::make('status_moderasi')
                            ->label('Status Moderasi Desa')
                            ->options([
                                'pending' => 'Pending (Menunggu Moderasi)',
                                'approved' => 'Approved (Disetujui)',
                                'rejected' => 'Rejected (Ditolak)',
                            ])
                            ->default('pending')
                            ->required(),

                        Textarea::make('deskripsi_tugas')
                            ->label('Deskripsi Rincian Tugas')
                            ->rows(3)
                            ->columnSpanFull()
                            ->required(),

                        Textarea::make('alamat_detail')
                            ->label('Alamat Detail Lokasi')
                            ->rows(2)
                            ->columnSpanFull()
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul_pekerjaan')
                    ->label('Judul Pekerjaan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('kategori')
                    ->label('Kategori')
                    ->badge()
                    ->color('info'),

                TextColumn::make('fee_insentif')
                    ->label('Fee / Upah')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('lokasi_dusun_rt')
                    ->label('Lokasi'),

                TextColumn::make('status_job')
                    ->label('Status Job')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'success',
                        'in_progress' => 'warning',
                        'completed' => 'info',
                        'cancelled' => 'danger',
                        default => 'secondary',
                    }),

                TextColumn::make('status_moderasi')
                    ->label('Moderasi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        default => 'secondary',
                    }),

                TextColumn::make('created_at')
                    ->label('Tanggal Posting')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status_moderasi')
                    ->label('Status Moderasi')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
                SelectFilter::make('status_job')
                    ->label('Status Pekerjaan')
                    ->options([
                        'open' => 'Open',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (JasaWarga $record) {
                        $record->update(['status_moderasi' => 'approved']);
                        Notification::make()
                            ->title('Job Jasa Disetujui')
                            ->body("Lowongan {$record->judul_pekerjaan} kini resmi tayang di papan pekerjaan desa.")
                            ->success()
                            ->send();
                    })
                    ->visible(fn (JasaWarga $record) => $record->status_moderasi !== 'approved'),

                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (JasaWarga $record) {
                        $record->update(['status_moderasi' => 'rejected']);
                        Notification::make()
                            ->title('Job Jasa Ditolak')
                            ->body("Lowongan {$record->judul_pekerjaan} ditolak.")
                            ->warning()
                            ->send();
                    })
                    ->visible(fn (JasaWarga $record) => $record->status_moderasi !== 'rejected'),

                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJasaWargas::route('/'),
            'create' => Pages\CreateJasaWarga::route('/create'),
            'edit' => Pages\EditJasaWarga::route('/{record}/edit'),
        ];
    }
}
