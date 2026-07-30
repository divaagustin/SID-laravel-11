<?php

namespace App\Filament\Pages;

use App\Services\WhatsappNotificationService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class PengaturanWhatsapp extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    protected static ?string $navigationLabel = 'Pengaturan WhatsApp';
    protected static string|\UnitEnum|null $navigationGroup = 'Pengaturan Sistem';
    protected static ?int $navigationSort = 2;
    protected string $view = 'filament.pages.pengaturan-whatsapp';
    protected static ?string $title = 'Pengaturan & Tes Gateway WhatsApp';

    public static function canAccess(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('tes_kirim_wa')
                ->label('Kirim Tes Pesan WA')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->form([
                    TextInput::make('target_phone')
                        ->label('Nomor WhatsApp Tujuan')
                        ->placeholder('Contoh: 08123456789')
                        ->required(),

                    Textarea::make('message')
                        ->label('Isi Pesan Tes')
                        ->default("Tes pesan notifikasi WhatsApp dari Sistem Layanan Desa.\n\nJika Anda menerima pesan ini, gateway WhatsApp terhubung dengan baik.")
                        ->required(),
                ])
                ->action(function (array $data) {
                    $waService = app(WhatsappNotificationService::class);

                    if (! $waService->isConfigured()) {
                        Notification::make()
                            ->title('WhatsApp Gateway Belum Dikonfigurasi')
                            ->body('Isi WA_GATEWAY_TOKEN di file .env terlebih dahulu.')
                            ->warning()
                            ->persistent()
                            ->send();
                        return;
                    }

                    $result = $waService->send($data['target_phone'], $data['message']);

                    if ($result['success']) {
                        Notification::make()
                            ->title('✅ Pesan Berhasil Dikirim!')
                            ->body("Pesan tes berhasil dikirim ke {$data['target_phone']}.")
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('❌ Gagal Mengirim Pesan')
                            ->body($result['message'])
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
