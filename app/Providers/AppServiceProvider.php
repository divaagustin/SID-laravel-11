<?php

namespace App\Providers;

use App\Models\LogSurat;
use App\Models\Penduduk;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Policies\LogSuratPolicy;
use App\Policies\PendudukPolicy;
use App\Policies\SuratKeluarPolicy;
use App\Policies\SuratMasukPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends AuthServiceProvider
{
    /**
     * Pemetaan Model → Policy untuk RBAC OpenSID.
     */
    protected $policies = [
        LogSurat::class   => LogSuratPolicy::class,
        Penduduk::class   => PendudukPolicy::class,
        SuratMasuk::class => SuratMasukPolicy::class,
        SuratKeluar::class => SuratKeluarPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Explicitly load MediaHelper helper functions
        if (file_exists(app_path('Helpers/MediaHelper.php'))) {
            require_once app_path('Helpers/MediaHelper.php');
        }

        // Daftarkan TteService sebagai singleton agar satu instance digunakan di seluruh app
        $this->app->singleton(\App\Services\TteService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Optimized View Composer with Cache to prevent N+1 queries during Filament renders
        View::composer(['portal.*', 'layouts.*', 'mandiri.*', 'filament.*'], function ($view) {
            if (! isset($view->getData()['config'])) {
                try {
                    $config = Cache::remember('global_config', 3600, fn () => DB::table('config')->first());
                    $view->with('config', $config);
                } catch (\Exception $e) {
                    $view->with('config', null);
                }
            }
        });

        // Gate tambahan untuk aksi custom
        Gate::define('verify-surat-sekdes', function ($user, $logSurat) {
            return app(LogSuratPolicy::class)->verifySekdes($user, $logSurat);
        });

        Gate::define('verify-surat-kades', function ($user, $logSurat) {
            return app(LogSuratPolicy::class)->verifyKades($user, $logSurat);
        });

        Gate::define('kirim-tte', function ($user, $logSurat) {
            return app(LogSuratPolicy::class)->kirimTte($user, $logSurat);
        });

        Gate::define('manage-users', function ($user) {
            return $user->isAdmin();
        });
    }
}
