<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\KartuRingkasanStatsWidget;
use App\Filament\Widgets\DemografiChartWidget;
use App\Filament\Widgets\ApbdesTransparansiWidget;
use App\Filament\Widgets\GrafikSuratBansosWidget;
use App\Filament\Widgets\PintasanCepatWidget;
use App\Filament\Widgets\PotensiBumdesWidget;
use App\Filament\Widgets\ProyekPembangunanWidget;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $config = Cache::remember('global_config_brand', 3600, fn () => DB::table('config')->first());
        $namaDesa = $config->nama_desa ?? '';
        $logoUrl = function_exists('get_media_url') ? get_media_url($config->logo ?? null, 'logo') : asset('storage/' . ($config->logo ?? 'logo.png'));

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(\App\Filament\Pages\Auth\Login::class)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->spa()
            ->brandName('Panel Admin — Pemerintah Desa ' . ($namaDesa ?: 'Serdang'))
            ->brandLogo(new \Illuminate\Support\HtmlString('
                <div class="admin-brand-box" style="display: flex; align-items: center; gap: 10px; text-align: left;">
                    <img src="' . e($logoUrl) . '" alt="Logo" style="height: 38px; max-height: 38px; width: auto; max-width: 44px; object-fit: contain; flex-shrink: 0; filter: drop-shadow(0 1px 3px rgba(0,0,0,0.3));" />
                    <div style="display: flex; flex-direction: column; text-align: left; line-height: 1.25;">
                        <span style="font-size: 10px; font-weight: 900; letter-spacing: 0.1em; text-transform: uppercase; color: #f59e0b;">PANEL ADMIN</span>
                        <span style="font-size: 13px; font-weight: 800; color: inherit; white-space: nowrap;">Pemerintah Desa ' . e($namaDesa ?: 'Serdang') . '</span>
                    </div>
                </div>
            '))
            ->brandLogoHeight('2.5rem')
            ->renderHook(
                \Filament\View\PanelsRenderHook::HEAD_END,
                fn () => new \Illuminate\Support\HtmlString('
                    <style>
                        body:has(.fi-simple-layout), .fi-simple-layout, .fi-simple-main-ctn {
                            background-image: linear-gradient(135deg, rgba(6, 78, 59, 0.40), rgba(15, 23, 42, 0.55)), url("' . asset('images/bg/panorama2.jpeg') . '") !important;
                            background-size: cover !important;
                            background-position: center !important;
                            background-repeat: no-repeat !important;
                            background-attachment: fixed !important;
                        }
                        .fi-simple-layout .fi-simple-main {
                            background: rgba(11, 40, 24, 0.50) !important;
                            backdrop-filter: blur(24px) !important;
                            -webkit-backdrop-filter: blur(24px) !important;
                            border: 1px solid rgba(255, 255, 255, 0.22) !important;
                            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.65) !important;
                            border-radius: 1.75rem !important;
                        }
                        .fi-simple-layout .fi-logo {
                            display: flex !important;
                            justify-content: center !important;
                            text-align: center !important;
                            margin-left: auto !important;
                            margin-right: auto !important;
                            width: 100% !important;
                        }
                        .fi-simple-layout .admin-brand-box {
                            flex-direction: column !important;
                            text-align: center !important;
                            justify-content: center !important;
                            gap: 6px !important;
                        }
                        .fi-simple-layout .admin-brand-box img {
                            height: 48px !important;
                            max-height: 48px !important;
                            max-width: 52px !important;
                        }
                        .fi-simple-layout .admin-brand-box div {
                            text-align: center !important;
                            align-items: center !important;
                        }
                        .fi-simple-main h1 {
                            display: none !important;
                        }
                        /* Rich Editor Spacing & Height Fix */
                        .fi-fo-rich-editor,
                        .fi-fo-rich-editor > div,
                        .fi-fo-rich-editor-editor,
                        .fi-fo-rich-editor trix-editor,
                        trix-editor,
                        .trix-content {
                            min-height: 320px !important;
                            height: auto !important;
                            max-height: none !important;
                            display: block !important;
                            padding: 1rem 1.25rem !important;
                            line-height: 1.7 !important;
                            font-size: 0.95rem !important;
                            overflow-y: auto !important;
                        }
                        trix-editor:empty:not(:focus)::before,
                        trix-editor[aria-label]:empty:not(:focus)::before {
                            color: #94a3b8 !important;
                        }
                    </style>
                ')
            )
            ->navigationGroups([
                'Info Desa',
                'Kependudukan',
                'Layanan Persuratan',
                'Bantuan Sosial & Kesehatan',
                'Pertanahan & GIS',
                'Pembangunan, Aset & BUMDes',
                'Admin Web & Pengaduan',
                'Pengaturan Sistem',
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                KartuRingkasanStatsWidget::class,
                DemografiChartWidget::class,
                ApbdesTransparansiWidget::class,
                GrafikSuratBansosWidget::class,
                PintasanCepatWidget::class,
                PotensiBumdesWidget::class,
                ProyekPembangunanWidget::class,
                AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
