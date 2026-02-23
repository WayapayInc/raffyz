<x-filament-panels::page>
    <form wire:submit="verify" class="flex flex-col gap-y-6">
        {{ $this->form }}
        
        <div class="flex justify-end mt-6" style="margin-top: 1.5rem;">
            <x-filament::button type="submit" wire:target="verify">
                Verify License
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
