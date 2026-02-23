@props(['raffle'])

<div class="group bg-card-dark rounded-2xl border border-white/5 overflow-hidden flex flex-col hover:border-white/20 transition-all hover:shadow-[0_10px_30px_-10px_rgba(0,0,0,0.7)] font-display">
    <div class="aspect-[16/10] overflow-hidden bg-black relative">
         @if($raffle->images && count($raffle->images) > 0)
            <img alt="{{ $raffle->title }}" 
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-90 group-hover:opacity-100" 
                 src="{{ Storage::url($raffle->images[0]) }}"/>
        @endif
        
        {{-- Optional: Add status badge if needed --}}
        @if($raffle->isActive())
             <div class="absolute top-4 right-4 z-20 bg-green-500/20 backdrop-blur-md px-3 py-1 rounded-md border border-green-500/50 text-xs font-bold text-green-400 uppercase">
                {{ __('messages.active') }}
            </div>
        @else
             <div class="absolute top-4 right-4 z-20 bg-red-500/20 backdrop-blur-md px-3 py-1 rounded-md border border-red-500/50 text-xs font-bold text-red-400 uppercase">
                {{ $raffle->effective_status->getLabel() }}
            </div>
        @endif
    </div>
    <div class="p-8">
        <a href="{{ route('raffles.show', $raffle->slug) }}" wire:navigate>
            <h3 class="text-2xl font-bold text-white mb-2 line-clamp-1">{{ $raffle->title }}</h3>
        </a>
        <p class="text-gray-400 text-sm mb-8 leading-relaxed line-clamp-2">{{ $raffle->description }}</p>
        
        <div class="mb-8">
            <div class="flex justify-between items-end mb-3">
                <span class="text-[10px] uppercase tracking-[0.2em] text-gray-500 font-bold">{{ __('messages.tickets_sold') }}</span>
                <span class="text-xs font-bold text-accent-cyan">{{ $raffle->sold_percentage }}%</span>
            </div>
            <div class="progress-bar">
                <div class="h-full bg-accent-cyan rounded-full shadow-[0_0_10px_rgba(0,242,255,0.5)]" style="width:{{ $raffle->sold_percentage }}%"></div>
            </div>
        </div>
        
        <div class="flex items-center justify-between pt-6 border-t border-white/5">
            <div>
                <span class="block text-[10px] uppercase tracking-[0.2em] text-gray-500 font-bold mb-1">{{ __('messages.entry_price') }}</span>
                <span class="text-3xl font-bold">{{ \App\Helpers\CurrencyHelper::format($raffle->ticket_price) }}</span>
            </div>
            <a href="{{ route('raffles.show', $raffle->slug) }}" wire:navigate 
               class="bg-[#1A1A1A] hover:bg-white hover:text-black border border-white/10 text-white px-8 py-3 rounded-xl text-sm font-bold transition-all duration-300">
                {{ __('messages.enter_now') }}
            </a>
        </div>
    </div>
</div>
