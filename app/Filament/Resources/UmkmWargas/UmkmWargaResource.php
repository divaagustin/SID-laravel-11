<?php

namespace App\Filament\Resources\UmkmWargas;

use App\Models\UmkmWarga;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UmkmWargaResource extends Resource
{
    protected static ?string $modelLabel = 'UMKM Warga';

    protected static ?string $pluralModelLabel = 'UMKM & Usaha Warga';

    protected static ?string $model = UmkmWarga::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationLabel = 'UMKM & Usaha Warga';

    protected static string|\UnitEnum|null $navigationGroup = 'Pembangunan, Aset & BUMDes';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi UMKM & Usaha Warga')
                    ->schema([
                        TextInput::make('nama_usaha')
                            ->label('Nama Usaha / Produk')
                            ->required()
                            ->maxLength(150),

                        Select::make('kategori_usaha')
                            ->label('Kategori Usaha')
                            ->options([
                                'Kuliner' => 'Kuliner / Olahan Makanan',
                                'Sembako' => 'Sembako & Kelontong',
                                'Elektronik/Konter' => 'Elektronik & Konter Pulsa',
                                'Pertanian' => 'Pertanian & Hasil Bumi',
                                'Pabrik/Manufaktur' => 'Pabrik / Usaha Kecil',
                                'Jasa_Tetap' => 'Jasa Tetap (Bengkel, Jahit, Salon)',
                                'Lainnya' => 'Lainnya',
                            ])
                            ->required(),

                        TextInput::make('nik_pemilik')
                            ->label('NIK Pemilik Usaha')
                            ->required()
                            ->maxLength(20),

                        TextInput::make('no_whatsapp')
                            ->label('Nomor WhatsApp')
                            ->required()
                            ->tel()
                            ->maxLength(25),

                        TextInput::make('jam_operasional')
                            ->label('Jam Operasional')
                            ->default('08.00 - 17.00 WIB')
                            ->maxLength(100),

                        Select::make('status_operasional')
                            ->label('Status Operasional')
                            ->options([
                                'buka' => 'Buka',
                                'tutup' => 'Tutup',
                            ])
                            ->default('buka')
                            ->required(),

                        Select::make('status_moderasi')
                            ->label('Status Moderasi Desa')
                            ->options([
                                'pending' => 'Pending (Menunggu Moderasi)',
                                'approved' => 'Approved (Disetujui & Tayang)',
                                'rejected' => 'Rejected (Ditolak)',
                            ])
                            ->default('pending')
                            ->required(),

                        FileUpload::make('foto_usaha')
                            ->label('Foto Usaha / Produk')
                            ->image()
                            ->directory('galeri')
                            ->columnSpanFull(),

                        Textarea::make('deskripsi_produk')
                            ->label('Deskripsi Produk & Layanan')
                            ->rows(3)
                            ->columnSpanFull(),

                        Textarea::make('alamat_usaha')
                            ->label('Alamat Lokasi Usaha')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto_usaha')
                    ->label('Foto')
                    ->circular(),

                TextColumn::make('nama_usaha')
                    ->label('Nama Usaha')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('kategori_usaha')
                    ->label('Kategori')
                    ->badge()
                    ->color('info'),

                TextColumn::make('nik_pemilik')
                    ->label('NIK Pemilik')
                    ->searchable(),

                TextColumn::make('no_whatsapp')
                    ->label('No. WhatsApp'),

                TextColumn::make('status_operasional')
                    ->label('Operasional')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'buka' => 'success',
                        'tutup' => 'danger',
                        default => 'secondary',
                    }),

                TextColumn::make('status_moderasi')
                    ->label('Moderasi Desa')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        default => 'secondary',
                    }),

                TextColumn::make('created_at')
                    ->label('Tanggal Pengajuan')
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
                SelectFilter::make('kategori_usaha')
                    ->label('Kategori Usaha'),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (UmkmWarga $record) {
                        $record->update(['status_moderasi' => 'approved']);
                        Notification::make()
                            ->title('UMKM Disetujui')
                            ->body("UMKM {$record->nama_usaha} kini resmi tayang di portal desa.")
                            ->success()
                            ->send();
                    })
                    ->visible(fn (UmkmWarga $record) => $record->status_moderasi !== 'approved'),

                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (UmkmWarga $record) {
                        $record->update(['status_moderasi' => 'rejected']);
                        Notification::make()
                            ->title('UMKM Ditolak')
                            ->body("Pengajuan UMKM {$record->nama_usaha} ditolak.")
                            ->warning()
                            ->send();
                    })
                    ->visible(fn (UmkmWarga $record) => $record->status_moderasi !== 'rejected'),

                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUmkmWargas::route('/'),
            'create' => Pages\CreateUmkmWarga::route('/create'),
            'edit' => Pages\EditUmkmWarga::route('/{record}/edit'),
        ];
    }
}
