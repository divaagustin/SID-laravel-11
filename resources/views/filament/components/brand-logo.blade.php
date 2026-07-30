@php
    $config = \Illuminate\Support\Facades\Cache::remember('global_config_logo', 60, fn () => \Illuminate\Support\Facades\DB::table('config')->first());
    $namaDesa = $config->nama_desa ?? '';
    $logoUrl = function_exists('get_media_url') ? get_media_url($config->logo ?? null, 'logo') : asset('storage/' . ($config->logo ?? 'logo.png'));
@endphp

<div class="flex items-center gap-2 max-w-[200px] overflow-hidden py-1">
    <img src="{{ $logoUrl }}" alt="Logo Desa" class="h-7 w-7 object-contain flex-shrink-0 rounded drop-shadow-sm">
    <div class="flex flex-col min-w-0">
        <span class="text-xs font-extrabold tracking-tight text-gray-900 dark:text-white leading-none truncate">
            OpenSID <span class="text-amber-500">v2</span>
        </span>
        <span class="text-[9px] font-bold text-gray-500 dark:text-gray-400 leading-tight uppercase truncate mt-0.5">
            {{ $namaDesa ? 'Desa ' . $namaDesa : 'Pemerintah Desa' }}
        </span>
    </div>
</div>
