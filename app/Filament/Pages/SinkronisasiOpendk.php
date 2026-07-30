<?php

namespace App\Filament\Pages;

use App\Services\OpenDkSyncService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class SinkronisasiOpendk extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-path';
    protected static ?string $navigationLabel = 'Sinkronisasi OpenDK';
    protected static string|\UnitEnum|null $navigationGroup = 'Pengaturan Sistem';
    protected static ?int $navigationSort = 3;
    protected string $view = 'filament.pages.sinkronisasi-opendk';
    protected static ?string $title = 'Sinkronisasi Data Supra Desa (OpenDK)';

    public static function canAccess(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sync_now')
                ->label('Sinkronkan Data Sekarang')
                ->icon('heroicon-o-arrow-path')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Sinkronkan Data ke Server OpenDK?')
                ->modalDescription('Tindakan ini akan memformat payload data kependudukan dan persuratan desa, lalu mengirimkannya ke REST API OpenDK Kecamatan dengan enkripsi HMAC SHA256.')
                ->action(function () {
                    $openDkService = app(OpenDkSyncService::class);

                    if (! $openDkService->isConfigured()) {
                        Notification::make()
                            ->title('OpenDK API Belum Dikonfigurasi')
                            ->body('Isi OPENDK_URL dan OPENDK_API_KEY di file .env terlebih dahulu.')
                            ->warning()
                            ->persistent()
                            ->send();
                        return;
                    }

                    $result = $openDkService->sync();

                    if ($result['success']) {
                        Notification::make()
                            ->title('✅ Sinkronisasi Berhasil!')
                            ->body($result['message'])
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('❌ Sinkronisasi Gagal')
                            ->body($result['message'])
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
