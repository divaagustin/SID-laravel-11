<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-4 flex justify-start">
            <x-filament::button type="submit">
                Simpan Profil Desa
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
