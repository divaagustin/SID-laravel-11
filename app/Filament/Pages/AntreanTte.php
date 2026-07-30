<?php

namespace App\Filament\Pages;

use App\Models\LogSurat;
use App\Services\TteService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Actions\Action as TableAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class AntreanTte extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPencilSquare;
    protected static ?string $navigationLabel = 'Antrean TTE (Kades)';
    protected static string|\UnitEnum|null $navigationGroup = 'Layanan Persuratan';
    protected static ?int $navigationSort = 3;
    protected string $view = 'filament.pages.antrean-tte';
    protected static ?string $title = 'Antrean Tanda Tangan Elektronik (TTE)';

    public static function canAccess(): bool
    {
        return auth()->user()?->canTte() ?? false;
    }

    /**
     * Tampilkan badge jumlah surat menunggu TTE di sidebar
     */
    public static function getNavigationBadge(): ?string
    {
        $count = \Illuminate\Support\Facades\Cache::remember('nav_badge_antrean_tte_count', 30, fn () => LogSurat::where('tte', 0)
            ->where('status', 1)
            ->whereNotNull('lampiran')
            ->count());

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }

    protected function getHeaderActions(): array
    {
        $tte = app(TteService::class);

        return [
            Action::make('cek_koneksi')
                ->label('Cek Koneksi BSrE')
                ->icon('heroicon-o-signal')
                ->color('gray')
                ->action(function () use ($tte) {
                    $result = $tte->checkConnection();

                    if ($result['connected']) {
                        Notification::make()
                            ->title('✅ Terhubung ke BSrE')
                            ->body('Server BSrE dapat diakses.')
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('❌ Gagal terhubung ke BSrE')
                            ->body($result['message'])
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                LogSurat::query()
                    ->where('status', 1)
                    ->orderBy('tanggal', 'desc')
            )
            ->columns([
                TextColumn::make('no_surat')
                    ->label('No. Surat')
                    ->searchable()
                    ->weight('semibold'),

                TextColumn::make('nama_surat')
                    ->label('Jenis Surat')
                    ->searchable()
                    ->limit(40),

                TextColumn::make('penduduk.nama')
                    ->label('Pemohon')
                    ->limit(30),

                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable(),

                IconColumn::make('tte')
                    ->label('Status TTE')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-clock')
                    ->trueColor('success')
                    ->falseColor('warning'),

                TextColumn::make('verifikasi_kades')
                    ->label('Paraf Kades')
                    ->formatStateUsing(fn ($state) => match($state) {
                        1 => '✅ Disetujui',
                        2 => '❌ Ditolak',
                        default => '⏳ Menunggu',
                    }),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('tte')
                    ->label('Status TTE')
                    ->trueLabel('Sudah TTE')
                    ->falseLabel('Belum TTE'),
            ])
            ->actions([
                TableAction::make('kirim_tte')
                    ->label('Kirim ke BSrE')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Kirim Surat ke BSrE untuk Ditandatangani')
                    ->modalDescription('Surat PDF akan dikirimkan ke server BSrE menggunakan TTE Kepala Desa. Pastikan PDF sudah benar sebelum melanjutkan.')
                    ->visible(fn ($record) => ! $record->tte)
                    ->action(function ($record) {
                        $tte = app(TteService::class);

                        if (! $tte->isConfigured()) {
                            Notification::make()
                                ->title('BSrE belum dikonfigurasi')
                                ->body('Isi BSRE_URL, BSRE_USERNAME, BSRE_PASSWORD, dan BSRE_NIK_KEPALA_DESA di file .env terlebih dahulu. Daftar di https://tte.bssn.go.id')
                                ->warning()
                                ->persistent()
                                ->send();
                            return;
                        }

                        // Tentukan path PDF dari kolom lampiran (OpenSID menyimpan path PDF di kolom ini)
                        $pdfRelativePath = $record->lampiran ?? $record->lokasi_arsip;
                        if (! $pdfRelativePath) {
                            Notification::make()
                                ->title('File PDF tidak ditemukan')
                                ->body('Surat ini belum memiliki file PDF yang bisa dikirim ke BSrE.')
                                ->danger()
                                ->send();
                            return;
                        }

                        $pdfAbsPath = Storage::disk('local')->path($pdfRelativePath);
                        $nikTtd = config('services.bsre.nik_ttd');

                        $result = $tte->signPdf($pdfAbsPath, $nikTtd, [
                            'tampilan' => 'visible',
                            'image'    => true,
                        ]);

                        if ($result['success']) {
                            // Simpan PDF yang sudah ditandatangani menimpa file lama
                            Storage::disk('local')->put($pdfRelativePath, $result['signed_pdf']);

                            $record->update(['tte' => true]);

                            Notification::make()
                                ->title('✅ TTE Berhasil!')
                                ->body("Surat {$record->no_surat} telah ditandatangani secara elektronik via BSrE.")
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('❌ TTE Gagal')
                                ->body($result['message'])
                                ->danger()
                                ->send();
                        }
                    }),

                TableAction::make('verifikasi')
                    ->label('Verifikasi PDF')
                    ->icon('heroicon-o-shield-check')
                    ->color('info')
                    ->visible(fn ($record) => (bool) $record->tte)
                    ->action(function ($record) {
                        $tte = app(TteService::class);

                        if (! $tte->isConfigured()) {
                            Notification::make()
                                ->title('BSrE belum dikonfigurasi')
                                ->body('Isi konfigurasi BSrE di .env terlebih dahulu.')
                                ->warning()
                                ->send();
                            return;
                        }

                        $pdfRelativePath = $record->lampiran ?? $record->lokasi_arsip;
                        if (! $pdfRelativePath) {
                            Notification::make()
                                ->title('File PDF tidak ditemukan')
                                ->danger()
                                ->send();
                            return;
                        }

                        $pdfAbsPath = Storage::disk('local')->path($pdfRelativePath);
                        $result = $tte->verifyPdf($pdfAbsPath);

                        if ($result['valid']) {
                            Notification::make()
                                ->title('✅ Tanda tangan valid')
                                ->body('PDF ini telah ditandatangani secara sah menggunakan TTE BSrE.')
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('⚠️ Tanda tangan tidak valid atau tidak ditemukan')
                                ->body($result['message'])
                                ->warning()
                                ->send();
                        }
                    }),
            ])
            ->emptyStateIcon('heroicon-o-pencil-square')
            ->emptyStateHeading('Tidak ada surat dalam antrean TTE')
            ->emptyStateDescription('Surat yang siap ditandatangani secara elektronik akan muncul di sini.');
    }
}
