<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}
    </form>

    <div class="py-8">
        <x-filament::button wire:click="save" wire:loading.attr="disabled">
            <div class="flex items-center gap-2">
                <span>{{ __('messages.save_changes') }}</span>
            </div>
        </x-filament::button>
    </div>
</x-filament-panels::page>
